 <!-- Main Content -->
 <div class="main-content">
        <section class="section">
          <div class="section-body">

            <div class="row">
              <div class="col-12" bis_skin_checked="1">
                <div class="card mb-0" bis_skin_checked="1">
                  <div class="card-body" bis_skin_checked="1">
                    <div class="col-md-9" style="float:left; padding-left:0px;">
                      <ul class="nav nav-pills">
                        <li class="nav-item">
                          <a class="nav-link active" href="javascript:void(0);">All Complaints<span class="badge badge-white">10</span></a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="javascript:void(0);">Pending <span class="badge" style="bbackground-color:#ebb30c">10</span></a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="javascript:void(0);">Completed <span class="badge" style="bbackground-color:#28a745">5</span></a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="javascript:void(0);">Irrelevant <span class="badge" style="bbackground-color:#dc3545">15</span></a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="javascript:void(0);">Others <span class="badge" style="bbackground-color:#76797a">30</span></a>
                        </li>
                      </ul>
                    </div>
                    <div class="col-3 text-right" style="float:left; padding-right:0px;">
                      <a href="admin/complaint_register" class="btn btn-icon icon-left btn-success custom-success-btn"><i class="fas fa-plus"></i> Register New Complaint</a>  
                    </div>
                    <div class="clearfix"></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="clearfix"></div>
            <div class="row mt-4">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                  <!-- start messages --->
                      <div style="text-align: center">
                              <?php if($feedback =$this->session->flashdata('feedback')){
                                $feedback_class =$this->session->flashdata('feedbase_class');  ?>
                                    <div class="row">
                                      <div class="col-md-6 offset-3">
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
                    <div class="table-responsive">
                      <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                        <thead class="">
                        <tr>
                            <th class="text-center">Complaint Id</th>
                            <th>Source</th>
                            <th>Applicant Name</th>
                            <th>District</th>
                            <th>Complaint Date</th>
                            <th>Status </th>
                            <th>Actions</th> 
                        </tr>
                        </thead>
                            <tbody>
                                <?php if($complaints): foreach($complaints as $oneByOne):  ?>             
                                  <tr>
                                    <td class="text-center">
                                      <?= $oneByOne['complaint_id']?>
                                    </td>
                                    <td>
                                      <?php 
                                        switch($oneByOne['complaint_source'])
                                        {
                                          case 'web':
                                            echo '<i class="fas fa-laptop mr-1"></i> <span clads>'.$oneByOne['complaint_source'].'</span>';
                                          break;

                                          case 'mobile-app':
                                            echo '<i class="fas fa-mobile-alt mr-1"></i> <span>'.$oneByOne['complaint_source'].'</span>';
                                          break;
                                        }
                                      ?>
                                    </td>
                                    <td><?= $oneByOne['complainant_name']?></td> 
                                    <td><?= $oneByOne['district_name']?></td> 
                                    <td><?= $oneByOne['complaint_entry_timestamp']?></td>
                                    <td><span class="badge text-capitalize" style="color:#fff; background-color: <?= $oneByOne['complaint_status_color'] ?>"><?= $oneByOne['complaint_status_title']?></span></td>
                                    <td>
                                      <a href="admin/print_complaint_detail/<?= $oneByOne['complaint_id'] ?>" class="btn btn-outline-success mr-1" bis_skin_checked="1">Print</a>
                                      <a href="admin/complaint_detail/<?= $oneByOne['complaint_id'] ?>" class="btn btn-outline-success" bis_skin_checked="1">View Detail</a>
                                    </td>
                                  </tr>
                                <?php endforeach; endif;?>
                            </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>