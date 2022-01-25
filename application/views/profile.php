<?php 
   
     $user_first_name = '';
     $user_last_name  = '';
     $user_email      = '';
     $user_address    = '';
     $user_contact    = '';
     $user_role_name  = '';
     $district_name   = ''; 

     if($profile)
     { 
       foreach($profile as $oneByOne)
       {
          $user_first_name = $oneByOne->user_first_name;
          $user_last_name  = $oneByOne->user_last_name;
          $user_email      = $oneByOne->user_email;
          $user_address    = $oneByOne->user_address;
          $user_contact    = $oneByOne->user_contact;
          $user_role_name  = $oneByOne->user_role_name;
          $district_name   = $oneByOne->district_name; 
       }
        
     }
?>
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
                        <a href="#"><?= ucwords( $user_first_name.' '.$user_last_name ) ?></a>
                      </div>
                      <div class="author-box-job"><?= ucwords($user_role_name) ?></div>
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
                          <?= ucwords( ($district_name != '')? $district_name : 'Super admin' ) ?>
                        </span>
                      </p>
                      <p class="clearfix">
                        <span class="float-left">
                          Phone
                        </span>
                        <span class="float-right text-muted">
                          <?= $user_contact ?>
                        </span>
                      </p>
                      <p class="clearfix">
                        <span class="float-left">
                          Email
                        </span>
                        <span class="float-right text-muted">
                          <?= $user_email ?>
                        </span>
                      </p>
                      <p class="clearfix">
                        <span class="float-left">
                          Address
                        </span>
                        <span class="float-right text-muted">
                          <?= ucwords($user_address) ?>
                        </span>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-12 col-lg-8">
                <div class="card">
                  <form method="post" class="needs-validation" action="admin/update_profile">
                            <div class="card-header">
                              <h4>Edit Profile</h4>
                            </div>
                            <div class="card-body">
                              <div class="row">
                                <div class="form-group col-md-6 col-12">
                                  <label>First Name</label>
                                  <input type="text" class="form-control" name="user_first_name" value="<?= $user_first_name ?>" maxlength="30" onkeyup="this.value=this.value.replace(/[^A-Za-z\s]/g,'');">
                                  <div class="invalid-feedback">
                                    Please fill in the first name
                                  </div>
                                </div>
                                <div class="form-group col-md-6 col-12">
                                  <label>Last Name</label>
                                  <input type="text" class="form-control" name="user_last_name" value="<?= $user_last_name ?> " maxlength="30" onkeyup="this.value=this.value.replace(/[^A-Za-z\s]/g,'');">
                                  <div class="invalid-feedback">
                                    Please fill in the last name
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="form-group col-md-7 col-12">
                                  <label>Email</label>
                                  <input type="email" class="form-control" name="user_email" value="<?= $user_email ?> ">
                                  <div class="invalid-feedback">
                                    Please fill in the email
                                  </div>
                                </div>
                                <div class="form-group col-md-5 col-12">
                                  <label>Phone</label>
                                  <input type="tel" class="form-control" name="user_contact" value="<?= $user_contact ?> " data-inputmask="'mask': '0399-99999999'" required maxlength = "12" minlenth="12">
                                </div>
                              </div>
                              <div class="row">
                                <div class="form-group col-12">
                                  <label>Address</label>
                                  <textarea
                                    class="form-control summernote-simple" name="user_address"><?= $user_address ?></textarea>
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
                              <button class="btn btn-primary">Save Changes</button>
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
        </script>