<?php

namespace app\Repositories;

use app\Abstract\AbstractRepository;
use app\Entities\Role;

class RoleRepository extends AbstractRepository
{
    protected string $table = 'roles';
    protected string $entity = Role::class;

    /**
     * Trouve un rôle par nom
     */
    public function findByName(string $name): ?Role
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE name = :name");
        $stmt->execute(['name' => $name]);
        $data = $stmt->fetch();

        return $data ? (new Role())->hydrate($data) : null;
    }

    /**
     * Vérifie si un rôle a les permissions d'un autre (via hiérarchie)
     */
    public function hasRole(string $userRole, string $requiredRole): bool
    {
        $hierarchy = require __DIR__ . '/../../config/roles.php';

        if ($userRole === $requiredRole) {
            return true;
        }

        if (!isset($hierarchy[$userRole])) {
            return false;
        }

        foreach ($hierarchy[$userRole] as $inheritedRole) {
            if ($this->hasRole($inheritedRole, $requiredRole)) {
                return true;
            }
        }

        return false;
    }
}
