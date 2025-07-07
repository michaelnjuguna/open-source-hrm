<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Employee extends Model
{
    //
    protected $fillable = [
        'employee_number',
        'first_name',
        'last_name',
        'national_id',
        'kra_pin',
        'email',
        'phone',
        'emergency_contact_name',
        'emergency_contact_phone',
        'date_of_birth',
        'gender',
        'marital_status',
        'employment_type',
        'hire_date',
        'termination_date',
        'is_active',
        'department_id',
        'position_id',
        'next_of_kin_name',
        'next_of_kin_relationship',
        'next_of_kin_phone',
        'next_of_kin_email',
        'password'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'termination_date' => 'date',
        'is_active' => 'boolean',
        'department_id' => 'integer',
    ];
    protected $appends = [
        'full_name',
    ];

    public static function booted()
    {
        static::creating(function ($employee) {
            $employee->password = bcrypt($employee->email);
        });
    }
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }
}
