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
    protected $appends = ['date'];

    public function assignee()
    {
        return $this->morphTo();
    }


    public function getDateAttribute()
    {
        return $this->due_date ? $this->due_date->format('d-M-Y') : null;
    }

}
