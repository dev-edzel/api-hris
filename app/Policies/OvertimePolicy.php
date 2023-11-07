<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\Overtime;
use App\Models\User;

class OvertimePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(Employee $user, Overtime $overtime)
    {
        return $user->department_id->id === 'required_department';
    }
}
