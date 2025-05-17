<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ImageHashtag extends Pivot
{
     public $timestamps = false;

    protected $fillable = ['image_id', 'hashtag_id'];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function hashtag()
    {
        return $this->belongsTo(Hashtag::class);
    }
}
