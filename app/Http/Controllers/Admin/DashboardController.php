<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ContactUs;
use App\Models\Course;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Service;
use App\Models\StudentServiceOrder;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{

    use ResponseTrait;

    public function index()
    {
        $data['pageTitle'] = __('Dashboard');
        $data['isDashboard'] = true;
        $data['activeDashboard'] = 'active';
        $data['totalEvent'] = Event::where('status',STATUS_ACTIVE)->count();
        $data['totalService'] = Service::where('status',STATUS_ACTIVE)->count();
        $data['totalAppointment'] = Appointment::where('status','!=' ,STATUS_REFUNDED)->where('appointments.payment_status', '!=', PAYMENT_STATUS_NOT_INIT)->count();
        $data['totalCourse'] = Course::where('status',STATUS_ACTIVE)->count();
        $data['totalStudent'] = User::where(['role' => USER_ROLE_STUDENT,'status' => STATUS_ACTIVE])->count();
        $data['totalStaff'] = User::where(['role' => USER_ROLE_STAFF,'status' => STATUS_ACTIVE])->count();

        $studentCourseOrder = $this->adminDashboardCourseOrderHistory();
        $data['courseMonth'] = json_encode($studentCourseOrder['courseMonth']);
        $data['yearlyChartDataCourse'] = json_encode($studentCourseOrder['yearlyChartDataCourse']);


        $studentServiceOrder = $this->adminDashboardServiceOrderHistory();
        $data['serviceMonth'] = json_encode($studentServiceOrder['serviceMonth']);
        $data['yearlyChartDataService'] = json_encode($studentServiceOrder['yearlyChartDataService']);


        $topEventOrder = $this->adminDashboardEventOrder();
        $data['eventOrder'] = json_encode($topEventOrder['eventOrder']);
        $data['eventNames'] = json_encode($topEventOrder['eventName']);

        $topConsultantOrder = $this->adminDashboardConsultantOrder();
        $data['consultantNames'] = json_encode($topConsultantOrder['consultantName']);
        $data['consultantOrder'] = json_encode($topConsultantOrder['consultantOrder']);



        return view('admin.dashboard', $data);
    }

    public function adminDashboardEventOrder()
    {
        $topEvents = Payment::join('events', 'payments.paymentable_id', '=', 'events.id')
            ->select('events.id', 'events.title', DB::raw('COUNT(payments.id) as payment_count'))
            ->where('payments.paymentable_type', Event::class)
            ->where('payment_status', PAYMENT_STATUS_PAID)
            ->groupBy('events.id', 'events.title')
            ->orderBy('payment_count', 'desc')
            ->take(5)
            ->get();

        // Limit titles to 30 characters with '...'
        $eventOrderData['mainData'] = $topEvents;
        $eventOrderData['eventName'] = $topEvents->pluck('title')->map(function ($title) {
            return Str::limit($title, 30, '...');
        })->toArray();
        $eventOrderData['eventOrder'] = $topEvents->pluck('payment_count')->toArray();

        return $eventOrderData;
    }

    public function adminDashboardConsultantOrder()
    {
        $topConsultant = Payment::join('appointments', 'payments.paymentable_id', '=', 'appointments.id')
            ->join('users','appointments.consulter_id','=','users.id')
            ->select('users.id', DB::raw("CONCAT(users.first_name, ' ', users.last_name) as userName"), DB::raw('COUNT(payments.id) as payment_count'))
            ->where('payments.paymentable_type', Appointment::class)
            ->where('payments.payment_status', PAYMENT_STATUS_PAID)
            ->groupBy('users.id')
            ->orderBy('payment_count', 'desc')
            ->take(5)
            ->get();

        $consultantOrderData['mainData'] = $topConsultant;
        $consultantOrderData['consultantName'] = $topConsultant->pluck('userName')->toArray();
        $consultantOrderData['consultantOrder'] =  $topConsultant->pluck('payment_count')->toArray();

        return $consultantOrderData;
    }

    public function adminDashboardServiceOrderHistory()
    {
        $categories = [];
        $data = [];

        $currentMonth = now()->startOfMonth();
        for ($i = 0; $i < 12; $i++) {
            $month_name = $currentMonth->format('M');
            $categories[] = $month_name;
            $data[$month_name] = ['subtotal' => 0];
            $currentMonth->subMonth();
        }

        $categories = array_reverse($categories);

        $totalQuery = StudentServiceOrder::select(
            DB::raw('MONTH(created_at) AS month'),
            DB::raw('SUM(subtotal) AS total_paid')
        )
            ->where('payment_status', PAYMENT_STATUS_PAID)
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        foreach ($totalQuery as $record) {
            $month_name = now()->startOfYear()->addMonths($record->month - 1)->format('M');
            if (isset($data[$month_name])) {
                $data[$month_name]['subtotal'] = $record->total_paid;
            }
        }

        $yearlyChartDataService = [];
        foreach ($categories as $month_name) {
            $yearlyChartDataService[] = $data[$month_name]['subtotal'];
        }

        return [
            'serviceMonth' => $categories,
            'yearlyChartDataService' => $yearlyChartDataService,
        ];
    }

    public function adminDashboardCourseOrderHistory()
    {
        $categories = [];
        $data = [];

        $currentMonth = now()->startOfMonth();
        for ($i = 0; $i < 12; $i++) {
            $month_name = $currentMonth->format('M');
            $categories[] = $month_name;
            $data[$month_name] = ['sub_total' => 0];
            $currentMonth->subMonth();
        }

        $categories = array_reverse($categories);

        $totalQuery = Payment::select(
            DB::raw('MONTH(created_at) AS month'),
            DB::raw('SUM(sub_total) AS total_paid')
        )
            ->where('paymentable_type', Course::class)
            ->where('payment_status', PAYMENT_STATUS_PAID)
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        foreach ($totalQuery as $record) {
            $month_name = now()->startOfYear()->addMonths($record->month - 1)->format('M');
            if (isset($data[$month_name])) {
                $data[$month_name]['sub_total'] = $record->total_paid;
            }
        }

        $yearlyChartDataService = [];
        foreach ($categories as $month_name) {
            $yearlyChartDataService[] = $data[$month_name]['sub_total'];
        }

        return [
            'courseMonth' => $categories,
            'yearlyChartDataCourse' => $yearlyChartDataService,
        ];
    }

    public function contactusList(Request $request)
    {
        if ($request->ajax()) {

            $contactUs = ContactUs::orderByDesc('id');

            return DataTables::of($contactUs)
                ->addIndexColumn()
                ->addColumn('userName', function ($contactUs) {
                    return $contactUs->first_name . ' ' . $contactUs->last_name;
                })
                ->filterColumn('userName', function($query, $keyword) {
                        $query->where('first_name', 'LIKE', "%{$keyword}%")
                            ->orWhere('last_name', 'LIKE', "%{$keyword}%")
                            ->orWhere('email', 'LIKE', "%{$keyword}%")
                            ->orWhere('mobile', 'LIKE', "%{$keyword}%");
                })
                ->make(true);
        }

        $data['pageTitle'] = __('Contact Us List');
        $data['activeContactUs'] = 'active';

        return view('admin.contact-us',$data);

    }
}
