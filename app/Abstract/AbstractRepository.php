<?php

namespace app\Abstract;

use app\Core\Database;
use PDO;

abstract class AbstractRepository
{
    protected PDO $db;
    protected string $table;
    protected string $entity;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function find(int $id): ?object
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        return $data ? (new $this->entity())->hydrate($data) : null;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM $this->table");
        $results = [];

        foreach ($stmt->fetchAll() as $row) {
            $results[] = (new $this->entity())->hydrate($row);
        }

        return $results;
    }

    public function save(AbstractEntity $entity): bool
    {
        $data = $this->entityToArray($entity);

        if ($entity->getId()) {
            return $this->update($entity->getId(), $data);
        }

        return $this->insert($data);
    }

    protected function insert(array $data): bool
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $stmt = $this->db->prepare("INSERT INTO $this->table ($columns) VALUES ($placeholders)");
        return $stmt->execute($data);
    }

    protected function update(int $id, array $data): bool
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        unset($data['id'], $data['created_at']);

        $set = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));

        $stmt = $this->db->prepare("UPDATE $this->table SET $set WHERE id = :id");
        $data['id'] = $id;

        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM $this->table WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    protected function entityToArray(AbstractEntity $entity): array
    {
        return array_filter(get_object_vars($entity), fn($value) => $value !== null);
    }
}
