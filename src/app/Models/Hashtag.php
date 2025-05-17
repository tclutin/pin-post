<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    public function images()
    {
        return $this->belongsToMany(Image::class, 'image_hashtags');
    }
}
