<?php

namespace App\Models;

use App\Filament\Pages\TaskBoard;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Task extends Model
{
    use HasUuids;

    protected $fillable = [
        'title',
        'description',
        'status',
        'sort_order',
        'assignee_id',

        'due_date',
        'position'
    ];

    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'status' => 'string',
        'sort_order' => 'integer',
        'assignee_id' => 'integer',
        'due_date' => 'datetime',
        'position' => 'integer',
    ];

    protected $table = 'tasks';

    protected $appends = ['date', 'email'];

    public function assignee()
    {
        return $this->belongsTo(Employee::class, 'assignee_id');
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
