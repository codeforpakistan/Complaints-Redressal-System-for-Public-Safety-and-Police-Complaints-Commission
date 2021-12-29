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
                    <div class="table table-striped">
                       <!-- :::::::::::::::::::::::::::::::::::: list of complaint start :::: -->
                       <div data-label="Employees complaints" class="df-example demo-table">
						<div class="row">
							<div class="col-lg-5 row">
								<div class="col-lg-3">
								<?php /*<select onchange="SearchForm()" name="display_record" id="display_record" class="form-control">
										<!-- <option value="">...Please Select...</option> -->
										<option <?php if($displayLimit=="15"){ ?> selected="selected"  <?php } ?> value="15">15</option>
										<option <?php if($displayLimit=="25"){ ?> selected="selected"  <?php } ?> value="25">25</option>
										<option <?php if($displayLimit=="50"){ ?> selected="selected"  <?php } ?> value="50">50</option>
										<option <?php if($displayLimit=="100"){ ?> selected="selected"  <?php } ?> value="100">100</option>
									</select>
									*/?>
								</div>
								<div class="col-lg-4"></div>
							</div>
							<div class="col-lg-2">
								<form id="checkformData" method="post" action="<?= site_url('/admin/complaints/index'); ?>"> 
										<!-- <input type="hidden" name="displayLimit" id="displayLimit" value="<?= $displayLimit; ?>" class="form-control" />
										<input type="hidden" name="EmployeeName" id="EmployeeName" value="<?= $EmployeeName; ?>" class="form-control" />
										<input type="hidden" name="SignInDate" id="SignInDate" value="<?= $SignInDate; ?>" class="form-control" />
										<input type="hidden" name="SignInTime" id="SignInTime" value="<?= $SignInTime; ?>" class="form-control" />
										<input type="hidden" name="SignOutDate" id="SignOutDate" value="<?= $SignOutDate; ?>" class="form-control" />
										<input type="hidden" name="SignOutTime" id="SignOutTime" value="<?= $SignOutTime; ?>" class="form-control" />
										<input type="hidden" name="emptySignInOut" id="emptySignInOut" value="<?= $emptySignInOut; ?>"> -->
										<!-- <select class="emptySignInOut form-control" name="emptySignInOut" id="emptySignInOut" >
											<option value="">Select Empty</option>
												<option <?php if($emptySignInOut =="emptySignInOut"){ ?> selected="selected"  <?php } ?> value="emptySignInOut">Sign-in / Sign-out</option>
											</select> -->
								</form>
							</div>
							
					
						</div>
						
							
							<table id="example2lol" class="table table-striped" >
								<thead>
									<tr>
										<th>
										<div class="row">
											<div class="col-md-4" style="padding:0">
												<select onchange="SearchForm()" name="display_record" id="display_record" class="form-control">
													<!-- <option value="">...Please Select...</option> -->
													<option <?php if($displayLimit=="15"){ ?> selected="selected"  <?php } ?> value="15">15</option>
													<option <?php if($displayLimit=="25"){ ?> selected="selected"  <?php } ?> value="25">25</option>
													<option <?php if($displayLimit=="50"){ ?> selected="selected"  <?php } ?> value="50">50</option>
													<option <?php if($displayLimit=="100"){ ?> selected="selected"  <?php } ?> value="100">100</option>
												</select>
											</div>
											<div class="col-md-8" style="padding:0">
												<select class="form-control select2" name="district_id" id="" style="width:90%">
													<option disabled value="" selected hidden>Please Select District</option>
													<?php if($district){ foreach($district as $dist){?>
													<option value="<?= $dist->district_id?>"><?= $dist->district_name?></option>
													<?php } }?>
												</select>
											</div>
									</div>
											
										</th>
										<th>
											<!--<input type="time" id="Sign_out_time" value="<?= $SignOutTime; ?>" class="form-control" />-->
											<select class="emptySignInOut form-control"  id="emptySignInOut22">
												<option value="">Select Status</option>
												<option value="">Pending</option>
												<option value="">In-progress</option>
												<option value="">Rejected</option>
											</select>
										</th>
										<th class="text-center" colspan="2">  
											<button type="button" onclick="SearchForm()" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i> Search</button>  
											<button onclick="ClearSearch()" type="button" class="btn btn-primary"><i class="fas fa-sync"></i> Clear</button> 
											<!-- <a href="<?= base_url('admin/complaints/exportIntoExcel')?>" class="btn btn-info" ><i class="fas fa-cloud-download-alt"></i> Export</a>   -->
									</th>
									</tr>
									<tr class="bg-success">
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
                          <td><?= $oneByOne['complainant_name']?></td> 
                          <td><?= $oneByOne['district_name']?></td> 
                          <td><?= $oneByOne['complaint_source']?></td>
                          <td><?= $oneByOne['district_name']?></td>
                          <td>
                              <a type="button" class="btn btn-primary btn-sm" href="admin/status_respondant_rsponse/<?= $oneByOne['complaint_id'] ?>"  style="margin-top:-5%;">Complaint Detailed
                              </a>
                              <!-- <a href="<?php echo site_url('/admin/complaints/updateoneByOneandance/'.$oneByOne['complaint_id']);?>"  ><i class="fa fa-edit"></i></a> -->
                          </td>
                        </tr>
								<?php } }?>
								
								</tbody>
							</table>
				<div class="row show pagination here">
									<div class="col-md-12">
									<?= $pagination; ?>
									</div>
				</div>
					
						</div><!-- df-example -->
							<form id="uploadfilefrm" action="<?= site_url('/admin/complaints/postoneByOneandancefile');?>" method="post" enctype="multipart/form-data">
								<input onchange="chkform()" type="file" id="uploadedfile" name="uploadedfile" accept="application/vnd.ms-excel" style="display: none;" />
							</form>
				</div>
				<style>
				.pagination .btn 
				{
					margin-right:5px
				}
				</style>
                       <!-- :::::::::::::::::::::::::::::::::::: list of complaint end   :::  -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

      <!--- edit form -->
      <div class="modal fade" id="editModel"  role="dialog" aria-labelledby="formModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="formModal">Update Status </h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    <!-- body-->
                       <!-- <span class="itStaffUpdateModel"></span> -->
                       <form class="" method="post" action="<?= base_url("admin/district_update") ?>">
                            <div class="row">
                               <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Source:</label>
                                      <div class="input-group">
                                          <input type="text" class="form-control complainant_source" readonly>
                                      </div>
                                  </div>
                              </div> <!-- end of col-md-6 -->

                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Complaint Status:</label>
                                      <div class="input-group">
                                          <input type="text" class="form-control complainant_status" readonly >
                                      </div>
                                  </div>
                              </div> <!-- end of col-md-6 -->
                            
                           </div> <!-- end of first row -->

                           <div class="row">
                               <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Source:</label>
                                      <div class="input-group">
                                          <input type="text" class="form-control complainant_source" readonly>
                                      </div>
                                  </div>
                              </div> <!-- end of col-md-6 -->

                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Complaint Status:</label>
                                      <div class="input-group">
                                          <input type="text" class="form-control complainant_status" readonly >
                                      </div>
                                  </div>
                              </div> <!-- end of col-md-6 -->
                            
                           </div> <!-- end of second row -->

                            <div class="form-group">
                            <label>Status</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </div>
                                </div>
                                    <select class="form-control" name="district_status" id="edit_district_status">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive<option>
                                    </select>
                            </div>
                            </div>
                            <input type="hidden" name="district_id" id="edit_district_id" >
                            <div class="row">
                                    <div class="col-md-12 text-center">
                                      <button type="submit" class="btn btn-primary m-t-15 waves-effect">Update</button>
                                    </div>
                                </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- add form -->
        <div class="modal fade" id="addModel" tabindex="-1" role="dialog" aria-labelledby="formModaladd" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="formModaladd">Add District</h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    <!-- body-->
                        <form class="" method="post" action="<?= base_url("admin/districts_insert") ?>">
                                <div class="form-group">
                                <label>District Name</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    </div>
                                    <input type="text" class="form-control" placeholder="district name" name="district_name" required>
                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                      <button type="submit" class="btn btn-primary m-t-15 waves-effect">Save</button>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
      <script>
          function districts_update(district_id)		{
			$.ajax({
				url: 'admin/districts_edit_model/'+district_id,
				dataType: 'json',
				success: function(response){ 
					$('#edit_district_name').val(response.district_name);
          $('#edit_district_id').val(response.district_id); 
          $('#edit_district_status option[value="' + response.district_status + '"]').prop('selected', true);
				}
			});
		}
      </script>