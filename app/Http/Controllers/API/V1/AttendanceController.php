<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function clockIn(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $employeeWithDepartment = $employee->load('department');

        $existingAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('clock_in', Carbon::today())
            ->whereNull('clock_out')
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'error' => 'Already In!',
            ], 400);
        }

        $attendance = new Attendance([
            'employee_id' => $employee->id,
            'clock_in' => now()->format('Y-m-d H:i:s'),
        ]);

        $attendance->save();

        return response()->json([
            'message' => 'Clocked in successfully',
            'attendance' => $attendance,
            'employee' => [
                'name' => $employeeWithDepartment->name,
                'department' => $employeeWithDepartment->department->name,
            ],
        ], 201);
    }

    public function clockOut(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $employeeWithDepartment = $employee->load('department');
        $attendance = $employee->attendance()->latest()->first();

        if ($attendance && !$attendance->clock_out) {
            $attendance->clock_out = now()->format('Y-m-d H:i:s');
            $attendance->save();

            return response()->json([
                'message' => 'Clocked out successfully',
                'attendance' => $attendance,
                'employee' => [
                    'name' => $employeeWithDepartment->name,
                    'department' => $employeeWithDepartment->department->name,
                ],
            ]);
        }

        return response()->json([
            'error' => 'No clock in record found'
        ], 404);
    }
}
