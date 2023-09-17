<?php

namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @param Request $request
     * @param string $type
     * @return bool
     */
    public function assignUserRole(Request $request, string $type): bool
    {
        $role = ($type === 'B2B') ? 'b2b_customer' : 'b2c_customer';
        $request->user()->assignRole($role);
        return true;
    }
}
