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
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = :username LIMIT 1");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
            
            return null;
        } catch (PDOException $e) {
            error_log("Authentication error: " . $e->getMessage());
            return null;
        }
    }
}