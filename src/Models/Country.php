<?php

class Country
{
    private PDO $db;
    private string $table = 'countries';

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function search(string $term): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} 
             WHERE name LIKE :term 
             OR capital LIKE :term 
             OR continent LIKE :term 
             ORDER BY name ASC"
        );
        $stmt->execute(['term' => "%{$term}%"]);
        return $stmt->fetchAll();
    }

    public function getByContinent(string $continent): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE continent = :continent ORDER BY name ASC"
        );
        $stmt->execute(['continent' => $continent]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (name, capital, continent, population, language, currency, description, image_url, best_season, created_at) 
             VALUES (:name, :capital, :continent, :population, :language, :currency, :description, :image_url, :best_season, NOW())"
        );
        $stmt->execute([
            'name' => $data['name'],
            'capital' => $data['capital'],
            'continent' => $data['continent'],
            'population' => $data['population'],
            'language' => $data['language'],
            'currency' => $data['currency'],
            'description' => $data['description'],
            'image_url' => $data['image_url'],
            'best_season' => $data['best_season']
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} 
             SET name = :name, capital = :capital, continent = :continent, 
                 population = :population, language = :language, currency = :currency, 
                 description = :description, image_url = :image_url, best_season = :best_season 
             WHERE id = :id"
        );
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'capital' => $data['capital'],
            'continent' => $data['continent'],
            'population' => $data['population'],
            'language' => $data['language'],
            'currency' => $data['currency'],
            'description' => $data['description'],
            'image_url' => $data['image_url'],
            'best_season' => $data['best_season']
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getContinents(): array
    {
        $stmt = $this->db->query("SELECT DISTINCT continent FROM {$this->table} ORDER BY continent ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}