<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return response()->json($events);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                "device_id"   =>  'required|exists:devices,id|integer',
                "timestamp"   =>  'required|date',
                "event_type"  =>  'required|string|max:255',
                "method_type" =>  'required|string|max:255'
            ]);

            $event = Event::create([
                'device_id'   => $validatedData['device_id'],
                'timestamp'   => $validatedData['timestamp'],
                'event_type'  => $validatedData['event_type'],
                'method_type' => $validatedData['method_type'],
            ]);

            return response()->json([
                'message' => 'Event created successfully',
                'event'   => $event
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $query = Event::query();

        // تحقق من أن `id` تم إرساله لتصفية الأحداث بناءً عليه
        if (!$id) {
            return response()->json([
                'message' => 'Device ID is required'
            ], 400); // قم بإرجاع خطأ إذا لم يتم إرسال `device_id`
        }

        // استخدم `id` كمعرف للجهاز لتصفية الأحداث
        $query->where('device_id', $id);

        // تحقق من وجود `timestamp` لتصفية الأحداث بناءً عليه
        if ($request->has('timestamp')) {
            $timestamp = $request->input('timestamp');
            $query->whereDate('timestamp', $timestamp); // تصفية بناءً على تاريخ `timestamp`
        }

        // يمكن تطبيق فلاتر إضافية بناءً على المدخلات من الطلب
        if ($request->has('event_type')) {
            $query->where('event_type', $request->input('event_type'));
        }

        if ($request->has('method_type')) {
            $query->where('method_type', $request->input('method_type'));
        }

        // الفلترة بناءً على مدى التاريخ إذا كانت هناك نطاق تاريخ معين
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('timestamp', [
                $request->input('start_date'),
                $request->input('end_date')
            ]);
        }

        $events = $query->get();

        // تحقق مما إذا تم العثور على أي أحداث
        if ($events->isEmpty()) {
            return response()->json([
                "message" => "No events found for the given criteria"
            ], 404);
        }

        return response()->json([
            'message'   => 'Events retrieved successfully!',
            'events'    => $events,
            'device_id' => $id  // إرسال `device_id` مع الرد
        ], 200);
    }


    public function update()
    {
        // Implement the update logic
    }

    public function destroy()
    {
        // Implement the destroy logic
    }
}
