<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasUuids;

    protected $table = 'messages';

    protected $fillable = [
        'topic_id',
        'sender_id',
        'content',
        'read_at'
    ];

    protected $casts = [
        'topic_id' => 'integer',
        'sender_id' => 'integer',
        'content' => 'string',
        'read_at' => 'datetime',
    ];

    protected $with = ['sender'];

    public function sender()
    {
        return $this->belongsTo(Employee::class, 'sender_id');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

}