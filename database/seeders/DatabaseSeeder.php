<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Department;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            ['name' => 'Software Development'],
            ['name' => 'Customer Support'],
            ['name' => 'Accounting'],
            ['name' => 'Human Resource'],
            ['name' => 'Network and System Administrator'],
            ['name' => 'Admin'],
            ['name' => 'Business Development'],
            ['name' => 'Compliance and Risk'],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
