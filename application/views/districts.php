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
                  <button type="button" class="btn btn-primary pull-right fa fa-plus" data-toggle="modal" data-target="#addModel" style="margin-top:-5%;"> Add District</button>
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
                            <th>District Name</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <?php if($districts):?>
                            <tbody>
                            <?php foreach($districts as $onByOne):?>
                                <tr>
                                    <td><?= $onByOne->district_name?></td>
                                    <td><?= ($onByOne->district_status == 1)?'<span class="text-success">Active</span>':'<span class="text-danger">Inactive</span>'?></td>
                                    <td>
                                       <a class="fa fa-edit text-info" data-toggle="modal" data-target="#editModel"  href="javascript:void(0)" onclick="districts_update(<?= $onByOne->district_id?>)"></a>
                                        <!-- <a class="fa fa-trash text-danger" onclick="return confirm('Are you sure to delete?')" href="<?= base_url('admin/districts_delete/'.$onByOne->district_id) ?>"></a> -->
                                    </td>
                                </tr>
                            <?php endforeach;?>
                            </tbody>
                        <?php endif; ?>
                      </table>
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
                    <div class="modal-header bg-success">
                    <h5 class="modal-title text-white" id="formModal">Update district </h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    <!-- body-->
                       <!-- <span class="itStaffUpdateModel"></span> -->
                       <form class="" method="post" action="<?= base_url("admin/district_update") ?>">
                            <div class="form-group">
                            <label>District Name</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="District Name" name="district_name" id="edit_district_name" required >
                            </div>
                            </div>
                            <div class="form-group">
                            <label>Status</label>
                            <div class="input-group">
                                  <select class="form-control" name="district_status" id="edit_district_status">
                                      <option value="1">Active</option>
                                      <option value="0">Inactive<option>
                                  </select>
                            </div>
                            </div>
                            <input type="hidden" name="district_id" id="edit_district_id" >
                            <div class="row">
                                    <div class="col-md-12 text-right">
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
                    <div class="modal-header bg-success">
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
                                    <input type="text" class="form-control" placeholder="district name" name="district_name" required>
                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
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