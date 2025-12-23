<?php

namespace app\Middleware;

use app\Core\Role;
use app\Core\Session;

class RoleMiddleware
{
    public static function check(int $requiredRoleId): bool
    {
        $userRoleId = Session::get('user_role_id');

        if (!$userRoleId) {
            return false;
        }

        return Role::isHigherOrEqual($userRoleId, $requiredRoleId);
    }

    public static function requirePermission(string $permission): void
    {
        $userRoleId = Session::get('user_role_id');

        if (!$userRoleId || !Role::hasPermission($userRoleId, $permission)) {
            http_response_code(403);
            echo "403 - Accès refusé";
            exit;
        }
    }
}
