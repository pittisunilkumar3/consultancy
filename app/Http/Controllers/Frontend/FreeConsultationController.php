<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\FreeConsultation;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class FreeConsultationController extends Controller
{
    use ResponseTrait;

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:20|min:6',
            'country_ids' => 'required|array',
            'country_ids.*' => 'integer',
            'consultation_type' => 'required|integer',
            'fund_type' => 'required|integer',
            'study_level_id' => 'required|integer',
        ]);

        // Check if a pending or active consultation already exists for the user
        $existingConsultation = FreeConsultation::where('email', $request->email)
            ->whereIn('status', [STATUS_PENDING, STATUS_ACTIVE])
            ->first();

        if ($existingConsultation) {
            return $this->error([], __('You already have a pending or active consultation.'));
        }

        // Create new FreeConsultation record
        $consultation = FreeConsultation::create([
            'user_id' => auth()->id(),
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'mobile' => $validatedData['mobile'],
            'fund_type' => $validatedData['fund_type'],
            'consultation_type' => $validatedData['consultation_type'],
            'country_ids' => $validatedData['country_ids'], // This will be stored as an array
            'study_level_id' => $validatedData['study_level_id'],
        ]);

        // Notify all active admin users
        $admins = User::where(['role' => USER_ROLE_ADMIN, 'status' => STATUS_ACTIVE])->get();
        foreach ($admins as $admin) {
            // Notification for admin
            setCommonNotification(
                $admin->id,
                __('New Free Consultation Booked'),
                __('A new free consultation has been booked by ') . $validatedData['first_name'] . ' ' . $validatedData['last_name'],
                route('admin.free_consultations.index')
            );
        }

        return $this->success([], __('Your consultation has been successfully booked.'));
    }
}
