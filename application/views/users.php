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
                  <button type="button" class="btn btn-primary pull-right fa fa-plus" data-toggle="modal" data-target="#addModel" style="margin-top:-5%;"></button>
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
                      <table class="table table-striped table-hover" id="table-1" style="width:100%;">
                        <thead class="">
                          <tr>
                            <th>Staff Name</th>
                            <th>District</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <?php if($users):?>
                            <tbody>
                            <?php foreach($users as $onByOne):?>
                                <tr>
                                    <td><?= $onByOne->user_name?></td>
                                    <td><?= $onByOne->district_name?></td>
                                    <td><?= $onByOne->user_role_name?></td>
                                    <td><?= ($onByOne->user_status == 1)?'<span class="text-success">Active</span>':'<span class="text-danger">Inactive</span>'?></td>
                                    <td>
                                       <a class="fa fa-edit text-info" data-toggle="modal" data-target="#editModel" href="javascript:void(0)" onclick="users_update(<?= $onByOne->user_id?>)"></a>
                                        <a class="fa fa-trash text-danger" onclick="return confirm('Are you sure to delete?')" href="<?= base_url('admin/users_delete/'.$onByOne->user_id) ?>"></a>
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
                    <h5 class="modal-title text-white" id="formModaladd">Update User </h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    <!-- body-->
                        <form class="" method="post" action="<?= base_url("admin/users_update") ?>">
                          
                            <div class="form-group">
                              <label>User</label>
                              <div class="input-group">
                                  <div class="input-group-prepend">
                                  <div class="input-group-text">
                                      <i class="fas fa-user"></i>
                                  </div>
                                  </div>
                                  <select class="form-control" id="toggleDistrictSection_edit" name="user_role_id_fk" style="width:90%" required>
                                    <option value="2">IT Staff</option>
                                    <option value="3">District Admin</option>
                                </select>
                              </div>
                            </div>
                            
                            <div class="form-group" id="hideShowDistrictEdit">
                                  <label>District</label>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                      <div class="input-group-text">
                                          <i class="fas fa-user"></i>
                                      </div>
                                      </div>
                                      <select class="form-control select2" id="edit_district_id" name="district_id" style="width:90%">
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
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary m-t-15 waves-effect">Update</button>                                    </div>
                                </div>
                                
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
                    <h5 class="modal-title text-white" id="formModaladd">Add User </h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    <!-- body-->
                        <form class="" method="post" action="<?= base_url("admin/users_insert") ?>">
                            <div class="form-group">
                              <label>User</label>
                              <div class="input-group">
                                  <div class="input-group-prepend">
                                  <div class="input-group-text">
                                      <i class="fas fa-user"></i>
                                  </div>
                                  </div>
                                  <select class="form-control" id="toggleDistrictSection" name="user_role_id_fk" style="width:90%" required>
                                    <option value="2">IT Staff</option>
                                    <option value="3">District Admin</option>
                                </select>
                              </div>
                            </div>
                            <div class="form-group"  id="hideShowDistrict">
                                  <label>District</label>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                      <div class="input-group-text">
                                          <i class="fas fa-user"></i>
                                      </div>
                                      </div>
                                      <select class="form-control select2" name="district_id" id="" style="width:90%">
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
        
        $( document ).ready(function() {
            $('#hideShowDistrict').hide();
            $( "#toggleDistrictSection" ).change(function() {
              var userRole = $("#toggleDistrictSection :selected").val();
              if(userRole == 3)
              {
                $('#hideShowDistrict').show();
              }
              else
              {
                $('#hideShowDistrict').hide();
              }
              
            });
            // edit form
            $( "#toggleDistrictSection_edit" ).change(function() {
              var userRole = $("#toggleDistrictSection_edit :selected").val();
              if(userRole == 3)
              {
                $('#hideShowDistrictEdit').show();
              }
              else
              {
                $('#hideShowDistrictEdit').hide();
                // $('#edit_district_id').val('');
                // $('#edit_district_id').trigger("change");
              }
              
            });
            
        });


          function users_update(user_id){ 
          $.ajax({
            url: 'admin/users_edit_model/'+user_id,
            dataType: 'json',
            success: function(response){ 
              $('#edit_user_name').val(response.user_name);
              $('#edit_user_password').val(response.user_password);
              $('#edit_user_id').val(response.user_id); 
              $('#toggleDistrictSection_edit option[value="' + response.user_role_id_fk + '"]').prop('selected', true);
              $('#edit_user_status option[value="' + response.user_status + '"]').prop('selected', true);
              if(response.user_role_id_fk == 2)
              {
                $('#hideShowDistrictEdit').hide();
                $('#edit_district_id').val('');
                $('#edit_district_id').trigger("change");
              }
              else
              {
                $('#hideShowDistrictEdit').show();
                $('#edit_district_id').val(response.user_district_id_fk);
                $('#edit_district_id').trigger("change"); 
              }
              
            }
          });
        }
      </script>