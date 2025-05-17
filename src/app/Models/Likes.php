<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Likes extends Pivot
{
    protected $table = 'likes';
    public $timestamps = false;
     public $incrementing = false; 
    protected $primaryKey = null; 

    protected $fillable = [
        'user_id',
        'image_id',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
