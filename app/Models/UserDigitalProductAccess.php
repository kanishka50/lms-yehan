<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDigitalProductAccess extends Model
{
    use HasFactory;

    protected $table = 'user_digital_product_access';
    
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'digital_product_id',
        'order_id',
        'granted_at',
    ];

    protected $casts = [
        'granted_at' => 'datetime',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['granted_at'];

    /**
     * Get the user who has access.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the digital product.
     */
    public function digitalProduct()
    {
        return $this->belongsTo(DigitalProduct::class);
    }

    /**
     * Get the order that granted this access.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}