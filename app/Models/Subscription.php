<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    

    protected $fillable = [
        'site_id',
        'subscriber_id',
    ];

    // RELATIONS
    public function site(){
        return $this->belongsTo(Site::class);
    }

    public function subscriber(){
        return $this->belongsTo(Subscriber::class);
    }

}
