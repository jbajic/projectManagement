@extends('templates.app')


@section('content')
<div class="container-fluid">

   
    <div class="logo pen-title">
         <img src="/img/logo2.png" alt="Project Management" class="hidden-xs" />
    </div>
    
     <div class="container {{ Session::has('state') ? ' active':'' }}">
      <div class="card"></div>
      <div class="card">
        <h1 class="title">Sign in</h1>
        <form action="{{ route('login.post') }}" method="POST"  role="form">
            {{ csrf_field() }}

          <div class="input-container">
            <input type="text" id="Email" name="email" required="required" value="{{ old('email') }}" autofocus />
            <label for="Email">Email</label>
            <div class="bar"></div>
          </div>

          <div class="input-container">
            <input type="password" id="Password" required="required" name="password" />
            <label for="Password">Password</label>
            <div class="bar"></div>
          </div>

          <div class="footer">
            <input type="checkbox" id="Username" name="remember_me" />
            <label for="Remember_me">Remember me</label>
          </div>

          <div class="button-container">
            @include('includes.message')
          </div>

          <div class="button-container">
            <button><span>Login</span></button>
          </div>
          <div class="footer"><a href="#">Forgot your password?</a></div>

        </form>
     </div>

      <div class="card alt">
        <div class="toggle"></div>
        <h1 class="title">Register
          <div class="close"></div>
        </h1>
        <form action="{{ route('register.post') }}" method="POST">

            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

<!--             <div class="input-container">
                <input type="text" id="Username" required="required" name="username" />
                <label for="Username">Username</label>
                <div class="bar"></div>
            </div> -->

            <div class="input-container">
                <input type="text" id="Email" required="required" name="email" value="{{ old('email') }}" />
                <label for="Email">Email</label>
                <div class="bar"></div>
            </div>

            <div class="input-container">
                <input type="password" id="Password" required="required" name="password" />
                <label for="Password">Password</label>
                <div class="bar"></div>
            </div>

            <div class="input-container">
                <input type="password" id="Repeat Password" required="required" name="password_confirmation" />
                <label for="Repeat Password">Repeat Password</label>
                <div class="bar"></div>
            </div>

          <div class="button-container">
            @include('includes.errors')
          </div>

          <div class="button-container">
            <button type="submit"><span>Next</span></button>
          </div>
        </form>
      </div>
    </div>

</div>
@endsection

