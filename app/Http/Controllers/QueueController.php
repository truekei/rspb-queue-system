<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Http\Requests\StoreQueueRequest;
use App\Http\Requests\UpdateQueueRequest;

class QueueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $queue = Queue::where('status', 'waiting')->get();

        return response()->json([
            'data' => $queue
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQueueRequest $request)
    {
        $request->validate([
            'type' => 'required|in:R,W'
        ]);

        // Hitung nomor terakhir untuk tipe antrian
        $lastQueue = Queue::where('type', $request->type)
            ->orderByDesc('id')
            ->first();

        $nextNumber = 1;
        if ($lastQueue) {
            // Ambil 3 digit terakhir dan +1
            $lastNumber = (int) substr($lastQueue->number, 1);
            $nextNumber = $lastNumber + 1;
        }

        // Format nomor antrian
        $queueNumber = $request->type . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Simpan ke database
        $queue = Queue::create([
            'number' => $queueNumber,
            'type' => $request->type,
            'datetime' => now(),
            'status' => 'waiting',
            'staff_id' => null
        ]);

        return response()->json([
            'success' => true,
            'data' => $queue
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Queue $queue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Queue $queue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function calledUpdateQueue(UpdateQueueRequest $request, $queueNumber)
    {
        $queue = Queue::where('number', $queueNumber)->first();

        $queue->status = 'called';
        $queue->staff_id = $request->staff_id;
        $queue->save();

        return response()->json(['success' => true]);
    }
    public function doneUpdateQueue($queueNumber)
    {
        $queue = Queue::where('number', $queueNumber)->first();

        $queue->status = 'done';
        $queue->save();

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Queue $queue)
    {
        //
    }
}
