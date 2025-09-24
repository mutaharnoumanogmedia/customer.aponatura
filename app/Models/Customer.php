<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;

class Customer implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable;

    public $id, $name, $email, $first_name, $last_name, $phone, $avatar, $address, $postal_code, $city, $country;

    public function __construct(array $attributes)
    {
        $this->id = $attributes['id'];
        $this->name = $attributes['name'];
        $this->email = $attributes['email'];
        $this->first_name = $attributes['first_name'] ?? "";
        $this->last_name = $attributes['last_name'] ?? "";
        $this->phone = $attributes['phone'] ?? null;
        $this->avatar = $attributes['avatar'] ?? null;
        $this->address = $attributes['address'] ?? null;
        $this->postal_code = $attributes['postal_code'] ?? null;
        $this->city = $attributes['city'] ?? null;
        $this->country = $attributes['country'] ?? null;
    }
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }

    public function getAuthPassword()
    {
        return ''; // Not needed, since login was done via external API
    }

    public function getRememberToken() {}
    public function setRememberToken($value) {}
    public function getRememberTokenName() {}
}
