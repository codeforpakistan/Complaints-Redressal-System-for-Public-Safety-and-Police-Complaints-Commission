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
                    <div class="table-responsive">
                      <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                        <thead class="">
                          <tr>
                            <th>S.#</th>
                            <th>Police Station Name</th>
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
                    <h5 class="modal-title text-white" id="formModal">Update Police Station </h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    <!-- body-->
                       <form class="police_station_update_form" method="post">
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
                    <h5 class="modal-title text-white" id="formModaladd">Add Police Sation</h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    <!-- body-->
                        <form class="police_station_add_form" method="post">
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
                                      <button type="submit" class="btn btn-primary m-t-15 waves-effect add_button">Save</button>
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
      $(document).ready(function()
      {
        police_station_list();
        function police_station_list()
        {
          var html   = '';
          var status = '';
          var count  = 0;
          var dataTable = $("#save-stage").DataTable();
          dataTable.clear().draw();
          $.ajax({
            url: 'admin/police_station_list',
            dataType:'json',
            success: function(response)
            { 
              $.each(response, function( index, onByOne ) 
              {
              if(onByOne.police_station_status == 1)
              {
                status = '<span class="text-success">Active</span>';
              }
              else
              {
                status = '<span class="text-danger">Inactive</span>';
              }
                
                dataTable.row.add([
                  ++count,
                  onByOne.police_station_name,
                  onByOne.district_name,
                  status,
                  '<a class="fa fa-edit text-info" data-target="#editModel" data-toggle="modal" href="javascript:void(0)" onclick="police_station_update('+onByOne.police_station_id+')"></a>\
                  <a class="fa fa-trash text-danger deleteRecord" href="javascript:void(0)" data-id='+onByOne.police_station_id+'></a>'
                ]).draw();
              });
            }
          });
        }  // end police_station_list

        // add Police Station
      $('.police_station_add_form').submit(function(e){
            e.preventDefault(); 
            $('.add_button').prop("disabled", true);
            var formData = new FormData( $(".police_station_add_form")[0] );

            $.ajax({
                        url:"<?php echo base_url(); ?>admin/police_station_insert",
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
                                $(".police_station_add_form")[0].reset();
                                $('#addModel').modal('hide');
                                police_station_list();
                                message(1,response);
                            }
                            else
                            { 
                              message(0,response);
                            }
                        }
                    }); 
                    
        });
          // update police Station
        $('.police_station_update_form').submit(function(e){
              e.preventDefault(); 
              $('.update_button').prop("disabled", true);
              var formData = new FormData( $(".police_station_update_form")[0] );

              $.ajax({
                        url:"<?php echo base_url(); ?>admin/police_station_update",
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
                                $(".police_station_update_form")[0].reset();
                                $('#editModel').modal('hide');
                                police_station_list();
                                message(1,response);
                            }
                            else
                            { 
                              message(0,response);
                            }
                        }
                      }); 
          });


            // delete Police Station
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
                                    url: '<?php echo base_url(); ?>admin/police_station_delete',   
                                    type:"post",
                                    data:{id:id},
                                    cache:false,
                                    async:false,
                                    success: function (response) 
                                    { 
                                      if(response == 'Record Delete')
                                      { 
                                        message(1,response);
                                        police_station_list();
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