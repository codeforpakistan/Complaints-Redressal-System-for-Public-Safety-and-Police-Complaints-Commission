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

      foreach($access_data as $oneByOne)
      {
        if($dashboard == $oneByOne->page_name)
        {
          $dashboard = $oneByOne->access;
        }
        else if($complaints == $oneByOne->page_name)
        {
          $complaints = $oneByOne->access;
        }
        else if($complaint_detail == $oneByOne->page_name)
        {
          $complaint_detail = $oneByOne->access;
        }
        else if($complaint_categories == $oneByOne->page_name)
        {
          $complaint_categories = $oneByOne->access;
        }
        else if($users == $oneByOne->page_name)
        {
          $users = $oneByOne->access;
        }
        else if($districts == $oneByOne->page_name)
        {
          $districts = $oneByOne->access;
        }
        else if($reports == $oneByOne->page_name)
        {
          $reports = $oneByOne->access;
        }
        else if($complaint_register == $oneByOne->page_name)
        {
          $complaint_register = $oneByOne->access;
        }

      }
?>


<div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="<?= base_url()?>">
              <img alt="image" src="assets/img/logo/logo-full.png" class="header-logo" /> 
            </a>
          </div> 
          <ul class="sidebar-menu">
            <li class="dropdown active">
              <a href="<?= base_url()?>" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <?php if($complaints != 0 && $complaint_register != 0){?>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                  <i data-feather="briefcase"></i><span>Complaints</span>
                </a>
                <ul class="dropdown-menu">
                    <?php if($complaints !=0) {?>
                    <li><a class="nav-link" href="admin/complaints">View All</a></li>
                    <?php } if($complaint_register !=0) {?>
                    <li><a class="nav-link" href="admin/complaint_register">Register Complaint</a></li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?> 
            <!-- <li class="dropdown">
                <a href="respondents" class="menu-toggle nav-link has-dropdown">
                  <i data-feather="briefcase"></i><span>Respondents</span>
                </a>
            </li> -->
            <?php if($complaint_categories != 0) { ?>
            <li class="dropdown">
                <a href="admin/complaint_categories" class="nav-link">
                  <i data-feather="briefcase"></i><span>Complaint Categories</span>
                </a>
            </li> 
            <?php } if($users != 0) { ?>
            <li class="dropdown">
                <a href="admin/users" class="nav-link">
                  <i data-feather="users"></i><span>Users</span>
                </a>
            </li>   
            <?php } if($districts != 0) { ?>        
            
            <li class="dropdown">
                <a href="admin/districts" class="nav-link">
                  <i data-feather="briefcase"></i><span>Districts</span>
                </a>
            </li>
            <?php } ?>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="image"></i><span>Reports</span></a>
              <ul class="dropdown-menu">
                    <li><a class="nav-link" href="javascript:void(0)">District Wise Report</a></li>
                    <li><a class="nav-link" href="javascript:void(0)">Yearly Wise Report</a></li>
                    <li><a class="nav-link" href="javascript:void(0)">Monthly Wise Report</a></li>
              </ul>
            </li>
                        
          </ul>
        </aside>
      </div>