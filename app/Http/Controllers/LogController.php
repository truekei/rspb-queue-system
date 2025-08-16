<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Http\Requests\StoreLogRequest;
use App\Http\Requests\UpdateLogRequest;
use App\Models\Queue;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreLogRequest $request)
    {
        $staffId = $request->staff_id;
        $queueId = Queue::where('number', $request->queue_number)->first()->id;
        Log::create([
            'queue_id' => $queueId,
            'staff_id' => $staffId,
            'start_time' => now(),
            'end_time' => null
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Log $log)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Log $log)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($queueNumber)
    {
        $log = Log::where('queue_id', Queue::where('number', $queueNumber)->first()->id)
            ->whereNull('end_time')
            ->first();

        $log->end_time = now();
        $log->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Log $log)
    {
        //
    }
}
