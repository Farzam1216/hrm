<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class GetAdminTypeRole
{
    /**
     * Remove the specified resource from storage.
     *
     * @return Collection
     */
    public function execute(): Collection
    {
        return Role::where('type', 'admin')->orderBy('id', 'asc')->get();
    }
}
