<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    

    protected $fillable = [
        'email',
    ];


    // RELATIONS

    /**
     * The sites that the subscriber is subscribed to
     */
    public function sites(){
        return $this->belongsToMany(Site::class, 'subscriptions');
    }
}
