<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('device')
            ->select('events.*', 'devices.device_name')
            ->join('devices', 'events.device_id', '=', 'devices.id');

        // Search functionality
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('devices.device_name', 'like', "%{$searchTerm}%")
                    ->orWhere('events.event_type', 'like', "%{$searchTerm}%")
                    ->orWhere('events.method_type', 'like', "%{$searchTerm}%");
            });
        }

        // Date range filter
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('events.timestamp', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Event type filter
        if ($request->has('event_type')) {
            $query->where('events.event_type', $request->event_type);
        }

        $events = $query->orderBy('events.timestamp', 'desc')
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'events' => view('events.table', compact('events'))->render(),
                'pagination' => view('pagination', ['paginator' => $events])->render()
            ]);
        }

        // Get unique event types for filter dropdown
        $eventTypes = Event::select('event_type')
            ->distinct()
            ->pluck('event_type');

        return view('events.manage', compact('events', 'eventTypes'));
    }

    public function show($id)
    {
        $event = Event::with('device')->findOrFail($id);
        return response()->json($event);
    }

    public function export(Request $request)
    {
        $query = Event::with('device')
            ->select('events.*', 'devices.device_name')
            ->join('devices', 'events.device_id', '=', 'devices.id');

        // Apply filters
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('events.timestamp', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        if ($request->has('event_type')) {
            $query->where('events.event_type', $request->event_type);
        }

        $events = $query->orderBy('events.timestamp', 'desc')->get();

        // Generate CSV
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="events_report.csv"',
        ];

        $callback = function() use ($events) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for proper Arabic character encoding
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, ['اسم الجهاز', 'نوع الحدث', 'نوع الطريقة', 'التاريخ والوقت']);
            
            // Data
            foreach ($events as $event) {
                fputcsv($file, [
                    $event->device->device_name,
                    $event->event_type,
                    $event->method_type,
                    $event->timestamp
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
