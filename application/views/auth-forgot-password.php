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
  <link rel="stylesheet" href="../assets/bundles/izitoast/css/iziToast.min.css">
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
              <h5 class="card-title text-center heading">Forgot Password</h5>
              <div class="card-body">
              <p class="text-muted para">We will send a link to reset your password</p>
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
           
                <form method="POST" class="needs-validation forgot_email_form" novalidate="">
                  <div class="form-row">
                      <div class="col-12">
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-envelope"></i></span>
                          </div>
                          <input name="user_email" type="email" value="" class="input form-control" id="forgot_email" placeholder="Email ID" aria-label="Username" aria-describedby="basic-addon1"  require autofocus/>
                        </div>
                      </div>
                    </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block forgot_button" tabindex="4">
                    Forgot Password
                    </button>
                  </div>
                </form>
                <!-- conformation code form-->
                  <form method="POST" class="needs-validation conformation_code_form d-none" novalidate="">
                    <div class="form-row">
                        <div class="col-12">
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon1"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input name="user_email" type="email" value="" class="input form-control" id="user_email" placeholder="Email ID" aria-label="Username" aria-describedby="basic-addon1" disabled/>
                          </div>
                        </div>
                        <div class="col-12 hideShowVcode">
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon1"><i class="fas fa-key"></i></span>
                            </div>
                            <input name="vcode" type="text" class="input form-control" id="vcode" placeholder="Verification code" aria-label="Username" aria-describedby="basic-addon1"  require/>
                          </div>
                        </div>
                      </div>
                    <div class="form-group">
                      <input type="checkbox" name="resend_code" id="resend_code" value="resend_code"> <lable style="color:blue;"> &nbsp; Resend Code</lable>
                      <button type="submit" class="btn btn-primary btn-lg btn-block vcode_button" tabindex="4">
                         Conform Verification code
                      </button>
                
                      <input type="hidden" name="user_id" id="user_id" />
                      <input type="hidden" name="user_email" id="vcode_user_email">
                    </div>
                  </form>
                <!-- conformation code form end -->
                <!--Reset password form-->
                <form method="POST" class="needs-validation update_password_form d-none" novalidate="">
                    <div class="form-row">
                        <div class="col-12">
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon1"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input name="user_email" type="email" value="" class="input form-control" id="rr_user_email" placeholder="Email ID" aria-label="Username" aria-describedby="basic-addon1" disabled/>
                          </div>
                        </div>
                        <div class="col-12">
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon1"><i class="fas fa-key"></i></span>
                            </div>
                            <input name="r_password" type="text" class="input form-control" id="r_password" placeholder="New Password" aria-label="Username" aria-describedby="basic-addon1" onChange="onChange()"  require/>
                          </div>
                        </div>
                        <div class="col-12">
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon1"><i class="fas fa-key"></i></span>
                            </div>
                            <input name="r_cpassword" type="password" class="input form-control" id="r_cpassword" placeholder="Conform Password" aria-label="Username" aria-describedby="basic-addon1" onChange="onChange()"  require/>
                          </div>
                        </div>
                      </div>
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary btn-lg btn-block vcode_button" tabindex="4">
                         Update Password
                      </button>
                      <input type="hidden" name="user_id" id="r_user_id" />
                      <input type="hidden" name="user_email" id="r_user_email">
                    </div>
                  </form>
                <!-- Reset Password form end -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- General JS Scripts -->
  <script src="../assets/js/app.min.js"></script>
  <!-- JS Libraies -->
  <script src="../assets/bundles/izitoast/js/iziToast.min.js"></script>
  <!-- <script src="assets/bundles/sweetalert/sweetalert.min.js"></script> -->

  <!-- Page Specific JS File -->
  <!-- Template JS File -->
  <script src="../assets/js/scripts.js"></script>
  <!-- Custom JS File -->
  <script src="../assets/js/custom.js"></script>
</body>


</html>

