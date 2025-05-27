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
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'termination_date' => 'date',
        'is_active' => 'boolean',
    ];
    protected $appends = [
        'full_name',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
