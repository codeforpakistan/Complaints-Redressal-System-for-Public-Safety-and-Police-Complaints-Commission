<!DOCTYPE html>
<html lang="en">

<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Public Safety Comission Police Complaint Management System</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="../assets/css/app.min.css">
  <link rel="stylesheet" href="../assets/bundles/bootstrap-social/bootstrap-social.css">
  <!-- Template CSS -->
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/components.css">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="../assets/css/custom.css">
  <link rel='shortcut icon' type='image/x-icon' href='../assets/img/logo/logo-icon.png' />
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
                <img alt="image" src="../assets/img/logo/logo-full.png" class="header-logo" />
              </div>
              <h5 class="card-title text-center">Forgot Password</h5>
              <div class="card-body">
              <p class="text-muted">We will send a link to reset your password</p>
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
                  <div class="form-row">
                      <div class="col-12">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-envelope"></i></span>
                          </div>
                          <input name="user_email" type="email" value="" class="input form-control" id="email" placeholder="Email ID" aria-label="Username" aria-describedby="basic-addon1"  require autofocus/>
                        </div>
                      </div>
                    </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                    Forgot Password
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
  <script src="../assets/js/app.min.js"></script>
  <!-- JS Libraies -->
  <!-- Page Specific JS File -->
  <!-- Template JS File -->
  <script src="../assets/js/scripts.js"></script>
  <!-- Custom JS File -->
  <script src="../assets/js/custom.js"></script>
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