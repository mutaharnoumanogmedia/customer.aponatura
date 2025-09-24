<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClickLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_name',
        'user_email',
        'user_ip',
        'user_agent',
        'additional_info',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;

}
