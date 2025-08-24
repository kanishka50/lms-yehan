<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCourse extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'order_id',
        'subscription_id',
        'expires_at',
        'purchased_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'purchased_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    // Check if access is valid
    public function isValid()
    {
        // Purchased items (no subscription_id) are always valid
        if (is_null($this->subscription_id)) {
            return true;
        }
        
        // Subscription items check expiry
        return $this->expires_at === null || $this->expires_at->isFuture();
    }

    // Scope for valid access
    public function scopeValid($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('subscription_id') // Purchased items
              ->orWhere(function ($sub) {
                  $sub->whereNotNull('subscription_id') // Subscription items
                      ->where(function ($exp) {
                          $exp->whereNull('expires_at')
                              ->orWhere('expires_at', '>', now());
                      });
              });
        });
    }

    // Scope for expired access
    public function scopeExpired($query)
    {
        return $query->whereNotNull('subscription_id')
                     ->where('expires_at', '<=', now());
    }
}