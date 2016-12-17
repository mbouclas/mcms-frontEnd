<?php

namespace Mcms\FrontEnd\Http\Controllers;

use App\Http\Controllers\Controller;
use Mcms\FrontEnd\Models\Team;
use Mcms\FrontEnd\Policies\AddTeamMemberPolicy;
use Illuminate\Http\Request;

use App\Http\Requests;

class TeamMembersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth',['except'=>['show','index']]);
    }

    /**
     * @return array
     */
    public function index()
    {
        return ['success' => true];
    }

    /**
     * The authorize in the Laravel way is hooked in the ServiceProvider as $policies
     * @param Team $team
     * @return string
     */
    public function store(Team $team)
    {
        //enforce policy the normal way
//        (new AddTeamMemberPolicy($team))->allows();
        //enforce the Laravel way. In this case laravel will match the controller method name to the policy method name
        $this->authorize($team);
//        $this->authorize('store',$team);//same as the above, only we are being explicit in which policy we call


        return 'all is well';

    }

    public function show()
    {
        
    }
}
