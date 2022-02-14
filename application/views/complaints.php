 <!-- Main Content -->
 <div class="main-content">
        <section class="section">
          <div class="section-body">

            <div class="clearfix"></div>
            <div class="row">
              <div class="col-8">
                <div class="col-12 text-center">
                  <div class="alert alert-warning alert-dismissible show fade">
                      <div class="alert-body">
                          <button class="close" data-dismiss="alert">
                          <span>×</span>
                          </button>
                          To change the complaint status & add a respondent reply click on " view detail " button.
                      </div>
                  </div>
              </div>
              <!-- <a href="javascript:void(0)" class="btn btn-icon icon-left btn-warning custom-success-btn">
                  <strong> Latest 200 Records Only.</strong> For More Records: Visit Reports > Detail Report
              </a> -->
                <!-- <span class="badge text-capitalize alert alert-warning"><strong> Latest 200 Records Only.</strong> For More Records: Visit Reports > Detail Report</span> -->
               </div>
              <div class="col-4 text-right" style="margin-bottom:10px;">
                    <a href="admin/complaint_register" class="btn btn-icon icon-left btn-success custom-success-btn"><i class="fas fa-plus"></i> Register New Complaint</a>  
              </div>
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
                            <th>Status</th>
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
                                    <td style="width: 200px !important;">
                                      <a target="_blank" href="admin/print_complaint_detail/<?= $oneByOne['complaint_id'] ?>" class="btn btn-outline-success mr-1" bis_skin_checked="1">Print</a>
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