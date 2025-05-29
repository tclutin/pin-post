<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;

interface CommentServiceInterface
{
    public function store(Request $request, $imageId);
    public function destroy($id);
}