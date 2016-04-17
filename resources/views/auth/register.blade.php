@extends('app')

@section('styles')
	<link href="/css/logAndReg.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container-fluid">

	<div class="pen-title">
      <h1>Material Login Form</h1><span>Pen <i class='fa fa-code'></i> by <a href='http://andytran.me'>Andy Tran</a></span>
    </div>
    <div class="rerun"><a href="">Rerun Pen</a></div>

    <div class="container">
      <div class="card"></div>
      <div class="card">
        <h1 class="title">Login</h1>
        <form>
          <div class="input-container">
            <input type="text" id="Username" required="required"/>
            <label for="Username">Username</label>
            <div class="bar"></div>
          </div>
          <div class="input-container">
            <input type="password" id="Password" required="required"/>
            <label for="Password">Password</label>
            <div class="bar"></div>
          </div>
          <div class="button-container">
            <button><span>Go</span></button>
          </div>
          <div class="footer"><a href="#">Forgot your password?</a></div>
        </form>
     </div>

      <div class="card alt">
        <div class="toggle"></div>
        <h1 class="title">Register
          <div class="close"></div>
        </h1>
        <form>
          <div class="input-container">
            <input type="text" id="Username" required="required"/>
            <label for="Username">Username</label>
            <div class="bar"></div>
          </div>
          <div class="input-container">
            <input type="password" id="Password" required="required"/>
            <label for="Password">Password</label>
            <div class="bar"></div>
          </div>
          <div class="input-container">
            <input type="password" id="Repeat Password" required="required"/>
            <label for="Repeat Password">Repeat Password</label>
            <div class="bar"></div>
          </div>
          <div class="button-container">
            <button><span>Next</span></button>
          </div>
        </form>
      </div>
    </div>

</div>




<div class="container-fluid">

    <div class="row vertical-center">
        <div class="col-md-8 col-md-offset-2">

            <div class="logo">
                <img src="/img/logo.png" alt="Project Management" class="hidden-xs" />
            </div>

            <div class="login-panel panel panel-default login">
                <div class="panel-heading">
                    <h3 class="panel-title center"> Sign In</h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('login.post') }}" method="POST" role="form">
                        {{ csrf_field() }}
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" placeholder="Username" name="username" type="text" autofocus>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Email" name="email" type="email">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Password" name="password" type="password" value="">
                            </div>
                            <div class="checkbox center">
                                <label>
                                    <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                </label>
                            </div>
                            <!-- Change this to a button or input when using this as a form -->
                            <a href="#" class="btn btn-lg btn-success btn-block">Login</a>
                        </fieldset>
                    </form>
                    <ul class="nav nav-pills">
                        <li><a href="{{ route('reset.password') }}"> Forgot your password?</a></li>
                        <li class="navbar-right"><a href="{{ route('register.get') }}"> Register <a/></li>
                    </ul>
                </div>

            </div>

      </div>
    </div>
</div>
