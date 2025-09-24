<?php

namespace App\Models;

use App\Services\CustomerService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MagicLink extends Model
{
    use HasFactory;

    protected $fillable = ['token', 'user_id', 'email', 'expires_at', 'used'];

    protected $dates = ['expires_at'];



    public function isValid()
    {
        return $this->expires_at > date("Y-m-d H:i:s");
    }
}
