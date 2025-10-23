<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        return [
            'employee_id' => 1,
            'shift_id' => 1,
            'date' => $this->faker->date(),
            'clock_in' => $this->faker->time('H:i'),
            'clock_out' => $this->faker->time('H:i'),
            'remarks' => $this->faker->sentence(),
        ];
    }
}
