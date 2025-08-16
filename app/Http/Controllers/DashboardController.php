<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activeQueuesR = Queue::where('status', 'waiting')
            ->where('type', 'R')
            ->count();
        $activeQueuesW = Queue::where('status', 'waiting')
            ->where('type', 'W')
            ->count();
        $activeStaff = DB::table('staff')
            ->join('logs', 'staff.id', '=', 'logs.staff_id')
            ->where('staff.active', true)
            ->whereNotNull('logs.end_time')
            ->select('staff.name', DB::raw('COUNT(logs.id) as total_served'), DB::raw('AVG(TIMEDIFF(logs.end_time, logs.start_time)) AS time_average'))
            ->groupBy('staff.name')
            ->orderBy('total_served', 'desc')
            ->get();
        $topStaff = DB::table('logs')
            ->join('staff', 'logs.staff_id', '=', 'staff.id')
            ->select('staff.name', DB::raw('COUNT(logs.id) as total_served'))
            ->whereNotNull('logs.end_time')
            ->groupBy('staff.name')
            ->orderBy('total_served', 'desc')
            ->take(3)
            ->get();

        return response()->json([
            'data' => [
                'activeQueues' => [
                    'R' => $activeQueuesR,
                    'W' => $activeQueuesW
                ],
                'activeStaff' => $activeStaff,
                'topStaff' => $topStaff,
            ]
        ]);
    }
}
