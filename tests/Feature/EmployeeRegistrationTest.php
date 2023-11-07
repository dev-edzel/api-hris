<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Response;

class EmployeeRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_registers_a_new_employee()
    {
        $department = Department::factory()->create();

        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secretPassword',
            'department_id' => $department->id,
            'password_confirmation' => 'secretPassword',
        ];

        $response = $this->json('POST', '/api/register', $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'message',
                'employee' => [
                    'name',
                    'email',
                    'department_name'
                ],
                'token'
            ])
            ->assertJson([
                'message' => 'Employee registered successfully',
                'employee' => [
                    'name' => 'John Doe',
                    'email' => 'johndoe@example.com',
                    'department_name' => $department->name,
                ]
            ]);

        $this->assertNotNull(Employee::where('email', 'johndoe@example.com')->first());

        // You can add more assertions based on your specific needs.
    }
}
