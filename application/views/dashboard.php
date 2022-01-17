<!-- Main Content -->
<div class="main-content">
<section class="section">
            <div class="row ">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding-top:10px;">
                        <img src="assets/img/dashboard-icons/1-all-complaints.png" style="max-height:60px;" alt="">
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding-top:20px;">
                        <div class="card-content">
                          <h5 class="font-15">All Complaints : <?= ($complaints != '') ? $complaints : '0' ?></h5>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding-top:10px;">
                        <img src="assets/img/dashboard-icons/2-yearly-complaints.png" style="max-height:60px;" alt="">
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding-top:20px;">
                        <div class="card-content">
                          <h5 class="font-15">This Year Complaints : <?= ($thisYear != '') ? $thisYear : '0' ?></h5>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding-top:10px;">
                        <img src="assets/img/dashboard-icons/3-monthly-complaints.png" style="max-height:60px;" alt="">
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding-top:20px;">
                        <div class="card-content">
                          <h5 class="font-15"><?= date('F',time()) ?> Complaints : <?= ($thisMonth != '') ? $thisMonth : '0' ?></h5>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding-top:10px;">
                        <img src="assets/img/dashboard-icons/4-today-complaints.png" style="max-height:60px;" alt="">
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding-top:20px;">
                        <div class="card-content">
                          <h5 class="font-15">Today Complaints :  <?= ($thisDay != '') ? $thisDay : '0' ?></h5>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> 
        
        <!-- second Row::::::::::::::::::::::::::::::::::::    -->
        <div class="row ">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding-top:10px;">
                        <img src="assets/img/dashboard-icons/5-pending-complaints.png" style="max-height:60px;" alt="">
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding-top:20px;">
                        <div class="card-content">
                          <h5 class="font-15">Pending Complaints :  <?= ($pending != '') ? $pending : '0' ?></h5>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding-top:10px;">
                        <img src="assets/img/dashboard-icons/6-completed-complaints.png" style="max-height:60px;" alt="">
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding-top:20px;">
                        <div class="card-content">
                          <h5 class="font-15">Completed Complaints :  <?= ($complete != '') ? $complete : '0' ?></h5>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding-top:15px;">
                        <img src="assets/img/dashboard-icons/7-rejected-complaints.png" style="max-height:50px;" alt="">
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding-top:25px;">
                        <div class="card-content">
                          <h5 class="font-15">Irrelevant Complaints :  <?= ($reject != '') ? $reject : '0' ?></h5>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding-top:10px;">
                        <img src="assets/img/dashboard-icons/9-webportal-complaints.png" style="max-height:60px;" alt="">
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding-top:20px;">
                        <div class="card-content">
                          <h5 class="font-15">Registered By Admin :  <?= ($admin != '') ? $admin : '0' ?></h5>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>  
           <!-- Thrid Row::::::::::::::::::::::::::::::::::::    -->
        <div class="row ">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding-top:10px;">
                        <img src="assets/img/dashboard-icons/10-app-complaints.png" style="max-height:60px;" alt="">
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding-top:20px;">
                        <div class="card-content">
                          <h5 class="font-15">Registered By Complainant :  <?= ($complainants != '') ? $complainants : '0' ?></h5>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card">
                  <div class="card-statistic-4">
                    <div class="align-items-center justify-content-between">
                      <div class="row ">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding-top:10px;">
                          <img src="assets/img/dashboard-icons/11-complainants.png" style="max-height:60px;" alt="">
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding-top:20px;">
                          <div class="card-content">
                            <h5 class="font-15">Total Complainants :  <?= ($complainants != '') ? $complainants : '0' ?></h5>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>  
        </div>    

</section>
</div>
