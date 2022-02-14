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
                      <a href="javascript:void(0);" class="btn btn-icon icon-left btn-success custom-success-btn" data-toggle="modal" data-target="#addModel" ><i class="fas fa-plus"></i> Add New District </a>  
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                        <thead class="">
                          <tr>
                            <th>S.#</th>
                            <th>District Name</th>
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
                       <form class="district_update_form" method="post">
                            <div class="form-group">
                            <label>District Name</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="District Name" name="district_name" id="edit_district_name" onkeyup="this.value=this.value.replace(/[^A-Za-z\s]/g,'');" required maxlength="30" >
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
                                      <button type="submit" class="btn btn-primary m-t-15 waves-effect update_button">Update</button>
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
                        <form class="district_add_form" method="post" >
                                <div class="form-group">
                                <label>District Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="district name" name="district_name" onkeyup="this.value=this.value.replace(/[^A-Za-z\s]/g,'');" required maxlength="30">
                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                      <button type="submit" class="btn btn-primary m-t-15 waves-effect add_button">Save</button>
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
      // CURD start      
      $(document).ready(function()
      {
        districts_list();
        function districts_list()
        {
          var html   = '';
          var status = '';
          var count  = 0;
          var dataTable = $("#save-stage").DataTable();
          dataTable.clear().draw();
          $.ajax({
            url: 'admin/districts_list',
            dataType:'json',
            success: function(response)
            { 
              $.each(response, function( index, onByOne ) 
              {
              if(onByOne.district_status == 1)
              {
                status = '<span class="text-success">Active</span>';
              }
              else
              {
                status = '<span class="text-danger">Inactive</span>';
              }
                
                dataTable.row.add([
                  ++count,
                  onByOne.district_name,
                  status,
                  '<a class="fa fa-edit text-info" data-target="#editModel" data-toggle="modal" href="javascript:void(0)" onclick="districts_update('+onByOne.district_id+')"></a>\
                  <a class="fa fa-trash text-danger deleteRecord" href="javascript:void(0)" data-id='+onByOne.district_id+'></a>'
                ]).draw();
              });
            }
          });
        }  // end districts_list

        // add complaint category
      $('.district_add_form').submit(function(e){
            e.preventDefault(); 
            $('.add_button').prop("disabled", true);
            var formData = new FormData( $(".district_add_form")[0] );

            $.ajax({
                        url:"<?php echo base_url(); ?>admin/districts_insert",
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
                                $(".district_add_form")[0].reset();
                                $('#addModel').modal('hide');
                                districts_list();
                                message(1,response);
                            }
                            else
                            { 
                              message(0,response);
                            }
                        }
                    }); 
                    
        });
          // update complaint category
        $('.district_update_form').submit(function(e){
              e.preventDefault(); 
              $('.update_button').prop("disabled", true);
              var formData = new FormData( $(".district_update_form")[0] );

              $.ajax({
                        url:"<?php echo base_url(); ?>admin/district_update",
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
                                $(".district_update_form")[0].reset();
                                $('#editModel').modal('hide');
                                districts_list();
                                message(1,response);
                            }
                            else
                            { 
                              message(0,response);
                            }
                        }
                      }); 
          });


            // delete complaint category
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
                                    url: '<?php echo base_url(); ?>admin/district_delete',   
                                    type:"post",
                                    data:{id:id},
                                    cache:false,
                                    async:false,
                                    success: function (response) 
                                    { 
                                      if(response == 'Record Delete')
                                      { 
                                        message(1,response);
                                        districts_list();
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