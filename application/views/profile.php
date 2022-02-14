      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <div class="row mt-sm-4">
              <div class="col-12 col-md-12 col-lg-4">
                <div class="card author-box">
                  <div class="card-body">
                    <div class="author-box-center">
                      <!-- <img alt="image" src="assets/img/users/user-1.png" class="rounded-circle author-box-picture"> -->
                      <div class="clearfix"></div>
                      <div class="author-box-name" style="margin-top:10px;">
                        <a href="#"><span class="user_profile_name"></span></a>
                      </div>
                      <div class="author-box-job"><span class="user_profile_role"></div>
                    </div>
                  </div>
                </div>
                <div class="card">
                  <div class="card-header">
                    <h4>Personal Details</h4>
                  </div>
                  <div class="card-body">
                    <div class="">
                      <p class="clearfix">
                        <span class="float-left">
                          District
                        </span>
                        <span class="float-right text-muted">
                        <span class="user_profile_district"></span>
                        </span>
                      </p>
                      <p class="clearfix">
                        <span class="float-left">
                          Phone
                        </span>
                        <span class="float-right text-muted">
                        <span class="user_profile_contact"></span>
                        </span>
                      </p>
                      <p class="clearfix">
                        <span class="float-left">
                          Email
                        </span>
                        <span class="float-right text-muted">
                        <span class="user_profile_email"></span>
                        </span>
                      </p>
                      <p class="clearfix">
                        <span class="float-left">
                          Address
                        </span>
                        <span class="float-right text-muted">
                        <span class="user_profile_address"></span>
                        </span>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-12 col-lg-8">
                <div class="card">
                  <form method="post" class="needs-validation profile_add_form">
                            <div class="card-header">
                              <h4>Edit Profile</h4>
                            </div>
                            <div class="card-body">
                              <div class="row">
                                <div class="form-group col-md-6 col-12">
                                  <label>First Name</label>
                                  <input type="text" class="form-control" name="user_first_name" value="" maxlength="30" onkeyup="this.value=this.value.replace(/[^A-Za-z\s]/g,'');">
                                  <div class="invalid-feedback">
                                    Please fill in the first name
                                  </div>
                                </div>
                                <div class="form-group col-md-6 col-12">
                                  <label>Last Name</label>
                                  <input type="text" class="form-control" name="user_last_name" value=" " maxlength="30" onkeyup="this.value=this.value.replace(/[^A-Za-z\s]/g,'');">
                                  <div class="invalid-feedback">
                                    Please fill in the last name
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="form-group col-md-7 col-12">
                                  <label>Email</label>
                                  <input type="email" class="form-control" name="user_email" value="">
                                  <div class="invalid-feedback">
                                    Please fill in the email
                                  </div>
                                </div>
                                <div class="form-group col-md-5 col-12">
                                  <label>Phone</label>
                                  <input type="tel" class="form-control" name="user_contact" value="" data-inputmask="'mask': '0399-99999999'" required maxlength = "12" minlenth="12">
                                </div>
                              </div>
                              <div class="row">
                                <div class="form-group col-12">
                                  <label>Address</label>
                                  <textarea
                                    class="form-control summernote-simple" name="user_address"></textarea>
                                </div>
                              </div>
                              <div class="row">
                                  <div class="form-group col-md-12 col-12">
                                    <label>Old Password</label>
                                    <input type="password" class="form-control" name="old_password">
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="form-group col-md-6 col-12">
                                    <label>New Password</label>
                                    <input type="password" class="form-control" name="password" onChange="onChange()">
                                  </div>
                                  <div class="form-group col-md-6 col-12">
                                    <label>Re-type New Password</label>
                                    <input type="password" class="form-control" name="confirm" onChange="onChange()">
                                  </div>
                              </div>
                              <div class="row">
                                <div class="form-group mb-0 col-12">
                                  <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="remember" class="custom-control-input" id="newsletter">
                                    <label class="custom-control-label" for="newsletter">Logout after password change</label>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="card-footer text-right">
                              <button class="btn btn-primary update_button">Save Changes</button>
                            </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
  <script src="assets/js/jquery.inputmask.bundle.js"></script>
  <<script>
    $(":input").inputmask();

        function onChange() 
        {
          const password = document.querySelector('input[name=password]');
          const confirm = document.querySelector('input[name=confirm]');
          if (confirm.value === password.value) {
            confirm.setCustomValidity('');
          } else {
            confirm.setCustomValidity('Passwords do not match');
          }
        }
      $(document).ready(function()
      {
        user_profile_list();
        function user_profile_list()
        {
          var user_first_name = '';
          var user_last_name  = '';
          var user_email      = '';
          var user_address    = '';
          var user_contact    = '';
          var user_role_name  = '';
          var district_name   = ''; 
          $.ajax({
            url: 'admin/profle_info',
            dataType:'json',
            success: function(response)
            { 
              $.each(response, function( index, oneByOne ) 
              {
                 user_first_name = oneByOne.user_first_name;
                 user_last_name  = oneByOne.user_last_name;
                 user_email      = oneByOne.user_email;
                 user_address    = oneByOne.user_address;
                 user_contact    = oneByOne.user_contact;
                 user_role_name  = oneByOne.user_role_name;
                 if(oneByOne.district_name == '' || oneByOne.district_name == null || oneByOne.district_name == 0)
                 {
                  district_name   = 'Super admin';
                 }
                 else
                 {
                  district_name   = oneByOne.district_name;c

                 }
                 
              });
              // profile 
              $('.user_profile_name').html(user_first_name+' '+user_last_name);
              $('.user_profile_role').html(user_role_name);
              $('.user_profile_district').html(district_name);
              $('.user_profile_contact').html(user_contact);
              $('.user_profile_email').html(user_email);
              $('.user_profile_address').html(user_address);
              // form values
              $('[name="user_first_name"]').val(user_first_name);
              $('[name="user_last_name"]').val(user_last_name);
              $('[name="user_email"]').val(user_email);
              $('[name="user_contact"]').val(user_contact);
              $('[name="user_address"]').val(user_address);
              $('[name="old_password"]').val('');
              $('[name="password"]').val('');
              $('[name="confirm"]').val('');
            }
          }); 
        }  // end profile
          // update profile 
        $('.profile_add_form').submit(function(e){
              e.preventDefault(); 
              $('.update_button').prop("disabled", true);
              var formData = new FormData( $(".profile_add_form")[0] );

              $.ajax({
                          url:"<?php echo base_url(); ?>admin/update_profile",
                          type:"post",
                          data:formData,
                          processData:false,
                          contentType:false,
                          cache:false,
                          async:false,
                          success: function(response)
                          { 
                            $('.update_button').prop("disabled", false);
                              if(response == 'Record Update')
                              {
                                  user_profile_list();
                                  message(1,response);
                              }
                              else if(response == 'Please login now')
                              {
                                window.location.href = "admin/logout_user";
                              }
                              else
                              { 
                                message(0,response);
                              }
                          }
                      }); 
                      
          });

      });    
  </script>