<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    //
    protected $table = 'leave';
    protected $fillable = [
        'employee_id',
        'actioned_by',
        'leave_type',
        'start_date',
        'end_date',
        'status',
        'rejection_reason',
        'notes'
    ];
    protected $casts = [
        'leave_date' => 'datetime:H:i',
        'start_date' => 'date',
        'end_date' => 'date',
    ];
    protected $appends = [
        'duration',

    ];

    public function getDurationAttribute()
    {
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);
        return $start->diffInDays($end);
    }


    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
