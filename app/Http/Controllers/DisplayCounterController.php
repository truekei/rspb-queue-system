<?php

namespace App\Http\Controllers;

use App\Events\CallQueueEvent;
use App\Models\Staff;
use Illuminate\Http\Request;

class DisplayCounterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $counter = $request->counter;
        $staffName = Staff::find($request->staff_id)->name;
        $queue = $request->queue;
        $announce = $request->announce;

        broadcast(new CallQueueEvent($counter, $staffName, $queue, $announce));

    }
}
