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
                <a href="admin/complaint_categories" class="nav-link">
                  <i data-feather="briefcase"></i><span>Complaint Categories</span>
                </a>
            </li> 
            
            <li class="dropdown">
                <a href="admin/users" class="nav-link">
                  <i data-feather="briefcase"></i><span>Users</span>
                </a>
            </li>   
                        
            
            <li class="dropdown">
                <a href="admin/districts" class="nav-link">
                  <i data-feather="briefcase"></i><span>Districts</span>
                </a>
            </li>
                        
          </ul>
        </aside>
      </div>