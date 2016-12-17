<?php

namespace Mcms\FrontEnd\Models;

use Mcms\Core\Models\User;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * Example of a cool way to create boolean shortcuts that may be useful all around the place
     * @param User $user
     * @return bool
     */
    public function isOwnedByUser(User $user)
    {
        return $this->owner_id == $user->id;
    }

    /**
     * Example of a cool way to create boolean shortcuts that may be useful all around the place
     * return true if the team can't hold any more members
     * @return bool
     */
    public function isMaxedOut()
    {
        return false;
    }
}
