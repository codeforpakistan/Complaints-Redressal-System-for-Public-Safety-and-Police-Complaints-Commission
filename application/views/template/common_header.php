<!DOCTYPE html>
<html lang="en">

<head>
  <base href="<?=base_url()?>">
    <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Public Safety Comission Police Complaint Management System</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="assets/css/app.min.css">
  <link rel="stylesheet" href="assets/bundles/chocolat/dist/css/chocolat.css">
  <link rel="stylesheet" href="assets/bundles/datatables/datatables.min.css">
  <link rel="stylesheet" href="assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
  
  <link rel="stylesheet" href="assets/css/app.min.css">
  <link rel="stylesheet" href="assets/bundles/dropzonejs/dropzone.css">
     <!-- select 2 lib -->
  <link rel="stylesheet" href="assets/bundles/select2/dist/css/select2.min.css">
  <!----breadcrumb----->
  <link rel="stylesheet" href="breadcrumb_assets/style.css">
  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/custom.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="assets/css/custom.css">
  <link rel='shortcut icon' type='image/x-icon' href='assets/img/logo/logo-icon.png' />
  <script src="assets/js/jquery.min.js"></script>

  <style>

    table tr.bg-success th
    {
      color: #fff !important;
    }

  </style>
  
</head>

<body>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar sticky bg-success">
        <div class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg collapse-btn"> <i data-feather="align-justify"></i></a></li>
          </ul>
        </div>
        <ul class="navbar-nav navbar-right">
          <!--<li style="color:#fff; padding-top:5px;"> Code Merged: Jan 10,2022 10:40am</li></li>    -->
          <li class="dropdown"><a href="#" data-toggle="dropdown"
              class="nav-link dropdown-toggle nav-link-lg nav-link-user"> 
                <!--<img alt="image" src="assets/img/user.png" class="user-img-radious-style">-->
                <div style="width: 40px; height: 40px; border: 1px solid #fff; display: inline-block; text-align: center; border-radius: 50%; padding-top: 2px;"><i class="fa fa-user" class="user-img-radious-style"></i></div>
                <span class="d-sm-none d-lg-inline-block"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
              <div class="dropdown-title"><?= $this->session->userdata('user_role_name')?></div>
              <!-- <a href="profile.html" class="dropdown-item has-icon" data-toggle="modal" data-target="#updateAmdinPasswordModel"> -->
              <a href="admin/profile" class="dropdown-item has-icon"> 
                <i class="far fa-user"></i> Profile
              </a> 
              <!-- <a href="#" class="dropdown-item has-icon"> <i class="fas fa-cog"></i>
                Settings
              </a> -->
              <div class="dropdown-divider"></div>
              <a href="<?= base_url('admin/logout_user')?>" class="dropdown-item has-icon text-danger"> <i class="fas fa-sign-out-alt"></i>
                Logout
              </a>
            </div>
          </li>
        </ul>
      </nav>

      <!-- add form -->
        <div class="modal fade" id="updateAmdinPasswordModel" tabindex="-1" role="dialog" aria-labelledby="formModaladd" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="formModaladd">Update Password</h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    <!-- body-->
                        <form class="" method="post" action="<?= base_url("admin/updatePassword") ?>">
                                <div class="form-group">
                                <label>User Password</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    </div>
                                    <input type="password" class="form-control" placeholder="Enter Your Password" name="user_password" required>
                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                      <button type="submit" class="btn btn-primary m-t-15 waves-effect">Save</button>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


      