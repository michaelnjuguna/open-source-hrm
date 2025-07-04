<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
        'status',
        'order_column',
        'employee_id',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];
    protected $table = 'tasks';
    protected $appends = ['date'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function getDateAttribute()
    {
        return $this->due_date ? $this->due_date->format('d-M-Y') : null;
    }

}
