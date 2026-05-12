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
        try {
            $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY name ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error fetching countries: " . $e->getMessage());
            return [];
        }
    }

    public function getById(int $id): ?array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetch();
            return $result ?: null;
        } catch (PDOException $e) {
            error_log("Error fetching country {$id}: " . $e->getMessage());
            return null;
        }
    }

    public function search(string $term): array
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM {$this->table} 
                 WHERE name LIKE :term1 
                 OR capital LIKE :term2 
                 OR continent LIKE :term3 
                 OR language LIKE :term4
                 ORDER BY name ASC"
            );
            $searchTerm = "%{$term}%";
            $stmt->execute([
                'term1' => $searchTerm,
                'term2' => $searchTerm,
                'term3' => $searchTerm,
                'term4' => $searchTerm
            ]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error searching countries: " . $e->getMessage());
            return [];
        }
    }

    public function getByContinent(string $continent): array
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM {$this->table} WHERE continent = :continent ORDER BY name ASC"
            );
            $stmt->execute(['continent' => $continent]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error fetching countries by continent: " . $e->getMessage());
            return [];
        }
    }

    public function create(array $data): ?int
    {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO {$this->table} (name, capital, continent, population, language, currency, description, image_url, best_season, created_at) 
                 VALUES (:name, :capital, :continent, :population, :language, :currency, :description, :image_url, :best_season, NOW())"
            );
            
            $stmt->execute([
                'name' => htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8'),
                'capital' => htmlspecialchars($data['capital'], ENT_QUOTES, 'UTF-8'),
                'continent' => $data['continent'],
                'population' => (int)$data['population'],
                'language' => htmlspecialchars($data['language'], ENT_QUOTES, 'UTF-8'),
                'currency' => htmlspecialchars($data['currency'], ENT_QUOTES, 'UTF-8'),
                'description' => htmlspecialchars($data['description'], ENT_QUOTES, 'UTF-8'),
                'image_url' => filter_var($data['image_url'], FILTER_SANITIZE_URL),
                'best_season' => htmlspecialchars($data['best_season'], ENT_QUOTES, 'UTF-8')
            ]);
            
            return (int)$this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creating country: " . $e->getMessage());
            return null;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $stmt = $this->db->prepare(
                "UPDATE {$this->table} 
                 SET name = :name, capital = :capital, continent = :continent, 
                     population = :population, language = :language, currency = :currency, 
                     description = :description, image_url = :image_url, best_season = :best_season 
                 WHERE id = :id"
            );
            
            return $stmt->execute([
                'id' => $id,
                'name' => htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8'),
                'capital' => htmlspecialchars($data['capital'], ENT_QUOTES, 'UTF-8'),
                'continent' => $data['continent'],
                'population' => (int)$data['population'],
                'language' => htmlspecialchars($data['language'], ENT_QUOTES, 'UTF-8'),
                'currency' => htmlspecialchars($data['currency'], ENT_QUOTES, 'UTF-8'),
                'description' => htmlspecialchars($data['description'], ENT_QUOTES, 'UTF-8'),
                'image_url' => filter_var($data['image_url'], FILTER_SANITIZE_URL),
                'best_season' => htmlspecialchars($data['best_season'], ENT_QUOTES, 'UTF-8')
            ]);
        } catch (PDOException $e) {
            error_log("Error updating country {$id}: " . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log("Error deleting country {$id}: " . $e->getMessage());
            return false;
        }
    }

    public function getContinents(): array
    {
        try {
            $stmt = $this->db->query("SELECT DISTINCT continent FROM {$this->table} ORDER BY continent ASC");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Error fetching continents: " . $e->getMessage());
            return [];
        }
    }
}