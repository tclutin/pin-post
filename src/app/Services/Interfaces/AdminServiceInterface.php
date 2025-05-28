<?php
namespace App\Services\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface AdminServiceInterface
{
    public function assignRole(int $userId, int $roleId): array;
    public function getUsers(): array;
    public function banUser(int $userId, int $loggedUserId): array;
    public function unbanUser(int $userId): array;
    public function banImage(int $imageId, int $deletedBy): array;
    public function banComment(int $commentId): array;
    public function getUserStats(): array;
    public function getLastWeekStats(): array;
    public function getRegistrationPlot(string $period): array;
}