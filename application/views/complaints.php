 <!-- Main Content -->
 <div class="main-content">
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4><?= $title ?></h4>
                  </div>
                  <div class="card-body">
                  <!-- <button type="button" class="btn btn-primary pull-right fa fa-plus" data-toggle="modal" data-target="#addModel" style="margin-top:-5%;"> Add District</button> -->
                  <a type="button" class="btn btn-primary pull-right fa fa-plus" href="admin/complaint_register" style="margin-top:-5%;"> Register New Complaint</a>
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
                            <th>Applicant Name</th>
                            <th>District</th>
                            <th>Complaint Date</th>
                            <th>Source</th>
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
                                    <td><?= $oneByOne['complainant_name']?></td> 
                                    <td><?= $oneByOne['district_name']?></td> 
                                    <td><?= $oneByOne['complaint_entry_timestamp']?></td>
                                    <td>
                                      <?php 
                                        switch($oneByOne['complaint_source'])
                                        {
                                          case 'admin':
                                            echo '<i class="fas fa-laptop mr-1"></i> <span clads>'.$oneByOne['complaint_source'].'</span>';
                                          break;

                                          case 'complainant':
                                            echo '<i class="fas fa-user-alt mr-1"></i> <span>'.$oneByOne['complaint_source'].'</span>';
                                          break;
                                        }
                                      ?>
                                    </td>
                                    <td><span class="badge text-capitalize" style="color:#fff; background-color: <?= $oneByOne['complaint_status_color'] ?>"><?= $oneByOne['complaint_status_title']?></span></td>
                                    <td>
                                      <a href="admin/complaint_detail/<?= $oneByOne['complaint_id'] ?>" class="btn btn-outline-success mr-1" bis_skin_checked="1">Complaint Detail</a>
                                      <a style="background-color:#54ca68 !important; color:#fff; border:none;" href="admin/print_complaint_detail/<?= $oneByOne['complaint_id'] ?>" class="btn btn-outline-success" bis_skin_checked="1">Print</a>
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