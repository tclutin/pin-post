<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;

interface AdminServiceInterface
{
    public function assignRole(Request $request, $userId);
    public function getUsers(Request $request);
    public function ban(Request $request, $userId);
    public function unban(Request $request, $userId);
    public function banImage(Request $request, $imageId);
    public function banComment(Request $request, $commentId);
    public function getUserStats(Request $request);
    public function getLastWeekStats(Request $request);
    public function getRegistrationPlot(Request $request);
}