<?php
namespace App\Http\Composers;

use Illuminate\Contrats\View\View;
use Auth;

class NavigationComposer
{

	public function compose($view)
	{
        $requests = Auth::user()->friendRequestPending();

        $view->with( array('name' => Auth::user()->name, 'friendRequests' => $requests, 'avatar' => Auth::user()->avatar ));
	}
}