<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Topic extends Model
{
    protected $table = "topics";
    protected $fillable = [
        "subject",
        "creator_id",
        "receiver_id"
    ];

    public function creator()
    {
        return $this->morphTo(Employee::class, ownerKey: 'Employee');
    }
    // public function creator()
    // {
    //     return $this->morphTo(Employee::class, ownerKey: 'User');
    // }
}