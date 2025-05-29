<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\CommentServiceInterface;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentServiceInterface $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(Request $request, $imageId)
    {
        return $this->commentService->store($request, $imageId);
    }

    public function destroy($id)
    {
        return $this->commentService->destroy($id);
    }
}