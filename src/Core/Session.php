<?php

class Session
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function destroy(): void
    {
        session_destroy();
    }

    public function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public function generateCsrfToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $this->set('csrf_token', $token);
        return $token;
    }

    public function validateCsrfToken(string $token): bool
    {
        $storedToken = $this->get('csrf_token');
        $this->remove('csrf_token');
        
        if ($storedToken === null) {
            return false;
        }
        
        return hash_equals($storedToken, $token);
    }
}