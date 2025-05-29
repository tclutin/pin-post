<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;

interface ImageServiceInterface
{
    public function index(Request $request);
    public function show($id);
    public function store(Request $request);
    public function update(Request $request, $id);
    public function destroy($id);
    public function imagesByHashtag($hashtagId);
    public function imagesByCategory($categoryId);
    public function imagesByUser($userId);
}
