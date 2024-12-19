<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DeviceStatusController extends Controller
{
    public function getDeviceStatus(Request $request)
    {
        $query = Device::query();
        $period = $request->get('period', 'day');

        switch ($period) {
            case 'day':
                $query->whereDate('last_active', Carbon::today());
                break;

            case 'week':
                $query->whereBetween('last_active', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;

            case 'month':
                $query->whereMonth('last_active', Carbon::now()->month)
                    ->whereYear('last_active', Carbon::now()->year);
                break;

            case 'custom':
                $startDate = $request->get('start_date');
                $endDate = $request->get('end_date');
                if ($startDate && $endDate) {
                    $query->whereBetween('last_active', [
                        Carbon::parse($startDate)->startOfDay(),
                        Carbon::parse($endDate)->endOfDay()
                    ]);
                }
                break;
        }

        $active = $query->where('status', 'active')->count();
        $inactive = $query->where('status', 'inactive')->count();

        return response()->json([
            'active' => $active,
            'inactive' => $inactive
        ]);
    }
}
