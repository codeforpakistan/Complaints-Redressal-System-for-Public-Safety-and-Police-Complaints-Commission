<?php 
      $access_data = $this->db->select('pages.page_name,page_privileges.access')->where('user_role_id_fk',$this->session->userdata('user_role_id_fk'))->like('access',1)->join('pages','pages.page_id=page_privileges.page_id_fk','inner')->get('page_privileges')->result();
      
      $dashboard            = 0;
      $complaints           = 0;
      $complaint_detail     = 0;
      $complaint_categories = 0;
      $users                = 0; 
      $districts            = 0;
      $reports              = 0;
      $complaint_register   = 0;
      $district_reports     = 0;
      $police_stations      = 0;
      

      foreach($access_data as $oneByOne)
      { 

        if($oneByOne->page_name == 'dashboard')
        { 
          $dashboard = $oneByOne->access;
        }
        if($oneByOne->page_name == 'complaints')
        {
          $complaints = $oneByOne->access;
        }
        if($oneByOne->page_name =='complaint_detail')
        {
          $complaint_detail = $oneByOne->access;
        }
        if($oneByOne->page_name == 'complaint_categories')
        { 
          $complaint_categories = $oneByOne->access;
        }
        if($oneByOne->page_name == 'users')
        {
          $users = $oneByOne->access;
        }
        if($oneByOne->page_name == 'districts')
        { 
          $districts = $oneByOne->access;
        }
        if($oneByOne->page_name == 'reports')
        {
          $reports = $oneByOne->access;
        }
        if($oneByOne->page_name == 'complaint_register')
        {
          $complaint_register = $oneByOne->access;
        } 
        if($oneByOne->page_name == 'district_reports')
        {
          $district_reports = $oneByOne->access;
        }
        if($oneByOne->page_name == 'police_stations')
        {
          $police_stations = $oneByOne->access;
        }

      } 
?>

<style>

   .header-logoo
   { 
     position: inherit !important; 
     left:0px !important; 
   }

</style>

<div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="<?= base_url()?>">
              <img alt="image" src="assets/img/logo/logo-full.png" class="header-logo" /> 
            </a>
          </div> 
          <ul class="sidebar-menu">
            <li class="dropdown active">
              <a href="<?= base_url()?>" class="nav-link">
                <img src="assets/img/dashboard-icons/dashboard.png" />
                <span>Dashboard </span>
              </a>
            </li>
            
            <?php if($users   == 1) { ?>
            <li class="dropdown">
                <a href="admin/users" class="nav-link">
                  <i data-feather="users"></i><span>Users</span>
                </a>
            </li> 

            <?php } if($districts == 1){ ?>        
            
            <li class="dropdown">
                <a href="admin/districts" class="nav-link">
                  <i data-feather="briefcase"></i><span>Districts</span>
                </a>
            </li>
            <?php } if($police_stations   == 1){ ?> 
            <li class="dropdown">
                <a href="admin/police_stations" class="nav-link">
                  <i data-feather="briefcase"></i><span>Police Stations</span>
                </a>
            </li>
            <?php } ?> 
            
            <?php if($complaint_categories  == 1) { ?>
            <li class="dropdown">
                <a href="admin/complaint_categories" class="nav-link">
                  <i data-feather="briefcase"></i><span>Complaint Categories</span>
                </a>
            </li> 
            <?php } ?> 
            
            <?php if($complaints == 1) { ?>
            <li class="dropdown">
                <a href="admin/complaints" class="nav-link">
                  <i data-feather="briefcase"></i><span>Complaints</span>
                </a>
            </li> 
            <?php } ?> 

            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="image"></i><span>Reports</span></a>
              <ul class="dropdown-menu">
                    <?php if($district_reports   == 1): ?>
                    <li><a class="nav-link" href="admin/district_reports">Districts Report</a></li>
                    <?php endif; ?>
                    <li><a class="nav-link" href="admin/detail_report">Detail Report</a></li>
              </ul>
            </li>
                        
          </ul>
        </aside>
      </div>