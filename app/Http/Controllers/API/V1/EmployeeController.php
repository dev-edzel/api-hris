<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterEmployeeRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Overtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class EmployeeController extends Controller
{
    public function register(RegisterEmployeeRequest $request)
    {
        $employeeData = $request->validated();

        $employeeData = $request->except(['password_confirmation']);

        $department = Department::findOrFail($employeeData['department_id']);

        $employeeData['password'] = Hash::make($request->input('password'));

        $employee = Employee::create($employeeData);

        $token = JWTAuth::fromUser($employee);

        return response()->json([
            'message' => 'Employee registered successfully',
            'employee' => [
                'name' => $employee->name,
                'email' => $employee->email,
                'department_name' => $department->name,
            ],
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::guard('employee')->attempt($credentials)) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        $employee = Auth::guard('employee')->user();

        if ($employee) {
            return response()->json([
                'message' => 'Login successfully',
                'name' => $employee->name,
                'email' => $employee->email,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
            ]);
        }
        return response()->json([
            'error' => 'User not found'
        ], 404);
    }

    public function logout()
    {
        Auth::guard('employee')->logout();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function refresh()
    {
        $newToken = JWTAuth::parseToken()->refresh();

        return response()->json([
            'access_token' => $newToken,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
    }

    public function profile()
    {
        return response()->json(Auth::guard('employee')->user());
    }

    // public function changeOvertimeStatus(Request $request)
    // {
    //     $employee = Auth::guard('employee')->user();

    //     if ($employee->department_id->id === 3) {
    //         $overtimeId = $request->input('overtime_id');
    //         $overtime = Overtime::find($overtimeId);

    //         if ($overtime) {
    //             $status = $request->input('status');

    //             if ($status->id === 1) {
    //                 $overtime->status = 'approved';

    //             } elseif ($status->id === 2) {
    //                 $overtime->status = 'declined';

    //             } else {
    //                 return response()->json([
    //                     'error' => 'Invalid status provided'
    //                 ], 400);
    //             }

    //             $overtime->save();

    //             return response()->json([
    //                 'message' => 'Overtime status updated successfully'
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'error' => 'Overtime not found'
    //             ], 404);
    //         }
    //     } else {
    //         return response()->json([
    //             'error' => 'Unauthorized'
    //         ], 401);
    //     }
    // }

    public function updateOvertimeStatus(Request $request, Overtime $overtime)
    {
        $this->authorize('update', $overtime);

        $data = $request->validate([
            'status' => 'required|in:' . implode(',', [
                Overtime::STATUS_PENDING,
                Overtime::STATUS_APPROVED,
                Overtime::STATUS_DECLINED,
            ]),
        ]);

        $overtime->update($data);

        return response()->json([
            'message' => 'Overtime status updated successfully',
            'overtime' => $overtime,
        ]);
    }
}
