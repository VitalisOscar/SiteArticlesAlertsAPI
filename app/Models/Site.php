<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*
|-----------------------------------------------------------
| Site
|-----------------------------------------------------------
|
| A site is a website that may publish one or several posts
|
*/
class Site extends Model
{
    use HasFactory;

    protected $timestamps = true;

    protected $fillable = [
        'name',
        'url',
    ];


    // RELATIONS

    /**
     * The posts for the site
     */
    public function posts(){
        return $this->hasMany(Post::class);
    }

    /**
     * The subscribers that are subscribed to the site
     */
    public function subscribers(){
        return $this->belongsToMany(Subscriber::class, 'subscriptions');
    }

}
