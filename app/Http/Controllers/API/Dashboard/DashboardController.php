<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DashboardController extends Controller
{
    public function summary()
    {
        $totalUsers       = User::count();
        $activeUsers      = User::where('active', true)->count();
        $inactiveUsers    = User::where('active', false)->count();

        $usersByRole = Role::all()->map(fn($role) => [
            'role'  => $role->name,
            'count' => User::role($role->name)->count(),
        ])->toArray();

        $totalRoles       = Role::count();
        $totalPermissions = Permission::count();

        $recentUsers = User::with('roles')
                           ->orderBy('created_at', 'desc')
                           ->limit(5)
                           ->get();

        return response()->json([
            'success'          => true,
            'data'             => [
                'total_users'      => $totalUsers,
                'active_users'     => $activeUsers,
                'inactive_users'   => $inactiveUsers,
                'users_by_role'    => $usersByRole,
                'total_roles'      => $totalRoles,
                'total_permissions'=> $totalPermissions,
                'recent_users'     => $recentUsers,
            ],
        ]);
    }
}