<!-- captcha refresh code -->
<script>
  $(document).ready(function()
  {
    $('.forgot_email_form').submit(function(e)
    {
        e.preventDefault();
        $('.heading').html('Conformation Code');
        $('.para').addClass('d-none'); 
        var formData = new FormData( $(".forgot_email_form")[0] );
        $('.forgot_button').prop("disabled", true);
          $.ajax({
                      url:"<?php echo base_url(); ?>admin/forgot_email_validation",
                      type:"post",
                      data:formData,
                      processData:false,
                      contentType:false,
                      cache:false,
                      async:false,
                      success: function(response)
                      { 
                        response = JSON.parse(response);
                        // alert(response.message);
                        $('.forgot_button').prop("disabled", false);
                          if(response.message == 'Kindly check your email for verification code.')
                          {
                            $('.forgot_email_form').addClass('d-none');
                            $('.conformation_code_form').removeClass('d-none');
                            $('#user_email').val(response.user_email);
                            $('#user_id').val(response.user_id);
                            $('#vcode_user_email').val(response.user_email);
                            message(1,response.message);
                          }
                          else
                          { 
                            message(0,response.message);
                          }
                      }
                  }); 
                        
    });
    // conformation code
    $('.conformation_code_form').submit(function(e)
    { 
        e.preventDefault(); 
        var formData = new FormData( $(".conformation_code_form")[0] );
        $('.vcode_button').prop("disabled", true);
        $('.heading').html('Reset Password');
        $('.para').addClass('d-none');
          $.ajax({
                      url:"<?php echo base_url(); ?>admin/conformation_code",
                      type:"post",
                      data:formData,
                      processData:false,
                      contentType:false,
                      cache:false,
                      async:false,
                      success: function(response)
                      {   
                       response = JSON.parse(response);
                        // alert(response);
                        $('.vcode_button').prop("disabled", false);
                          if(response.message == 'record exists')
                          {
                            $('.forgot_email_form').addClass('d-none');
                            $('.conformation_code_form').addClass('d-none');
                            $('.update_password_form').removeClass('d-none');
                            $('#r_user_email').val(response.user_email);
                            $('#rr_user_email').val(response.user_email);
                            $('#r_user_id').val(response.user_id);
                            // $('.hideShowVcode').removeClass('d-none');
                            // $('#resend_code').prop('checked', false);
                            message(1,response.message);
                          }
                          else if(response.message == 'Kindly check your email for verification code.')
                          {
                            $('#resend_code').prop('checked', false);
                            $('.hideShowVcode').removeClass('d-none');
                            $('.vcode_button').html('Conform Conformation Code');
                            $('.forgot_email_form').addClass('d-none');
                            $('.conformation_code_form').removeClass('d-none');
                            $('#user_email').val(response.user_email);
                            $('#user_id').val(response.user_id);
                            $('#vcode_user_email').val(response.user_email);
                            message(1,response.message);
                          }

                          else
                          { 
                            message(0,response.message);
                          }
                      }
                  }); 
                        
    });
    //  update password
    $('.update_password_form').submit(function(e)
    {
        e.preventDefault(); 
        var formData = new FormData( $(".update_password_form")[0] );
        $('.vcode_button').prop("disabled", true);
        $('.heading').html('Reset Password');
        $('.para').addClass('d-none');
          $.ajax({
                      url:"<?php echo base_url(); ?>admin/update_password",
                      type:"post",
                      data:formData,
                      processData:false,
                      contentType:false,
                      cache:false,
                      async:false,
                      success: function(response)
                      {  
                        $('.vcode_button').prop("disabled", false);
                          if(response == 'Password Update Successfully please Login now')
                          {
                            $('.update_password_form').addClass('d-none');
                            $('.heading').html('Update password Successfully');
                            $('.para').removeClass('d-none');
                            $('.para').html('Please Login With New Password');
                            message(1,response);
                            setTimeout(function() {
                                  window.location.href = "login_user";
                              }, 7000);
                            
                            
                          }
                          else
                          { 
                            message(0,response);
                          }
                      }
                  }); 
                        
    });

    $("#resend_code").on("click", function()
    {
      check = $("#resend_code").prop("checked");
        if(check) 
        {
            $('.hideShowVcode').addClass('d-none');
            $('.vcode_button').html('Resend Code');
        } 
        else 
        {
            $('.hideShowVcode').removeClass('d-none');
            $('.vcode_button').html('Conform Conformation Code');
        }
   }); 

  });            


  function onChange() 
  {
    const password = document.querySelector('input[name=r_password]');
    const confirm = document.querySelector('input[name=r_cpassword]');
    if (confirm.value === password.value) {
      confirm.setCustomValidity('');
    } else {
      confirm.setCustomValidity('Passwords do not match');
    }
  }


  function message(status,response_msg)
{
    if(status == 1)
    {
        
        iziToast.success({
        title: 'Success:',
        message: response_msg,
        position: 'topRight'
        });
    }
    else
    { 
    iziToast.error({
        title: 'Error:',
        message: response_msg,
        position: 'topRight'
        });

    }
}


</script>

<style>
    .jconfirm .jconfirm-scrollpane {position: relative;;left: 36%;}
</style>

