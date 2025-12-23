<?php

namespace app\Repositories;

use app\Abstract\AbstractRepository;
use app\Entities\User;

class UserRepository extends AbstractRepository
{
    protected string $table = 'users';
    protected string $entity = User::class;

    /**
     * Trouve un utilisateur par email
     */
    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch();

        return $data ? (new User())->hydrate($data) : null;
    }

    /**
     * Trouve un utilisateur par token de réinitialisation
     */
    public function findByPasswordResetToken(string $token): ?User
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM $this->table 
             WHERE password_reset_token = :token 
             AND password_reset_expires_at > NOW()"
        );
        $stmt->execute(['token' => $token]);
        $data = $stmt->fetch();

        return $data ? (new User())->hydrate($data) : null;
    }

    /**
     * Trouve tous les utilisateurs actifs
     */
    public function findAllActive(): array
    {
        $stmt = $this->db->query("SELECT * FROM $this->table WHERE is_active = 1");
        $results = [];

        foreach ($stmt->fetchAll() as $row) {
            $results[] = (new User())->hydrate($row);
        }

        return $results;
    }

    /**
     * Trouve tous les utilisateurs par rôle
     */
    public function findByRole(int $roleId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE role_id = :role_id");
        $stmt->execute(['role_id' => $roleId]);
        $results = [];

        foreach ($stmt->fetchAll() as $row) {
            $results[] = (new User())->hydrate($row);
        }

        return $results;
    }

    /**
     * Met à jour la date de dernière connexion
     */
    public function updateLastLogin(int $userId): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE $this->table SET last_login_at = NOW() WHERE id = :id"
        );
        return $stmt->execute(['id' => $userId]);
    }

    /**
     * Compte le nombre d'utilisateurs
     */
    public function count(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM $this->table");
        return (int)$stmt->fetch()['total'];
    }

    /**
     * Vérifie si un email existe déjà
     */
    public function emailExists(string $email, ?int $excludeUserId = null): bool
    {
        $sql = "SELECT COUNT(*) as total FROM $this->table WHERE email = :email";
        $params = ['email' => $email];

        if ($excludeUserId) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeUserId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int)$stmt->fetch()['total'] > 0;
    }
}
