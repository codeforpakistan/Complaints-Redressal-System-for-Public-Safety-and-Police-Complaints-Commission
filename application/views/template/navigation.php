<div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="<?= base_url()?>"> <img alt="image" src="assets/img/logo/logo-full.png" class="header-logo" /> 
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="dropdown active">
              <a href="<?= base_url()?>" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                  <i data-feather="briefcase"></i><span>Complaints</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="admin/complaints">View All</a></li>
                    <li><a class="nav-link" href="complaint_register">Register Complaint</a></li>
                </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="image"></i><span>Reports</span></a>
              <ul class="dropdown-menu">
                    <li><a class="nav-link" href="light-gallery.html">District Wise Report</a></li>
                    <li><a class="nav-link" href="light-gallery.html">Yearly Wise Report</a></li>
                    <li><a class="nav-link" href="light-gallery.html">Monthly Wise Report</a></li>
              </ul>
            </li> 
            <li class="dropdown">
                <a href="respondents" class="menu-toggle nav-link has-dropdown">
                  <i data-feather="briefcase"></i><span>Respondents</span>
                </a>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                  <i data-feather="briefcase"></i><span>Complaint Categories</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="admin/complaint_categories">View All</a></li>
                    <!-- <li><a class="nav-link" href="categories_add">Add New Category</a></li> -->
                </ul>
            </li>
            
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                  <i data-feather="briefcase"></i><span>IT Staff</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="admin/IT_staff">View All</a></li>
                </ul>
            </li>
            
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                  <i data-feather="briefcase"></i><span>District Admins</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="admin/district_admin">View All</a></li>
                    <li><a class="nav-link" href="distrcit_admin_add">Add New District Admin</a></li>
                </ul>
            </li>            
            
            <!-- <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                  <i data-feather="briefcase"></i><span>Complainants</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="complainants">View All</a></li>
                    <li><a class="nav-link" href="complainant_add">Add New Complainant</a></li>
                </ul>
            </li> -->
                        
            
            <li class="dropdown">
                <a href="admin/districts" class="nav-link">
                  <i data-feather="briefcase"></i><span>Districts</span>
                </a>
            </li>
                        
          </ul>
        </aside>
      </div>