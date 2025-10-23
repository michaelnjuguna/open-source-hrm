<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;
use App\Models\Shift;

class Attendance extends Model
{
    //
    use HasFactory;
    protected $table = 'attendances';
    protected $with = ['shift', 'employee'];

    protected $fillable = [
        'employee_id',
        'date',
        'clock_in',
        'clock_out',

        'shift_id',
        'remarks',
    ];
    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime:H:i',
        'clock_out' => 'datetime:H:i',
        'shift_id' => 'integer',

    ];
    protected $appends = [
        'shift_name',
        'hours'
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }
    public function getShiftNameAttribute()
    {
        return $this->shift ? $this->shift->name : null;
    }
    public function getHoursAttribute()
    {
        if ($this->clock_in && $this->clock_out) {
            $start = Carbon::parse($this->clock_in);
            $end = Carbon::parse($this->clock_out);
            return $start->diffInHours($end, false) + ($start->diffInMinutes($end, false) % 60) / 60;
        }
        return null;
    }
}
