<?php

namespace App\Models;

use App\Filament\Pages\TaskBoard;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
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
        'due_date' => 'datetime',
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
        return $this->assignee?->email;
    }

}
