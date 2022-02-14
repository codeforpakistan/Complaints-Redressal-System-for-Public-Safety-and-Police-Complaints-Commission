 <!-- Main Content -->
 <div class="main-content">
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <div class="col-6 pl-0">
                      <h4> Complaints </h4>
                    </div>
                    <div class="col-6 text-right pr-0">
                      <!-- <a style="margin-right:5px;" href="admin/complaint_register" class="btn btn-icon icon-left btn-success custom-success-btn"><i class="fas fa-plus"></i> Register New Complaint </a>   -->
                      <a href="<?= base_url('admin/exportIntoExcel')?>" class="btn custom-success-btn" style="background: #2eaac7 !important; border:none; color:#fff;"><i class="fas fa-cloud-download-alt"></i> Export</a>
                    </div>  
					<div class="clearfix"></div>
				  </div>
                  <div class="card-body">
                      <!-- start messages --->
                           <div style="text-align: center">
                                  <?php if($feedback =$this->session->flashdata('feedback')){
                                  $feedback_class =$this->session->flashdata('feedbase_class');  ?>
                                  <div class="row">
                                      <div class="col-md-6 offset-3 msg">
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
                       <!-- :::::::::::::::::::::::::::::::::::: list of complaint start :::: -->
								<form id="reset_form" method="post" action="admin/detail_report"> 
										<div class="row" style="padding-left:15px;">
											<div class="col-md-2" style="padding-left:0px;">
												<label>District</label>
												<select class="form-control select2" name="district_id" id="district_id">
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
									</div>
								</form>
						<hr>
						
							<table id="example2lol" class="table table-striped" >
									<thead>
										<tr class="bg-success">
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
											<?php if(!empty($complaints)) { foreach($complaints as $oneByOne){  ?>             
									
												<tr>
												<td class="text-center"><?= $oneByOne['complaint_id']?></td>
												<td>
													<?php 
														switch($oneByOne['complaint_source'])
														{
														case 'web':
															echo '<i class="fas fa-laptop mr-1"></i> <span>'.$oneByOne['complaint_source'].'</span>';
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
												    <a target="_blank" href="admin/print_complaint_detail/<?= $oneByOne['complaint_id'] ?>" class="btn btn-outline-success mr-1" bis_skin_checked="1">Print</a>
													<a href="admin/complaint_detail/<?= $oneByOne['complaint_id'] ?>" class="btn btn-outline-success" bis_skin_checked="1">View Detail</a>
												</td>
												</tr>
										<?php } } else{?>
									
									</tbody>
									<tfoot>
										<tr>
											<td colspan="5" class="text-center">No Record Found</td>
										</tr>
									</tfoot>
								<?php }?>
							</table>
							<?= $pagination; ?>
              			
                       <!-- :::::::::::::::::::::::::::::::::::: list of complaint end   :::  -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

      <script>
      	function reset_form()
      	{
      	    document.getElementById("reset_form").reset();
      		$('#district_id').val("");
      		$('#complaint_status_id').val("");
      		$('#complaint_source').val("");
      		document.getElementById("reset_form").submit();

      	}
      	
      </script>
        
      