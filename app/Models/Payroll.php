<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $table = 'payrolls';
    protected $fillable = [
        'employee_id',
        'pay_date',
        'period',
        'gross_pay',
        'net_pay',
        'deductions',
        'allowances',
        'bonuses',
        'notes',
        'status'
    ];
    protected $casts = [
        'deductions' => 'array',
        'allowances' => 'array',
        'bonuses' => 'array',
    ];

    protected $with = [
        'employee',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }


}
