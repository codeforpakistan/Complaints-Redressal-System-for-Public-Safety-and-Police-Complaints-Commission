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
                      <a href="javascript:void(0);" class="btn btn-icon icon-left btn-success custom-success-btn" data-toggle="modal" data-target="#addModel" ><i class="fas fa-plus"></i> Add New User </a>  
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                        <thead>
                          <tr>
                            <th>S.#</th>
                            <th>User Name</th>
                            <th>District</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                            <tbody>
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

      <!--- edit form -->
      <div class="modal fade" id="editModel"  role="dialog" aria-labelledby="formModaladd" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                    <h5 class="modal-title text-white" id="formModaladd">Update User </h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    <!-- body-->
                        <form class="users_update_form" method="post">
                          
                            <div class="form-group">
                              <label>User</label>
                              <div class="input-group">
                                  <select class="form-control" id="toggleDistrictSection_edit" name="user_role_id_fk" style="width:1000%" required>
                                    <option value="2">IT Staff</option>
                                    <option value="3">District Admin</option>
                                </select>
                              </div>
                            </div>
                            
                            <div class="form-group" id="hideShowDistrictEdit">
                                  <label>District</label>
                                  <div class="input-group">
                                      <select class="form-control select2" id="edit_district_id" name="district_id" style="width:1000%">
                                      <?php if($district){ foreach($district as $dist){?>
                                           <option value="<?= $dist->district_id?>"><?= $dist->district_name?></option>
                                        <?php } }?>
                                    </select>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label>Username</label>
                                  <div class="input-group">
                                      <input type="text" class="form-control" placeholder="Username" id="edit_user_name" name="user_name" onkeyup="this.value=this.value.replace(/[^A-Za-z0-9\s]/g,'');" required maxlength="30">
                                  </div>
                                </div>
                                <div class="form-group">
                                <label>Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" placeholder="Password" id="edit_user_password" name="user_password" requored >
                                </div>
                                </div>
                                <div class="form-group">
                                <label>Password</label>
                                <div class="input-group">
                                      <select class="form-control" name="user_status" id="edit_user_status">
                                          <option value="1" >Active</option>
                                          <option value="0" >Inactive<option>
                                      </select>
                                 </div>
                                </div>
                                <input type="hidden" name="user_id" id="edit_user_id" >
                                <div class="row">
                                    <div class="col-md-12 text-right">
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
                    <div class="modal-header bg-success">
                    <h5 class="modal-title text-white" id="formModaladd">Add User </h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    <!-- body-->
                        <form class="users_add_form" method="post">
                            <div class="form-group">
                              <label>User Role</label>
                              <div class="input-group">
                                  <select class="form-control" id="toggleDistrictSection" name="user_role_id_fk" style="width:90%" required>
                                    <option value="2">IT Staff</option>
                                    <option value="3">District Admin</option>
                                  </select>
                              </div>
                            </div>
                            <div class="form-group"  id="hideShowDistrict">
                                  <label>District</label>
                                  <div class="input-group">
                                      <select class="form-control select2" name="district_id" id="" style="width:100%">
                                        <option disabled value="" selected hidden>Select District</option>
                                        <?php if($district){ foreach($district as $dist){?>
                                           <option value="<?= $dist->district_id?>"><?= $dist->district_name?></option>
                                        <?php } }?>
                                    </select>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label>Username</label>
                                  <div class="input-group">
                                      <input type="text" class="form-control" placeholder="Username" name="user_name" onkeyup="this.value=this.value.replace(/[^A-Za-z0-9\s]/g,'');" required maxlength="30">
                                  </div>
                                </div>
                                <div class="form-group">
                                <label>Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" placeholder="Password" name="user_password" requored >
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
            //   $('#edit_user_password').val(response.user_password);
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
       // CURD Start 
      $(document).ready(function()
      {
        users_list();
        function users_list()
        {
          var html   = '';
          var status = '';
          var count  = 0;
          var dataTable = $("#save-stage").DataTable();
          dataTable.clear().draw();
          $.ajax({
            url: 'admin/users_list',
            dataType:'json',
            success: function(response)
            { 
              $.each(response, function( index, onByOne ) 
              {
              if(onByOne.user_status == 1)
              {
                status = '<span class="text-success">Active</span>';
              }
              else
              {
                status = '<span class="text-danger">Inactive</span>';
              }
                
                dataTable.row.add([
                  ++count,
                  onByOne.user_name,
                  onByOne.district_name,
                  onByOne.user_role_name,
                  status,
                  '<a class="fa fa-edit text-info" data-target="#editModel" data-toggle="modal" href="javascript:void(0)" onclick="users_update('+onByOne.user_id+')"></a>\
                  <a class="fa fa-trash text-danger deleteRecord" href="javascript:void(0)" data-id='+onByOne.user_id+'></a>'
                ]).draw();
              });
            }
          });
        }  // end users_list

        // add users
      $('.users_add_form').submit(function(e){
            e.preventDefault(); 
            $('.add_button').prop("disabled", true);
            var formData = new FormData( $(".users_add_form")[0] );

            $.ajax({
                        url:"<?php echo base_url(); ?>admin/users_insert",
                        type:"post",
                        data:formData,
                        processData:false,
                        contentType:false,
                        cache:false,
                        async:false,
                        success: function(response)
                        { 
                          $('.add_button').prop("disabled", false);
                            if(response == 'Record Add')
                            {
                                $(".users_add_form")[0].reset();
                                $('#addModel').modal('hide');
                                message(1,response);
                                users_list();
                                $('#hideShowDistrict').hide();
                            }
                            else
                            { 
                              message(0,response);
                            }
                        }
                    }); 
                    
        });
          // update users
        $('.users_update_form').submit(function(e){
              e.preventDefault(); 
              $('.update_button').prop("disabled", true);
              var formData = new FormData( $(".users_update_form")[0] );

              $.ajax({
                        url:"<?php echo base_url(); ?>admin/users_update",
                        type:"post",
                        data:formData,
                        processData:false,
                        contentType:false,
                        cache:false,
                        async:false,
                        success: function(response)
                        { 
                            if(response == 'Recored Update')
                            {
                              $('.update_button').prop("disabled", false);
                                $(".users_update_form")[0].reset();
                                $('#editModel').modal('hide');
                                users_list();
                                message(1,response);
                            }
                            else
                            { 
                              message(0,response);
                            }
                        }
                      }); 
          });


            // delete users
          $(document).on("click", ".deleteRecord", function() 
          {
            var id = $(this).attr("data-id");
              $.confirm({
                        title: '<span style="font-weight: bold;color: #C0392B;"> Conform!</span>',
                        content: '<span style="font-weight: bold;color: black;">Do You Want To Do This ?</span>',
                        type: 'red',
                        icon: 'fa fa-trash',
                        typeAnimated: true,
                        autoOpen: false,
                        position: {my: "center top", at:"center top", of: window },
                        buttons: {
                            confirm: function () 
                            {
                                $.ajax({
                                    url: '<?php echo base_url(); ?>admin/users_delete',   
                                    type:"post",
                                    data:{id:id},
                                    cache:false,
                                    async:false,
                                    success: function (response) 
                                    { 
                                      if(response == 'Record Delete')
                                      { 
                                        message(1,response);
                                        users_list();
                                      }
                                      else
                                      { 
                                        message(0,response);
                                      }
                                    }
                                });
                                $(id).animate({
                                      opacity: 0
                                  }, 500, function() {
                                      $(this).hide(); // applies display: none; to the element .panel
                                  });
                    
                    
                            },
                            cancel: function () 
                            {
                    
                            }
                        }
                     });
          }); // end of delete
          

         
      });
      </script>