<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use App\Http\Requests\v1\LogRequest;

class LogController extends Controller
{
    /**
     * Display a listing of Activities.
     */
    public function index(LogRequest $request){
        $activities = Activity::latest();
        if ($request->has('causer')) {
            $activities = $activities->causedBy($request->causer);
        }
        if ($request->has('count')) {
            $activities = $activities->limit($request->count);
        }
        $logs = $activities->get();

        return response()->json([
            'data' => $logs,
            'count' => $request->count ?? 10
        ], 200);
    }
}
