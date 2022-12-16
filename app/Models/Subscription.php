<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*
|-----------------------------------------------------------
| Subscription
|-----------------------------------------------------------
|
| A subscription is a record of an individual subscriber
| being subscribed to a single site
|
*/
class Subscription extends Model
{
    use HasFactory;

    // Subscription status
    const STATUS_ACTIVE = 'Active';
    const STATUS_CANCELLED = 'Cancelled';

    public $timestamps = true;

    protected $fillable = [
        'site_id',
        'subscriber_id',
        'status'
    ];

    // RELATIONS
    public function site(){
        return $this->belongsTo(Site::class);
    }

    public function subscriber(){
        return $this->belongsTo(Subscriber::class);
    }


    // SCOPES

    /**
     * Scope a query to only include active subscriptions
     */
    public function scopeActive($query){
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope a query to only include cancelled subscriptions
     */
    public function scopeCancelled($query){
        return $query->where('status', self::STATUS_CANCELLED);
    }

}
