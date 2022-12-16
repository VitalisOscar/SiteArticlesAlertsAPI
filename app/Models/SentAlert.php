<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*
|-----------------------------------------------------------
| SentAlert
|-----------------------------------------------------------
|
| A sent alert is a record of a post that was sent to a
| subscriber's inbox
|
*/
class SentAlert extends Model
{
    use HasFactory;

    protected $timestamps = true;

    protected $fillable = [
        'subscription_id', // Subscription that made the post be sent
        'post_id', // Post that was sent
    ];

}
