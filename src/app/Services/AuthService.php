<?php
namespace App\Services;

use App\Models\User;
use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface {

    public function register(string $name, string $email, string $password): array
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'role_id' => 1
        ]);

        return [
            'user' => $user,
            'token' => $user->createToken('main')->plainTextToken,
        ];
    }

    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return ['message' => 'user not found'];
        }

        return [
            'user' => $user,
            'token' => $user->createToken('main')->plainTextToken
        ];
    }
}
