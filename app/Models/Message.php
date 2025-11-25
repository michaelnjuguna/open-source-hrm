<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $table = 'messages';

    protected $fillable = [
        'topic_id',
        'sender_id',
        "sender_type",
        'content',
        'read_at'
    ];
    protected $with = ['sender'];
    public function sender()
    {
        return $this->morphTo();
    }


    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

}