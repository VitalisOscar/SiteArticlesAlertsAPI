<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*
|-----------------------------------------------------------
| Subscriber
|-----------------------------------------------------------
|
| A subscriber is a user that has signed up to receive
| email alerts from one or several sites
|
*/
class Subscriber extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'email',
    ];


    // RELATIONS

    /**
     * The sites that the subscriber is subscribed to
     */
    public function sites(){
        return $this->belongsToMany(Site::class, 'subscriptions')->withPivot('status');
    }

    /**
     * The posts that the subscriber has been sent
     */
    public function received_posts(){
        return $this->belongsToMany(Post::class, 'sent_alerts');
    }

}
