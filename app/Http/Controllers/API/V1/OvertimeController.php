<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOvertimeRequest;
use App\Models\Overtime;
use Illuminate\Support\Facades\Auth;

class OvertimeController extends Controller
{
    public function store(StoreOvertimeRequest $request)
    {
        $overtimeData = $request->validated();
        $overtimeData['employee_id'] = Auth::guard('employee')->user()->id;

        $overtime = Overtime::create($overtimeData);

        return response()->json([
            'message' => 'Overtime request submitted successfully',
            'overtime' => $overtime,
        ], 201);
    }
}
