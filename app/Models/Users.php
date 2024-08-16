<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Order;
use App\Models\Wishlist;
use App\Models\Cart;

class Users extends Authenticatable
{
    use Notifiable;

    // Fillable fields for mass assignment
    protected $fillable = [
        'name', 'phone', 'email', 'password','role','photo','status','provider','provider_id',
    ];

    // Hidden fields for serialization
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Casting attributes to specific types
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the wishlists for the user.
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the carts for the user.
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Count the total number of users.
     */
    public static function countTotalUsers()
    {
        return self::count();
    }

    /**
     * Count the number of active users.
     */
    public static function countActiveUsers()
    {
        return self::where('status', 'active')->count();
    }

    /**
     * Get the user by email.
     */
    public static function getUserByEmail($email)
    {
        return self::where('email', $email)->first();
    }

    /**
     * Get all users with pagination.
     */
    public static function getAllUsers($perPage = 10)
    {
        return self::orderBy('id', 'desc')->paginate($perPage);
    }
}
