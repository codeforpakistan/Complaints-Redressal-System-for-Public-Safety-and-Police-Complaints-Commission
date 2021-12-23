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
                  <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#addModel" style="margin-top:-5%;">Add District Admin</button>
                      <!-- start messages --->
                      <div style="text-align: center">
                              <?php if($feedback =$this->session->flashdata('feedback')){
                                $feedback_class =$this->session->flashdata('feedbase_class');  ?>
                                    <div class="row">
                                      <div class="col-lg-12 col-lg-offset-2">
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
                      <table class="table table-striped table-hover" id="tableExport" style="width:100%;">
                        <thead class="">
                          <tr>
                            <th>Staff Name</th>
                            <th>District</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <?php if($district_admin):?>
                            <tbody>
                            <?php foreach($district_admin as $onByOne):?>
                                <tr>
                                    <td><?= $onByOne->user_name?></td>
                                    <td><?= $onByOne->district_name?></td>
                                    <td><?= ($onByOne->user_status == 1)?'<span class="text-success">Active</span>':'<span class="text-danger">Inactive</span>'?></td>
                                    <td>
                                       <a class="fa fa-edit text-info" data-toggle="modal" data-target="#editModel" href="javascript:void(0)" onclick="district_admin_update(<?= $onByOne->user_id?>)"></a>
                                        <a class="fa fa-trash text-danger" onclick="return confirm('Are you sure to delete?')" href="<?= base_url('admin/district_admin_delete/'.$onByOne->user_id) ?>"></a>
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
      <div class="modal fade" id="editModel"  role="dialog" aria-labelledby="formModaladd" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="formModaladd">Update District Admin </h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    <!-- body-->
                        <form class="" method="post" action="<?= base_url("admin/district_admin_update") ?>">
                            
                            <div class="form-group">
                                  <label>District</label>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                      <div class="input-group-text">
                                          <i class="fas fa-user"></i>
                                      </div>
                                      </div>
                                      <select class="form-control select2" id="edit_district_id" name="district_id" style="width:90%" required>
                                      <?php if($district){ foreach($district as $dist){?>
                                           <option value="<?= $dist->district_id?>"><?= $dist->district_name?></option>
                                        <?php } }?>
                                    </select>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label>Username</label>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                      <div class="input-group-text">
                                          <i class="fas fa-user"></i>
                                      </div>
                                      </div>
                                      <input type="text" class="form-control" placeholder="Username" id="edit_user_name" name="user_name" required>
                                  </div>
                                </div>
                                <div class="form-group">
                                <label>Password</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    </div>
                                    <input type="password" class="form-control" placeholder="Password" id="edit_user_password" name="user_password" requored >
                                </div>
                                </div>
                                <div class="form-group">
                                <label>Password</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    </div>
                                      <select class="form-control" name="user_status" id="edit_user_status">
                                          <option value="1" >Active</option>
                                          <option value="0" >Inactive<option>
                                      </select>
                                 </div>
                                </div>
                                <input type="hidden" name="user_id" id="edit_user_id" >
                                <button type="submit" class="btn btn-primary m-t-15 waves-effect">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- add form -->
        <div class="modal fade" id="addModel"  role="dialog" aria-labelledby="formModaladd" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="formModaladd">Add District Admin </h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    <!-- body-->
                        <form class="" method="post" action="<?= base_url("admin/district_admin_insert") ?>">
                            
                            <div class="form-group">
                                  <label>District</label>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                      <div class="input-group-text">
                                          <i class="fas fa-user"></i>
                                      </div>
                                      </div>
                                      <select class="form-control select2" name="district_id" style="width:90%" required>
                                        <option disabled value="" selected hidden>Please Select District</option>
                                        <?php if($district){ foreach($district as $dist){?>
                                           <option value="<?= $dist->district_id?>"><?= $dist->district_name?></option>
                                        <?php } }?>
                                    </select>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label>Username</label>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                      <div class="input-group-text">
                                          <i class="fas fa-user"></i>
                                      </div>
                                      </div>
                                      <input type="text" class="form-control" placeholder="Username" name="user_name" required>
                                  </div>
                                </div>
                                <div class="form-group">
                                <label>Password</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    </div>
                                    <input type="password" class="form-control" placeholder="Password" name="user_password" requored >
                                </div>
                                </div>
                                <button type="submit" class="btn btn-primary m-t-15 waves-effect">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
      <script>
          function district_admin_update(user_id){ 
          $.ajax({
            url: 'admin/district_admin_edit_model/'+user_id,
            dataType: 'json',
            success: function(response){ 
              $('#edit_user_name').val(response.user_name);
              $('#edit_user_password').val(response.user_password);
              $('#edit_user_id').val(response.user_id); 
              $('#edit_user_status option[value="' + response.user_status + '"]').prop('selected', true);
              $('#edit_district_id').val(response.user_district_id_fk);
              $('#edit_district_id').trigger("change");
            }
          });
        }
      </script>