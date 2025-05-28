<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\AdminServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller 
{
    private $adminService;

    public function __construct(AdminServiceInterface $adminService)
    {
        $this->adminService = $adminService;
    }

    public function assignRole(Request $request, $userId)
    {
        $request->validate([
            'role_id' => 'required|integer|exists:roles,id'
        ]);

        $result = $this->adminService->assignRole($userId, $request->role_id);
        return $this->jsonResponse($result);
    }

    public function getUsers(Request $request)
    {
        $users = $this->adminService->getUsers();
        return response()->json($users);
    }

    public function ban(Request $request, $userId)
    {
        $result = $this->adminService->banUser($userId, Auth::id());
        return $this->jsonResponse($result);
    }

    public function unban(Request $request, $userId)
    {
        $result = $this->adminService->unbanUser($userId);
        return $this->jsonResponse($result);
    }

    public function banImage(Request $request, $imageId) 
    {
        $result = $this->adminService->banImage($imageId, Auth::id());
        return $this->jsonResponse($result);
    }

    public function banComment(Request $request, $commentId) 
    {
        $result = $this->adminService->banComment($commentId);
        return $this->jsonResponse($result);
    }

    public function getUserStats(Request $request)
    {
        $stats = $this->adminService->getUserStats();
        return response()->json($stats);
    }

    public function getLastWeekStats(Request $request)
    {
        $stats = $this->adminService->getLastWeekStats();
        return response()->json($stats);
    }

    public function getRegistrationPlot(Request $request) 
    {
        $period = $request->input('period', 'week');
        $result = $this->adminService->getRegistrationPlot($period);
        return response()->json($result);
    }

    private function jsonResponse(array $result)
    {
        $status = $result['status'] ?? 200;
        $message = $result['message'] ?? '';
        $data = array_diff_key($result, array_flip(['status', 'message']));
        
        return response()->json(
            !empty($data) ? $data : ['message' => $message],
            $status
        );
    }
}