<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\Overtime;

class OvertimePolicy
{
    public function update(Employee $employee, Overtime $overtime)
    {
        return $employee->id === $overtime->employee_id;
    }
}
