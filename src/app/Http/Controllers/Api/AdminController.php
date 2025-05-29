<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\AdminServiceInterface;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminServiceInterface $adminService)
    {
        $this->adminService = $adminService;
    }

    public function assignRole(Request $request, $userId)
    {
        return $this->adminService->assignRole($request, $userId);
    }

    public function getUsers(Request $request)
    {
        return $this->adminService->getUsers($request);
    }

    public function ban(Request $request, $userId)
    {
        return $this->adminService->ban($request, $userId);
    }

    public function unban(Request $request, $userId)
    {
        return $this->adminService->unban($request, $userId);
    }

    public function banImage(Request $request, $imageId)
    {
        return $this->adminService->banImage($request, $imageId);
    }

    public function banComment(Request $request, $commentId)
    {
        return $this->adminService->banComment($request, $commentId);
    }

    public function getUserStats(Request $request)
    {
        return $this->adminService->getUserStats($request);
    }

    public function getLastWeekStats(Request $request)
    {
        return $this->adminService->getLastWeekStats($request);
    }

    public function getRegistrationPlot(Request $request)
    {
        return $this->adminService->getRegistrationPlot($request);
    }
}