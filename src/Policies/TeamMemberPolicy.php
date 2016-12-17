<?php

namespace Mcms\FrontEnd\Policies;

use Mcms\FrontEnd\Models\Team;
use Illuminate\Auth\Access\HandlesAuthorization;
use Mcms\Core\Models\User;
/**
 * Policies the Laravel way
 * Class TeamMemberPolicy
 * @package FrontEnd\Policies
 */
class TeamMemberPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * This method is called BEFORE checking for the actual policy.
     * For example, if the user is admin, do not bother checking the store method checks
     * @param $user
     * @return bool
     */
    public function before($user)
    {
        return $user->isAdmin();
    }

    /**
     * Policy that checks a user against a team before store
     * Laravel passes the User automatically when you call $this->authorize() from the controller
     * @param User $user
     * @param Team $team
     */
    public function store(User $user, Team $team)
    {
        if (auth()->guest()){
            //throw exception
            abort(403,'You are not signed in');
        }

        if ($team->isMaxedOut()){
            abort(403,'Your team is maxed out');
        }

        if (! $team->isOwnedByUser($user)){
            abort(403,'You are not the team owner');
        }

        return true;
    }
}
