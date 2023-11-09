<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeaveRequest;
use App\Models\Leave;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function allRecords()
    {
        $leaveRecords = Leave::all();

        return response()->json([
            'message' => 'All leave records retrieved successfully',
            'overtime' => $leaveRecords,
        ]);
    }

    public function fileLeave(StoreLeaveRequest $request)
    {
        $leaveData = $request->validated();
        $leaveData['employee_id'] = Auth::guard('employee')->user()->id;
        $leaveData['status'] = Status::STATUS_PENDING;

        $leave = Leave::create($leaveData);

        return response()->json([
            'message' => 'Leave request submitted successfully',
            'leave' => $leave,
        ], 201);
    }
}
