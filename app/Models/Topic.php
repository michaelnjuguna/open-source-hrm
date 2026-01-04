<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Topic extends Model
{
    protected $table = "topics";
    protected $fillable = [
        "subject",
        'creator_id',
        'receiver_id',
    ];
    protected $casts = [
        'subject' => 'string',
        'creator_id' => 'integer',
        'receiver_id' => 'integer',
    ];


    public function message()
    {
        return $this->hasMany(Message::class);
    }

    public function creator()
    {
        return $this->belongsTo(Employee::class, 'creator_id');
    }
}