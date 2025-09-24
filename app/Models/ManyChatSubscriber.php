<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManyChatSubscriber extends Model
{
    use HasFactory;
    protected $fillable = [
        'subscriber_id',
        'first_name',
        'last_name',
        'phone',
        'tag',
        'subscribed_at'
    ];
}
