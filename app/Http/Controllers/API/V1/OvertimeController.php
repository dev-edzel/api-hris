<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOvertimeRequest;
use App\Models\Overtime;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OvertimeController extends Controller
{
    public function allRecords()
    {
        $overtimeRecords = Overtime::all();

        return response()->json([
            'message' => 'All overtime records retrieved successfully',
            'overtime' => $overtimeRecords,
        ]);
    }

    public function fileOvertime(StoreOvertimeRequest $request)
    {
        $overtimeData = $request->validated();
        $overtimeData['employee_id'] = Auth::guard('employee')->user()->id;
        $overtimeData['status'] = Status::STATUS_PENDING;

        $overtime = Overtime::create($overtimeData);

        return response()->json([
            'message' => 'Overtime request submitted successfully',
            'overtime' => $overtime,
        ], 201);
    }

    public function updateOvertimeStatus(Request $request, Overtime $overtime)
    {
        $this->authorize('update', $overtime);

        $data = $request->validate([
            'status' => 'required|in:' . implode(',', [
                Status::STATUS_PENDING,
                Status::STATUS_APPROVED,
                Status::STATUS_DECLINED,
            ]),
        ]);

        $overtime->update($data);

        return response()->json([
            'message' => 'Overtime status updated successfully',
            'overtime' => $overtime,
        ]);
    }
}
