<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'type',
        'pdf_file_path',
        'file_size',
        'page_count',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'file_size' => 'integer',
        'page_count' => 'integer',
    ];

    /**
     * Get users who have access to this product.
     */
    public function userAccess()
    {
        return $this->hasMany(UserDigitalProductAccess::class);
    }

    /**
     * Get the users who have access through the pivot table.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_digital_product_access')
                    ->withPivot('order_id', 'granted_at');
    }

    /**
     * Check if a specific user has access to this product.
     */
    public function hasUserAccess($userId)
    {
        return $this->userAccess()->where('user_id', $userId)->exists();
    }

    /**
     * Get the commission rate for this product.
     */
    public function commissionRate()
    {
        return $this->hasOne(CommissionRate::class, 'item_id')
            ->where('item_type', 'digital_product');
    }

    /**
     * Get the current active commission rate percentage.
     */
    public function getActiveCommissionRateAttribute()
    {
        $rate = $this->commissionRate()->where('is_active', true)->first();
        return $rate ? $rate->rate : null;
    }

    /**
     * Check if this product has an active commission rate.
     */
    public function hasActiveCommissionRate()
    {
        return $this->commissionRate()->where('is_active', true)->exists();
    }

    /**
     * Get the referral links for this product.
     */
    public function referralLinks()
    {
        return $this->hasMany(ReferralTracking::class, 'item_id')
            ->where('item_type', 'digital_product');
    }

    /**
     * Get formatted file size.
     */
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return $bytes . ' byte';
        } else {
            return '0 bytes';
        }
    }

    /**
     * Check if product is a PDF.
     */
    public function isPdf()
    {
        return $this->type === 'pdf';
    }

    /**
     * Check if the product is in stock.
     * For PDFs, always return true since they're digital files.
     */
    public function isInStock()
    {
        // PDFs are always "in stock" since they're digital files
        if ($this->type === 'pdf') {
            return true;
        }
        
        // For legacy product types (if any remain)
        return true;
    }
}