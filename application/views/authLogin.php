<!DOCTYPE html>
<html lang="en">

<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Public Safety Comission Police Complaint Management System</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="assets/css/app.min.css">
  <link rel="stylesheet" href="assets/bundles/bootstrap-social/bootstrap-social.css">
  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="assets/css/custom.css">
  <link rel='shortcut icon' type='image/x-icon' href='assets/img/logo/logo-icon.png' />
</head>

<body>
  <div class="loader"></div>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="card card-primary">
              <div class="card-header">
                <img alt="image" src="assets/img/logo/logo-full.png" class="header-logo" />
              </div>
              <div class="card-body">
              <h5 class="card-title text-center">User Login</h5>
                <!-- start messages --->
                  <div style="text-align: center">
                      <?php if($feedback =$this->session->flashdata('feedback')){
                        $feedback_class =$this->session->flashdata('feedbase_class');  ?>
                            <div class="row">
                              <div class="col-lg-12 col-lg-offset-2">
                              <div class="alert alert-dismissible <?=  $feedback_class;?>">
                              <?= $feedback ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                              </div>
                              </div>
                          </div>

                      
                        <?php }?>

                    </div>
            <!-- end of messages  --->
           
                <form method="POST" action="<?= base_url('Admin/login_user')?>" class="needs-validation" novalidate="">
                  <!-- <div class="form-group">
                    <label for="email">Username</label>
                    <input id="email" type="text" class="form-control" name="user_name" tabindex="1" required autofocus>
                    <div class="invalid-feedback">
                      Please fill in your Username
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="d-block">
                      <label for="password" class="control-label">Password</label> -->
                      <!-- <div class="float-right">
                        <a href="admin/forgot_passord" class="text-small">
                          Forgot Password?
                        </a>
                      </div> -->
                    <!-- </div>
                    <input id="password" type="password" class="form-control" name="user_password" tabindex="2" required >
                    <div class="invalid-feedback">
                      please fill in your password
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                      <label class="custom-control-label" for="remember-me" onclick="hideShowPassword()" id="showHideLabel">Show password</label>
                    </div>
                  </div>  -->


                  <div class="form-row">
                    <div class="col-12">
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                        </div>
                        <input name="user_name" type="text" value="" class="input form-control" id="username" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1"  require autofocus/>
                      </div>
                    </div>
                    <div class="col-12">
                    <div class="float-right">
                        <a href="admin/forgot_passord" class="text-small">
                          Forgot Password?
                        </a>
                      </div> 
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock"></i></span>
                        </div>
                        <input name="user_password" type="password" value="" class="input form-control" id="password" placeholder="password" required="true" aria-label="password" aria-describedby="basic-addon1" />
                        <div class="input-group-append">
                          <span class="input-group-text" onclick="hideShowPassword();">
                            <i class="fas fa-eye" id="show_eye"></i>
                            <i class="fas fa-eye-slash d-none" id="hide_eye"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                        <label class="custom-control-label" for="remember-me">Remember Me</label>
                      </div>
                    </div>
                  </div><br>
                  <!-- <div class="form-group">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                      <label class="custom-control-label" for="remember-me">Remember Me</label>
                    </div>
                  </div> -->
                  <!-- <div class="form-group">
                    <div class="row">
                      <div class="col-md-6">
                        <p id="captImg"><-?php echo $captchaImg; ?></p>
                      </div>
                      <div class="col-md-6">
                        <input type="text" name="captcha" class="form-control" value="" required/>
                      </div>
                    </div>
                    <p>Can't read the image? click <a href="javascript:void(0);" class="refreshCaptcha">here</a> to refresh.</p>
                  </div> -->
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                      Login
                    </button>
                  </div>
                </form>
                <!-- <div class="text-center mt-4 mb-3">
                  <div class="text-job text-muted">Login With Social</div>
                </div>
                <div class="row sm-gutters">
                  <div class="col-6">
                    <a class="btn btn-block btn-social btn-facebook">
                      <span class="fab fa-facebook"></span> Facebook
                    </a>
                  </div>
                  <div class="col-6">
                    <a class="btn btn-block btn-social btn-twitter">
                      <span class="fab fa-twitter"></span> Twitter
                    </a>
                  </div> -->
                </div>
              </div>
            </div>
            <!-- <div class="mt-5 text-muted text-center">
              Don't have an account? <a href="auth-register.html">Create One</a>
            </div> -->
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- General JS Scripts -->
  <script src="assets/js/app.min.js"></script>
  <!-- JS Libraies -->
  <!-- Page Specific JS File -->
  <!-- Template JS File -->
  <script src="assets/js/scripts.js"></script>
  <!-- Custom JS File -->
  <script src="assets/js/custom.js"></script>
</body>


<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->
</html>

<!-- captcha refresh code -->
<script>
$(document).ready(function(){
    $('.refreshCaptcha').on('click', function(){
        $.get('<?= base_url().'admin/refreshCaptcha'; ?>', function(data){
            $('#captImg').html(data);
        });
    });
});
function hideShowPassword() 
{
  var passsword = document.getElementById("password");

  if (passsword.type === "password") 
  {
    passsword.type = "text";
    $('#show_eye').addClass('d-none');
    $('#hide_eye').removeClass('d-none');
    
  } 
  else 
  {
    passsword.type = "password";
    $('#show_eye').removeClass('d-none');
    $('#hide_eye').addClass('d-none');
  }
}
</script>