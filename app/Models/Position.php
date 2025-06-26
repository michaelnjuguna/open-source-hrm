<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    //

    protected $fillable = [
        'title',
        'department_id',
        'code',
        'description',
        'salary',
    ];
    protected $table = 'positions';
    protected $with = ['department'];
    protected $casts = [
        'salary' => 'decimal:2',
    ];
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
