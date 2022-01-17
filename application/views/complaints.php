 <!-- Main Content -->
 <div class="main-content">
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Complaint List</h4>
                  </div>
                  <div class="card-body">
                  <a type="button" class="btn btn-primary pull-right fa fa-plus" href="admin/complaint_register" style="margin-top:-5%;"> Register New Complaint</a>
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
								<form id="reset_form" method="post" action="admin/complaints"> 
										<div class="row">
											<div class="col-md-2" style="padding:0">
												<label>District</label>
												<select class="form-control select2" name="district_id" id="district_id">
													<option disabled value="" selected hidden>Please Select</option>
													<?php if($districts){ foreach($districts as $dist){?>
													<option value="<?= $dist->district_id?>" <?= ($district_id == $dist->district_id)? 'selected': '' ?> ><?= $dist->district_name?></option>
													<?php } }?>
												</select>
											</div>
											<div class="col-md-2" style="padding:0">
												<label>Status</label>
												<select class="form-control select2" name="complaint_status_id" id="complaint_status_id">
													<option disabled value="" selected hidden>Please Select</option>
													<?php if($complaint_statuses){ foreach($complaint_statuses as $status){?>
													<option value="<?= $status->complaint_status_id ?>" <?= ($complaint_status_id == $status->complaint_status_id)? 'selected': '' ?> ><?= $status->complaint_status_title?></option>
													<?php } }?>
												</select>
											</div>
											<div class="col-md-2" style="padding:0">
												<label>From</label>
												<input type="date" name="from_date" class="form-control" value="<?= $from_date ?>" />
											</div>
											<div class="col-md-2" style="padding:0">
												<label>To</label>
												<input type="date" name="to_date" class="form-control" value="<?= $to_date ?>" />
											</div>
											<div class="col-md-2" style="padding:0">
												<label>Source</label>
												<select class="form-control" name="complaint_source" id="complaint_source">
													<option value="All" <?= ($complaint_source == "All")? 'selected': '' ?> >ALL</option>
													<option value="admin" <?= ($complaint_source == "admin")? 'selected': '' ?>>Admin</option>
													<option value="complainant" <?= ($complaint_source == "complainant")? 'selected': '' ?> >Complainant</option>
												</select>
											</div>
											<div class="col-md-1" style="padding:0">
												<button type="submit" class="btn btn-success form-control" style="margin-top: 33%;"><i class="fa fa-search" aria-hidden="true"></i></button> 
											</div>
											<div class="col-md-1" style="padding:0"> 
											    <button type="button" onclick="reset_form()" class="btn btn-primary form-control" style="margin-top: 33%;"><i class="fas fa-sync"></i></button>
											</div>
									</div>
								</form>
						<hr>
						
							<table id="example2lol" class="table table-striped" >
									<thead>
										<tr class="bg-success">
											<th>Complaint Date</th>
											<th>Citizen Name</th>
											<th>District</th>
											<th>Source</th>
											<th>Status </th>
											<th>Actions</th>
										</tr>
									</thead>
									<tbody>
											<?php if(!empty($complaints)) { foreach($complaints as $oneByOne){  ?>             
									
												<tr>
												<td><?= $oneByOne['complaint_entry_timestamp']?></td>
												<td><?= $oneByOne['complainant_name']?></td> 
												<td><?= $oneByOne['district_name']?></td> 
												<td><?= $oneByOne['complaint_source']?></td>
												<td><?= $oneByOne['complaint_status_title']?></td>
												<td>
													<a type="button" class="btn btn-primary btn-sm" href="admin/complaint_detail/<?= $oneByOne['complaint_id'] ?>"  style="margin-top:-5%;">Complaint Detailed
													</a>
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
								<div class="row show pagination here">
									<div class="col-md-12">
									   <?= $pagination; ?>
									   <a href="<?= base_url('admin/exportIntoExcel')?>" class="btn btn-info pull-right" style="margin-top: -48px;" ><i class="fas fa-cloud-download-alt"></i> Export</a>
									</div>
								</div>
              			
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
        
      