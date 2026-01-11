<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $table = 'messages';

    protected $fillable = [
        'topic_id',
        'sender_id',
        'content',
        'read_at',
        'receiver_id'
    ];
    protected $casts = [
        'topic_id' => 'integer',
        'sender_id' => 'integer',
        'content' => 'string',
        'read_at' => 'datetime',
        'receiver_id' => 'integer'
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

    public function receiver()
    {
        return $this->belongsTo(Employee::class, 'receiver_id');
    }

}