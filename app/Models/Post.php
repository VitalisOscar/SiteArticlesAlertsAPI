<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*
|-----------------------------------------------------------
| Post
|-----------------------------------------------------------
|
| A post is a single article for a particular site
|
*/
class Post extends Model
{
    use HasFactory;

    protected $timestamps = true;

    protected $fillable = [
        'title',
        'description',
    ];


    // RELATIONS

    /**
     * The site that the post belongs to
     */
    public function site(){
        return $this->belongsTo(Site::class);
    }

    /**
     * The subscribers that the post was sent to
     */
    public function subscribers(){
        return $this->belongsToMany(Subscriber::class, 'sent_alerts');
    }
}
