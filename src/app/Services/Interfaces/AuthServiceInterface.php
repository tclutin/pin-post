<?php

namespace App\Services\Interfaces;

interface AuthServiceInterface
{
    public function register(string $name, string $email, string $password): array;
    public function login(string $email, string $password): array;
}
