 <!-- Main Content -->
 <div class="main-content">
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <div class="col-9 pl-0">
                      <h4><?= $title ?></h4>
                    </div>
                    <div class="col-3 text-right pr-0">
                      <a href="javascript:void(0);" class="btn btn-icon icon-left btn-success custom-success-btn" data-toggle="modal" data-target="#addModel" ><i class="fas fa-plus"></i> Add New Police Station </a>  
                    </div>
                  </div>
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
                            <th>Police Station Id</th>
                            <th>Police Station Name</th>
                            <th>District Name</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <?php if($police_sation):?>
                            <tbody>
                            <?php foreach($police_sation as $onByOne):?>
                                <tr>
                                    <td><?= $onByOne->police_station_id?></td>
                                    <td><?= $onByOne->police_station_name?></td>
                                    <td><?= $onByOne->district_name?></td>
                                    <td><?= ($onByOne->police_station_status == 1)?'<span class="text-success">Active</span>':'<span class="text-danger">Inactive</span>'?></td>
                                    <td>
                                       <a class="fa fa-edit text-info" data-toggle="modal" data-target="#editModel"  href="javascript:void(0)" onclick="police_station_update(<?= $onByOne->police_station_id?>)"></a>
                                        <a class="fa fa-trash text-danger" onclick="return confirm('Are you sure to delete?')" href="<?= base_url('admin/police_station_delete/'.$onByOne->police_station_id) ?>"></a>
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
                    <h5 class="modal-title text-white" id="formModal">Update Police Station </h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    <!-- body-->
                       <!-- <span class="itStaffUpdateModel"></span> -->
                       <form class="" method="post" action="<?= base_url("admin/police_station_update") ?>">
                            <div class="form-group">
                            <label>Police Station Name</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Police Station Name" name="police_station_name" id="edit_police_station_name" onkeyup="this.value=this.value.replace(/[^A-Za-z\s]/g,'');" required maxlength="30" >
                            </div>
                            </div>
                            <div class="form-group">
                                <label>District Name</label>
                                <div class="input-group">
                                   <select class="form-control select2" name="district_id_fk" id="edit_district_id_fk" style="width:100%">
                                        <option disabled value="" selected hidden>Select District</option>
                                        <?php if($district){ foreach($district as $dist){?>
                                           <option value="<?= $dist->district_id?>"><?= $dist->district_name?></option>
                                        <?php } }?>
                                    </select>
                                </div>
                                </div>
                            <div class="form-group">
                            <label>Status</label>
                            <div class="input-group">
                                  <select class="form-control" name="police_station_status" id="edit_police_station_status">
                                      <option value="1">Active</option>
                                      <option value="0">Inactive<option>
                                  </select>
                            </div>
                            </div>
                            <input type="hidden" name="police_station_id" id="edit_police_station_id" >
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
                    <h5 class="modal-title text-white" id="formModaladd">Add Police Sation</h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    <!-- body-->
                        <form class="" method="post" action="<?= base_url("admin/police_station_insert") ?>">
                                <div class="form-group">
                                <label>Police Station Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Police Station Name" name="police_station_name" onkeyup="this.value=this.value.replace(/[^A-Za-z\s]/g,'');" required maxlength="30">
                                </div>
                                </div>
                                <div class="form-group">
                                <label>District Name</label>
                                <div class="input-group">
                                   <select class="form-control select2" name="district_id_fk" id="" style="width:100%">
                                        <option disabled value="" selected hidden>Select District</option>
                                        <?php if($district){ foreach($district as $dist){?>
                                           <option value="<?= $dist->district_id?>"><?= $dist->district_name?></option>
                                        <?php } }?>
                                    </select>
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
        
            function police_station_update(police_station_id)		{
              $.ajax({
                url: 'admin/police_station_edit_model/'+police_station_id,
                dataType: 'json',
                success: function(response){ 
                  $('#edit_police_station_name').val(response.police_station_name);
                  $('#edit_police_station_id').val(response.police_station_id); 
                  $('#edit_police_station_status option[value="' + response.police_station_status + '"]').prop('selected', true);
                  $('#edit_district_id_fk option[value="' + response.district_id_fk + '"]').prop('selected', true);
                  $('#edit_district_id_fk').trigger("change");
                }
              });
            }
      </script>