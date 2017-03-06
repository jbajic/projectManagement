<?php
namespace App\Http\Composers;

use Illuminate\Contrats\View\View;
use Auth;

class NavigationComposer
{

	public function compose($view)
	{
        $requests = Auth::user()->friendRequestPending();

        $numberOfRequests = count($requests);

        $view->with( array('name' => Auth::user()->name, 'friendRequests' => $requests, 'avatar' => Auth::user()->avatar, 
        				'numberOfRequests' => $numberOfRequests ));
	}
}