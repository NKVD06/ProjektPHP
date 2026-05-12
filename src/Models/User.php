<?php

class User
{
    private PDO $db;
    private string $table = 'users';

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function authenticate(string $username, string $password): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }
}