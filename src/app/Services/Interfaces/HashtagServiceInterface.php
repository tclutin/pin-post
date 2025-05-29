<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;

interface HashtagServiceInterface
{
    public function index();
    public function create(Request $request);
    public function attachToImage(Request $request, $imageId);
    public function detachFromImage(Request $request, $imageId);
}
