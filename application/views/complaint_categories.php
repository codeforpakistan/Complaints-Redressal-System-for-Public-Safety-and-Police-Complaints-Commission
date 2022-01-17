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
                  <button type="button" class="btn btn-primary pull-right fa fa-plus" data-toggle="modal" data-target="#addModel" style="margin-top:-5%;"> Add Category</button>
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
                            <th>Complaint Category Id</th>
                            <th>Complaint Category Name</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <?php if($complaint_categories):?>
                            <tbody>
                            <?php foreach($complaint_categories as $onByOne):?>
                                <tr>
                                    <td><?= $onByOne->complaint_category_id?></td>
                                    <td><?= $onByOne->complaint_category_name?></td>
                                    <td><?= ($onByOne->complaint_category_status == 1)?'<span class="text-success">Active</span>':'<span class="text-danger">Inactive</span>'?></td>
                                    <td>
                                       <a class="fa fa-edit text-info" data-target="#editModel" data-toggle="modal" href="javascript:void(0)" onclick="complaint_categories_update(<?= $onByOne->complaint_category_id?>)"></a>
                                        <a class="fa fa-trash text-danger" onclick="return confirm('Are you sure to deactivate?')" href="<?= base_url('admin/complaint_categories_delete/'.$onByOne->complaint_category_id) ?>"></a>
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
      <div class="modal fade" id="editModel"  role="dialog" aria-labelledby="formModal" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                    <h5 class="modal-title text-white" id="formModal">Update Complaint Category </h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    <!-- body-->
                       <!-- <span class="itStaffUpdateModel"></span> -->
                       <form class="" method="post" action="<?= base_url("admin/complaint_category_update") ?>">
                            <div class="form-group">
                            <label>Complaint Category Name</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Complaint Category Name" name="complaint_category_name" id="edit_complaint_category_name" onkeyup="this.value=this.value.replace(/[^A-Za-z\s]/g,'');" required maxlength="60">
                            </div>
                            </div>
                            <div class="form-group">
                            <label>Status</label>
                            <div class="input-group">
                                  <select class="form-control" name="complaint_category_status" id="edit_complaint_category_status">
                                      <option value="1">Active</option>
                                      <option value="0">Inactive<option>
                                  </select>
                            </div>
                            </div>
                            <input type="hidden" name="complaint_category_id" id="edit_complaint_category_id" >
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
        <div class="modal fade" id="addModel" tabindex="-1" role="dialog" aria-labelledby="formModaladd" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                    <h5 class="modal-title text-white" id="formModaladd">Add Complaint Category </h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    <!-- body-->
                    <form class="" method="post" action="<?= base_url("admin/complaint_category_insert") ?>">
                            <div class="form-group">
                            <label>Complaint Category Name</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Complaint Category Name" name="complaint_category_name" onkeyup="this.value=this.value.replace(/[^A-Za-z\s]/g,'');" required maxlength="60">
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
          function complaint_categories_update(complaint_category_id)		{
			$.ajax({
				url: 'admin/complaint_categories_edit_model/'+complaint_category_id,
        dataType:'json',
				success: function(response){ 
              $('#edit_complaint_category_name').val(response.complaint_category_name);
              $('#edit_complaint_category_id').val(response.complaint_category_id); 
              $('#edit_complaint_category_status option[value="' + response.complaint_category_status + '"]').prop('selected', true);
				}
			});
		}
      </script>