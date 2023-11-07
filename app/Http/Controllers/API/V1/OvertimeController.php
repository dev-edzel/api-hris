<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOvertimeRequest;
use App\Models\Overtime;
use Illuminate\Http\Request;

class OvertimeController extends Controller
{
    public function store(StoreOvertimeRequest $request)
    {
        $overtime = $request->validated();

        $overtime = Overtime::create($request->all());

        return response()->json([
            'message' => 'Overtime created successfully',
            'overtime' => $overtime
        ], 201);
    }
}
