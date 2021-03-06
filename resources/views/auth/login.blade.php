<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
  <meta charset="utf-8" />
  <title>Map fitme</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <meta content="Map" name="description" />
  <meta content="" name="author" />
  <!-- BEGIN GLOBAL MANDATORY STYLES -->
  <link href="{{url('assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
  {{--<link href="../assets/global/css/components-md.min.css" rel="stylesheet" id="style_components" type="text/css" />--}}
  <link href="{{url('assets/pages/css/login-5.min.css')}}" rel="stylesheet" type="text/css" />
  <link rel="shortcut icon" href="favicon.ico" /> </head>
<!-- END HEAD -->

<body class=" login">
<!-- BEGIN : LOGIN PAGE 5-2 -->
<div class="user-login-5">
  <div class="row bs-reset">
    <div class="col-md-6 login-container bs-reset">
      <div class="login-content">
        <h1>LOGIN</h1>
        <p>Vui lòng đăng nhập vào hệ thống</p>
        <form class="form-horizontal" role="form" method="POST" id="register-form" action="{{ url('/login') }}">
          {{ csrf_field() }}
         
          <div class="row">
            <div class="col-xs-6">
              <input class="form-control form-control-solid placeholder-no-fix form-group" type="text" id="email" autocomplete="off" placeholder="Email" name="email" required/>
              @if ($errors->has('email'))
                <span class="help-block">
                  <strong>{{ $errors->first('email') }}</strong>
                </span>
              @endif</div>
            <div class="col-xs-6">
              <input class="form-control form-control-solid placeholder-no-fix form-group" type="password" autocomplete="off" placeholder="Password" name="password" required/>
              @if ($errors->has('password'))
                <span class="help-block">
                  <strong>{{ $errors->first('password') }}</strong>
                </span>
              @endif</div>
          </div>
          <div class="row">
            <div class="col-xs-6">
              <input type="checkbox" name="remember" />
              <span> Remember </span>
              <button class="btn blue" type="submit" style="float: right">Đăng Nhập</button>
            </div>
            @if (isset($config['recaptcha']) && $config['recaptcha'] == 1)
            <div class="col-xs-6">
              {!! Recaptcha::render() !!}
              @if ($errors->has('g-recaptcha-response'))
                <span class="help-block">
                  <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                </span>
              @endif
            </div>
            @endif
          </div>
        </form>
        <!-- END FORGOT PASSWORD FORM -->
      </div>
      <div class="login-footer">
        <div class="row bs-reset">

          <div class="col-xs-7 bs-reset">
            <div class="login-copyright text-right">
              {{--<p>Copyright &copy; Keenthemes 2015</p>--}}
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 bs-reset">
      <div class="login-bg"> </div>
    </div>
  </div>
</div>

<!-- END : LOGIN PAGE 5-2 -->
<!--[if lt IE 9]>
<script src="{{url('assets/global/plugins/respond.min.js')}}"></script>
<script src="{{url('assets/global/plugins/excanvas.min.js')}}"></script>
<script src="{{url('assets/global/plugins/ie8.fix.min.js')}}"></script>
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="{{url('assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
@if (isset($config['recaptcha']) && $config['recaptcha'] == 1)
<script src='https://www.google.com/recaptcha/api.js'></script>
@endif
<script type="text/javascript">
    $('input[type=password]').keypress(function(e) {
        if(e.which == 10 || e.which == 13) {
            $('#register-form').submit();
        }
    });

    $('#email').keypress(function(e) {
        if(e.which == 10 || e.which == 13) {
            $('#register-form').submit();
        }
    });


</script>
</body>



</html>