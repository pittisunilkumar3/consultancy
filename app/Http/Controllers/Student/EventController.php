<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Event;
use App\Models\Payment;
use App\Models\StudyLevel;
use App\Models\University;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;


class EventController extends Controller
{

    use ResponseTrait;

    public function list(Request $request)
    {

        $event = Payment::with(['paymentable', 'user'])
            ->where('paymentable_type', Event::class)
            ->where('user_id', auth()->id())
            ->where('payment_status', PAYMENT_STATUS_PAID)
            ->whereHasMorph(
                'paymentable',
                [Event::class],
                function ($query) use ($request) {
                    if ($request->filled('search_key')) {
                        $searchKey = '%' . $request->search_key . '%';

                        $query->where(function ($q) use ($searchKey, $request) {
                            $q->where('title', 'like', $searchKey)
                                ->orWhere('date_time', 'like', $searchKey)
                                ->orWhere('price', 'like', $searchKey);

                            $matchingTypes = array_keys(array_filter(getEventMeetingType(), function($value) use ($request) {
                                return stripos($value, $request->search_key) !== false;
                            }));

                            if (!empty($matchingTypes)) {
                                $q->orWhereIn('type', $matchingTypes);
                            }
                        });
                    }
                }
            )

            ->orderBy('id', 'DESC')
            ->paginate(12);

        $event->each(function ($event) {
            if ($event->paymentable_type === Event::class) {
                $event->itemTitle = $event->paymentable->title;
                $event->itemImage = $event->paymentable->image;
                $event->itemType = $event->paymentable->type;
                $event->itemPrice = $event->paymentable->price;
                $event->itemDate = $event->paymentable->date_time;
            }
        });

        $data['myEvent'] = $event;

        if ($request->ajax()) {
            return view('student.event.partials.event-list', $data)->render();
        }

        $data['pageTitle'] = __('My Event');
        $data['activeEvent'] = 'active';

        return view('student.event.list', $data);
    }

    public function details($id)
    {
        $payment = Payment::with(['paymentable','gateway', 'transaction'])
            ->where('payments.user_id', auth()->id())
            ->where('payment_status', PAYMENT_STATUS_PAID)
            ->findOrFail(decodeId($id));

        if ($payment->paymentable_type === Event::class) {
            $event = $payment->paymentable;

            $studyLevelIds = is_array($event->study_levels) ? $event->study_levels : json_decode($event->study_levels);
            $countryIds = is_array($event->country_ids) ? $event->country_ids : json_decode($event->country_ids);
            $universityIds = is_array($event->university_ids) ? $event->university_ids : json_decode($event->university_ids);

            $data['eventData'] = $event;
            $data['paymentData'] = $payment;
            $data['eventData']->studyLevelsName = !empty($studyLevelIds)
                ? StudyLevel::whereIn('id', $studyLevelIds)->pluck('name')
                : collect();
            $data['eventData']->countryName = !empty($countryIds)
                ? Country::whereIn('id', $countryIds)->pluck('name')
                : collect();
            $data['eventData']->universityName = !empty($universityIds)
                ? University::whereIn('id', $universityIds)->pluck('name')
                : collect();
        } else {
            return redirect()->back()->with('error', 'Event not found for this payment.');
        }
        $data['pageTitle'] = __('Event Details');
        $data['activeEvent'] = 'active';
        return view('student.event.details', $data);
    }


}
