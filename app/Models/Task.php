<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Task extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
        'status',
        'sort_order',
        'assignee_id',
        'assignee_type',
        'due_date',
        'position'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];
    protected $table = 'tasks';
    protected $appends = ['date', 'email'];

    public function assignee()
    {
        return $this->morphTo();
    }


    public function getDateAttribute()
    {
        return $this->due_date?->format('d-M-Y');
    }
    public function getEmailAttribute()
    {

        // if ($this->assignee_type == Employee::class) {
        //     $employee = Employee::find($this->assignee_id);
        //     return $employee->email;
        // } elseif ($this->assignee_type == User::class) {
        //     $user = User::find($this->assignee_id);
        //     return $user->email;
        // }
        return $this->assignee?->email;
    }

}
