<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Event;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // تحديد نطاق التاريخ
        $startDate = null;
        $endDate = null;

        switch($request->date_filter) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'custom':
                $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::today();
                $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::today()->endOfDay();
                break;
            default:
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
        }

        // إحصائيات أساسية
        $stats = [
            'total_devices' => Device::whereDate('created_at', '<=', $endDate)->count(),
            'active_devices' => Device::where('status', true)
                ->whereDate('created_at', '<=', $endDate)
                ->count(),
            'total_users' => \App\Models\User::whereDate('created_at', '<=', $endDate)->count(),
            'today_events' => Event::whereBetween('timestamp', [$startDate, $endDate])->count(),
        ];

        // توزيع حالة الأجهزة
        $deviceStatus = [
            'active' => Device::where('status', true)
                ->whereDate('created_at', '<=', $endDate)
                ->count(),
            'inactive' => Device::where('status', false)
                ->whereDate('created_at', '<=', $endDate)
                ->count(),
        ];

        // أحداث الفترة المحددة
        $periodEvents = Event::select(DB::raw('DATE(timestamp) as date'), DB::raw('count(*) as count'))
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // أنواع الأحداث
        $eventTypes = Event::select('event_type', DB::raw('count(*) as count'))
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->groupBy('event_type')
            ->get()
            ->map(function ($event) {
                $arabicLabels = [
                    'open' => 'فتح الباب',
                    'close' => 'إغلاق الباب',
                    'tamper' => 'محاولة عبث',
                    'battery_low' => 'بطارية منخفضة',
                    'internet_disconnected' => 'انقطاع الإنترنت',
                    'door_left_open' => 'الباب مفتوح',
                    'lock_status' => 'حالة القفل'
                ];
                return [
                    'type' => $arabicLabels[$event->event_type] ?? $event->event_type,
                    'count' => $event->count
                ];
            })
            ->pluck('count', 'type')
            ->toArray();

        // أنواع الإشعارات
        $notificationTypes = Notification::select('type', DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('type')
            ->get()
            ->map(function ($notification) {
                $arabicLabels = [
                    'door_open' => 'فتح الباب',
                    'door_left_open' => 'الباب مفتوح',
                    'lock_status' => 'حالة القفل',
                    'battery' => 'البطارية',
                    'internet' => 'الإنترنت',
                    'tamper' => 'محاولة عبث'
                ];
                return [
                    'type' => $arabicLabels[$notification->type] ?? $notification->type,
                    'count' => $notification->count
                ];
            })
            ->pluck('count', 'type')
            ->toArray();

        // نشاط الأجهزة خلال اليوم
        $hourlyActivity = Event::select(DB::raw('HOUR(timestamp) as hour'), DB::raw('count(*) as count'))
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->pluck('count', 'hour')
            ->toArray();

        // آخر الأحداث
        $latestEvents = Event::with('device')
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->orderBy('timestamp', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'stats',
            'deviceStatus',
            'periodEvents',
            'eventTypes',
            'notificationTypes',
            'hourlyActivity',
            'latestEvents',
            'startDate',
            'endDate'
        ));
    }
}
