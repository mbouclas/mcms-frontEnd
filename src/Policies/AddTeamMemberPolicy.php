<?php

namespace Mcms\FrontEnd\Policies;


use Mcms\FrontEnd\Models\Content;
use Mcms\FrontEnd\Models\Team;

class AddTeamMemberPolicy
{
    protected $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }
    public function allows()
    {
        if (auth()->guest()){
            abort(403,'You are not signed in');

        }
        
    }
}