<!-- Main Content -->
<div class="main-content">
<section class="section">
  <div class="row ">

            <?php 

              $dashboard_data_arr = array(
                                           'All Complaints' => $complaints,
                                           'Pending'        => $pending,
                                           'Completed'      => $complete,
                                           'This Year'      => $thisYear,
                                           'This Month'     => $thisMonth, // date('F',time())
                                           'Today'          => $thisDay,
                                           'Irrelevant'     => $reject,
                                           'Web-Portal'     => $web,
                                           'Android'        => $mobile_app,
                                           'Applicants'     => $complainants,
                                         ); 

              foreach($dashboard_data_arr as $key_title => $value)
              { 
                  $value_formatted = ($value != '') ? $value : '0';

                  echo '<div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <div class="card card-statistic-1" bis_skin_checked="1">
                            <div class="card-wrap" bis_skin_checked="1" style="height: 85px;">
                              <div class="padding-20" bis_skin_checked="1" style="height: 100%; display: flex; align-items: center;">
                                <div class="text-left" bis_skin_checked="1">
                                  <h5 class="font-light mb-0" style="font-size:1.1rem;">
                                    <i class="ti-arrow-up text-success"></i> '.$key_title.'
                                  </h5>
                                </div>
                              </div>
                              <div class="card-icon l-bg-green" bis_skin_checked="1" style="position: absolute;right: 0px;top: 0px;display: flex;justify-content: center;align-items: center;padding-top: 5px;">
                                '.$value_formatted.'
                              </div>
                            </div>
                          </div>
                        </div>';
              }
                    
            ?>
  </div> 
</section>
</div>
