 <!-- Main Content -->
 <div class="main-content">
        <section class="section">
          <div class="section-body">

            <div class="clearfix"></div>

              <!--------------- Data Filteration Form ---------------->
							
              <div class="card">

                  <div class="card-header">
                    <div class="col-6 pl-0">
                      <h4> View & Filter Complaints </h4>
                    </div>
                  </div>

                  <div class="card-body">

							      <form id="reset_form" method="post" action="admin/detail_report" class="detail_report_filter_form"> 
								    
                      <div class="row" style="padding-left:15px;">
                        
                          <div class="col-md-2" style="padding-left:0px;">
                              <label>District</label>
                              <?php 
                                if($this->session->userdata('user_role_id_fk') == 3)
                                {
                                  $district_class = 'disabled';
                                  $district_id = $this->session->userdata('user_district_id_fk');
                                }
                                else
                                {
                                  $district_class = '';
                                  $district_id = 0;  
                                }
                              ?>
                              <select class="form-control select2" name="district_id" id="district_id" <?= $district_class?>>
                                <option disabled value="" selected hidden>Please Select</option>
                                
                                <?php if($districts){ foreach($districts as $dist){?>
                                    <option value="<?= $dist->district_id?>" <?= ($district_id == $dist->district_id)? 'selected': '' ?> ><?= $dist->district_name?></option>
                                <?php } }?>
                              </select>
                          </div>
                                            
                          <div class="col-md-2" style="padding-left:0px;">
                            <label>Status</label>
                            <select class="form-control select2" name="complaint_status_id" id="complaint_status_id">
                              <option disabled value="" selected hidden>Please Select</option>
                              <?php if($complaint_statuses){ foreach($complaint_statuses as $status){?>
                                <option value="<?= $status->complaint_status_id ?>" <?= ($complaint_status_id == $status->complaint_status_id)? 'selected': '' ?> ><?= $status->complaint_status_title?></option>
                              <?php } }?>
                            </select>
                          </div>
                          
                          <div class="col-md-2" style="padding-left:0px;">
                            <label>From</label>
                            <input type="date" name="from_date" class="form-control" value="<?= $from_date ?>" />
                          </div>
                              
                          <div class="col-md-2" style="padding-left:0px;">
                            <label>To</label>
                            <input type="date" name="to_date" class="form-control" value="<?= $to_date ?>" />
                          </div>
                          
                          <div class="col-md-2" style="padding-left:0px;">
                            <label>Source</label>
                            <select class="form-control" name="complaint_source" id="complaint_source">
                              <option value="All" <?= ($complaint_source == "All")? 'selected': '' ?> >ALL</option>
                              <option value="web" <?= ($complaint_source == "web")? 'selected': '' ?>>Web</option>
                              <option value="mobile-app" <?= ($complaint_source == "mobile-app")? 'selected': '' ?> >Mobile-App</option>
                            </select>
                          </div>
                          
                          <div class="col-md-1" style="padding-left:0px;">
                            <button type="submit" class="btn btn-success form-control" style="margin-top: 33%;"><i class="fa fa-search" aria-hidden="true"></i></button> 
                          </div>
                          
                          <div class="col-md-1" style="padding-left:0px;"> 
                            <button type="button" onclick="reset_form()" class="btn btn-primary form-control" style="margin-top: 33%;"><i class="fas fa-sync"></i></button>
                          </div>
                      
                      </div> <!-- row -->
                    
                      <div class="row" style="padding-left:15px; padding-top:15px;">
                        
                          <div class="col-md-5" style="padding-left:0px;">
                              <input type="text" name="search_text" class="form-control" placeholder="Type Complaint-Id or Applicant-name to search..">
                          </div>
                          
                          <div class="col-md-3"> </div>
                          
                          <div class="col-md-2" style="padding-right:0px;">
                              <select class="form-control select2" name="sort_by_column" id="">
                                <option disabled value="" selected hidden>Sort By Column</option>
                                <option value="complaint_id"> Complaint Id </option>
                                <option value="complainant_name"> Applicant Name </option>
                                <option value="complaint_status_id_fk"> Status </option>
                                <option value="complaint_source"> Source </option>
                              </select>
                          </div>

                          <div class="col-md-2">
                              <select class="form-control select2" name="sort_by_value" id="">
                            <option value="asc"> Ascending </option>
                            <option value="desc"> Descending </option>
                          </select>
                          </div>
                              
                      </div>
                    
                    </form>

            <hr/>

            <!--------------- Data Filteration Form End ---------------->
              

            <div class="row">

              <!-- <div class="col-8">
                <p style="">To change the complaint status & add a respondent reply click on " view detail " button</p>
               </div> -->
              <!-- <div class="col-4 text-right" style="margin-bottom:10px;">
                    <a href="admin/complaint_register" class="btn btn-icon icon-left btn-success custom-success-btn"><i class="fas fa-plus"></i> Register New Complaint</a>  
              </div> -->

              <div class="col-12">
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
                        <tr class="bg-success">
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

            
            </div> <!-- card-body -->
            </div> <!-- card -->

          </div> <!-- section-body -->
        </section>
      </div>