<?php

namespace app\Core;

use app\Repositories\RoleRepository;

class Role
{
    private static ?array $rolesCache = null;
    private static ?array $hierarchy = null;

    private static array $permissions = [
        'ROLE_ADMIN' => ['*'],
        'ROLE_USER' => ['users.view', 'users.edit.own', 'posts.create', 'posts.edit.own'],
        'ROLE_VISITOR' => ['posts.view']
    ];

    private static function loadHierarchy(): void
    {
        if (self::$hierarchy === null) {
            $config = require __DIR__ . '/../../config/roles.php';

            // Convertir la config en scores hiérarchiques
            self::$hierarchy = [
                'ROLE_ADMIN' => 100,
                'ROLE_USER' => 50,
                'ROLE_VISITOR' => 10
            ];
        }
    }

    private static function loadRoles(): void
    {
        if (self::$rolesCache === null) {
            $repository = new RoleRepository();
            $roles = $repository->findAll();

            self::$rolesCache = [];
            foreach ($roles as $role) {
                self::$rolesCache[$role->getId()] = $role->getName();
            }
        }
    }

    public static function getRoleNameById(int $roleId): ?string
    {
        self::loadRoles();
        return self::$rolesCache[$roleId] ?? null;
    }

    public static function hasPermission(int $roleId, string $permission): bool
    {
        $roleName = self::getRoleNameById($roleId);

        if (!$roleName) {
            return false;
        }

        if (in_array('*', self::$permissions[$roleName] ?? [])) {
            return true;
        }

        return in_array($permission, self::$permissions[$roleName] ?? []);
    }

    public static function inheritsFrom(string $role, string $targetRole): bool
    {
        $config = require __DIR__ . '/../../config/roles.php';

        if ($role === $targetRole) {
            return true;
        }

        if (!isset($config[$role])) {
            return false;
        }

        if (in_array($targetRole, $config[$role])) {
            return true;
        }

        foreach ($config[$role] as $parentRole) {
            if (self::inheritsFrom($parentRole, $targetRole)) {
                return true;
            }
        }

        return false;
    }

    public static function isHigherOrEqual(int $roleId1, int $roleId2): bool
    {
        self::loadHierarchy();

        $role1 = self::getRoleNameById($roleId1);
        $role2 = self::getRoleNameById($roleId2);

        return self::inheritsFrom($role1, $role2);
    }

    public static function canManageRole(int $userRoleId, int $targetRoleId): bool
    {
        self::loadHierarchy();

        $userRole = self::getRoleNameById($userRoleId);
        $targetRole = self::getRoleNameById($targetRoleId);

        return (self::$hierarchy[$userRole] ?? 0) > (self::$hierarchy[$targetRole] ?? 0);
    }
}
