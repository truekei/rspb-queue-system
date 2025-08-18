<?php

namespace App\Http\Controllers;

use App\Events\LoadDisplayEvent;
use App\Events\LoadDisplayCountersEvent;
use App\Models\Staff;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    public function displayLoad()
    {
        broadcast(new LoadDisplayEvent());
        return response()->json(['status' => 'success']);
    }

    public function displayLoadCounters(Request $request)
    {
        $counters = [
            [ 'selectedStaff' => $request->input('counters.0.selectedStaff') ? Staff::findOrFail($request->input('counters.0.selectedStaff'))->name : null,
            'currentQueue' => $request->input('counters.0.currentQueue')],
            [ 'selectedStaff' => $request->input('counters.1.selectedStaff') ? Staff::findOrFail($request->input('counters.1.selectedStaff'))->name : null,
            'currentQueue' => $request->input('counters.1.currentQueue')],
            [ 'selectedStaff' => $request->input('counters.2.selectedStaff') ? Staff::findOrFail($request->input('counters.2.selectedStaff'))->name : null,
            'currentQueue' => $request->input('counters.2.currentQueue')],
        ];

        broadcast(new LoadDisplayCountersEvent($counters));
        return response()->json(['status' => 'success']);
    }
}
