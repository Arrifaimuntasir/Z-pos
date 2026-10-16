<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Get the currently active branch ID for the logged in user.
     * Administrators can switch branches via session.
     * Normal users are restricted to their assigned branch.
     * 
     * @return int|null
     */
    protected function getActiveBranchId()
    {
        $user = auth()->user();
        if (!$user) return null;

        if ($user->hasRole('Administrator') || $user->hasRole('Super Admin')) {
            $branchId = session('active_branch_id');
        } else {
            $branchId = $user->branch_id;
        }

        if ($branchId && !\App\Models\Branch::where('id', $branchId)->exists()) {
            return null;
        }

        return $branchId;
    }
}
