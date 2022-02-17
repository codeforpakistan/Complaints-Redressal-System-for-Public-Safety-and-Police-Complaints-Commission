
 <!-- Main Content -->
 <div class="main-content">
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <div class="col-md-8"><h4><?= $title ?></h4></div>
                    <div class="col-md-4 text-right"><p style="margin-bottom:0px;">Source: <span class="text-capitalize"><?= $complaint_detail[0]->complaint_source ?><span></p></div>
                  </div>
                  <div class="card-body">
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
                    <!-- <form class="" method="post" action="<?= base_url("admin/complaint_register_form") ?>" enctype="multipart/form-data"> -->
                     <?php if($complaint_detail) foreach($complaint_detail as $oneByOne)  ?>
                     <div id="editor"></div>
                    <div id="printSection">
                        <div class="row" >
                        <div class="col-md-4">
                                <div class="form-group">
                                    <label>Name:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control"  value="<?= $oneByOne->complainant_name?>" readonly >
                                    </div>
                                </div>
                            </div> <!-- end of col-md-4 -->

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Father/Husband Name:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="<?= $oneByOne->complainant_guardian_name?>" readonly >
                                    </div>
                                </div>
                            </div> <!-- end of col-md-4 -->

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Contact No:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control"  value="<?= $oneByOne->complainant_contact ?>"  readonly >
                                    </div>
                                </div>
                            </div> <!-- end of col-md-4 -->
                            
                        </div> <!-- end of row -->
                        
                        <div class="row"> <!-- second row -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CNIC:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="<?= $oneByOne->complainant_cnic ?>" readonly>
                                    </div>
                                </div>
                            </div> <!-- end of col-md-4 -->

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Gender:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="<?= $oneByOne->complainant_gender ?>" readonly>
                                        </div>
                                    </div>
                                </div> <!-- end of col-md-4 -->

                                <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email:</label>
                                    <div class="input-group">
                                        <input type="email" class="form-control" value="<?= $oneByOne->complainant_email?>" readonly>
                                    </div>
                                </div>
                            </div> <!-- end of col-md-4 -->
                        </div> <!-- end of row -->

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Home District:</label>
                                    <div class="input-group">
                                        <select class="form-control" style="width:90%" readonly disabled>
                                                  <?php if($district){ foreach($district as $home_dist){?>
                                               <option <?= ($home_dist->district_id == $oneByOne->complainant_council_id_fk)?'selected' :''?> ><?= $home_dist->district_name?></option>
                                                 <?php } }?>
                                           </select>
                                    </div>
                                </div>
                            </div> <!-- end of col-md-4 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Union Council:</label>
                                    <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $oneByOne->complainant_council ?>" readonly>
                                        <!-- <select class="form-control" style="width:90%" readonly disabled>
                                                  <?php if($district_councils){ foreach($district_councils as $district_councils){?>
                                               <option <?= ($district_councils->district_council_id == $oneByOne->complainant_council_id_fk)?'selected' :''?> ><?= $district_councils->district_council_name?></option>
                                                 <?php } }?>
                                           </select> -->
                                    </div>
                                </div>
                            </div> <!-- end of col-md-4 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Complaint ID:</label>
                                    <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $oneByOne->complaint_id ?>" readonly>
                                    </div>
                                </div>
                            </div> <!-- end of col-md-4 -->
                        </div>
                            <div class="row">

                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label>Full Address:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="<?= $oneByOne->complainant_address?>" readonly>
                                        </div>
                                    </div>
                                </div> <!-- end of col-md-12 -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Complaint Source:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="<?= $oneByOne->complaint_source?>" readonly>
                                        </div>
                                    </div>
                                </div> <!-- end of col-md-12 -->

                            </div>  <!-- end row -->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Complaint Type:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="<?= $oneByOne->complaint_category_name?>" readonly>
                                        </div>
                                    </div>
                                </div> <!-- end of col-md-6 -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Complaint Against District:</label>
                                        <div class="input-group">
                                        <input type="text" class="form-control" value="<?= $oneByOne->complaint_council ?>" readonly>
                                            <!-- <select class="form-control" style="width:90%" readonly disabled>
                                                  <?php if($district){ foreach($district as $home_dist){?>
                                               <option <?= ($home_dist->district_id == $oneByOne->complaint_council_id_fk)?'selected' :''?> ><?= $home_dist->district_name?></option>
                                                 <?php } }?>
                                           </select> -->
                                        </div>
                                    </div>
                                </div> <!-- end of col-md-6 -->

                            </div> <!-- end of row -->

                         
                        
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Complaint Details:</label>
                                    <div class="input-group">
                                        <textarea class="form-control"  readonly><?= $oneByOne->complaint_detail?></textarea>
                                    </div>
                                </div>
                            </div> <!-- end of col-md-12 -->                         
                        </div>  <!-- end row --> 

                        <div class="row">

                            <label>Attachments:</label>
                            <?php if($complaint_attachment){ foreach($complaint_attachment as $attachments){ ?>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                           <center> 
                                                <a href="<?= $attachments->complaint_attachment_file_path?>" target="_blank">view</a>
                                                <a href="<?= $attachments->complaint_attachment_file_path?>"  download style="color:blue">Download</a>
                                            </center>
                                            <input type="file" class="form-control dropify" data-height="100" data-default-file="<?= $attachments->complaint_attachment_file_path?>" data-show-remove="false" data-show-loader="false">
                                        </div>       
                                    </div> <!-- end of col-md-12 --> 
                                       
                                <?php } }?>                        
                        </div>  <!-- end row --> 


                            <!-- <div class="row">
                                    <div class="col-md-12 text-right">
                                      <button type="submit" class="btn btn-primary m-t-15 waves-effect">Save</button>
                                    </div>
                                </div> -->   
                        
                        <!------ ::::::::::::::::::::::::: start Stats and respondant replay form --- :::::::::::::::::::::::::::-->
                        <div class="row col-12 bg-success text-white pt-2">
                            <div class="col-10">
                              <h3>Change Status / Respondent Reply</h3>  
                            </div>
                             <div class="col-2">
                                 <button class="show_hide_form btn btn-primary pull-right ">Hide Form</button>
                             </div>
                             
                        </div>
                        <div class="mt-5">
                            
                        </div>
                        <div class="status_response_form">
                        <form class="comp_remarks_add_form" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <div class="alert alert-warning alert-dismissible show fade">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert">
                                            <span>×</span>
                                            </button>
                                            For respondents, click on respondent reply form status dropdown.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 asssingGridFour">
                                    <div class="form-group">
                                        <label>Status:</label>
                                        <div class="input-group">
                                            <select class="form-control" name="complaint_status_id_fk" id="complaint_status_id_fk" required>
                                                <option  selected value="0">Select Status</option>
                                                <?php if($complaint_statuses){ foreach($complaint_statuses as $statusess){?>
                                                        <option value="<?= $statusess->complaint_status_id?>"><?= $statusess->complaint_status_title?></option>
                                                    <?php } }?>
                                            </select>
                                        </div>
                                    </div>
                                </div> <!-- end of col-md-4 -->

                                <div class="col-md-4" style="display: none;" id="respondatsHideShow">
                                    <div class="form-group">
                                        <label>Respondent:</label>
                                        <div class="input-group">
                                            <select class="form-control" name="respondent_id_fk" id="respondent_id_fk">
                                                <option  selected value="0">Select Respondent</option>
                                                <?php if($respondats){ foreach($respondats as $respondats){?>
                                                        <option value="<?= $respondats->respondent_id?>"><?= $respondats->respondent_title?></option>
                                                    <?php } }?>
                                            </select>
                                        </div>
                                    </div>
                                </div> <!-- end of col-md-4 -->

                                <div class="col-md-4" style="display:none;">
                                    <div class="form-group">
                                        <label>date:</label>
                                        <div class="input-group">
                                            <input type="date" class="form-control"  name="complaint_remarks_timestamp" id="complaint_remarks_timestamp" >
                                        </div>
                                    </div>
                                </div> <!-- end of col-md-4 -->
                                    
                            </div> <!-- end of first row -->
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label>Remarks:</label>
                                        <div class="input-group">
                                            <textarea class="" name="complaint_remarks_detail" id="complaint_remarks_detail" rows="5" cols="300" required></textarea>
                                        </div>
                                    </div>
                                </div> <!-- end of col-md-9 -->
                                <div class="col-md-3">
                                        <div class="form-group ">
                                            <label>Attachment:</label>
                                            <input type="file" name="attachment"  class="form-control dropify" data-height="100"  data-max-file-size="200M" data-allowed-file-extensions="pdf png jpg jpeg doc docx csv xlsx mp4 mpeg mp3" >
                                        </div> 
                                </div> <!-- end of col-md-3 -->                         
                            </div>  <!-- end row -->
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <input type="hidden" name="complaint_id" value="<?= $complaint_id ?>">
                                    <button type="submit" class="btn btn-primary m-t-15 waves-effect add_button">Save</button>
                                </div>
                            </div>  
                        </form>
                        <hr>
                    </div>
                          <div class="showRemarks"></div>
                            
                  </div>
                 </div> <!---pdf section end -->
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
<script>
    // $(document).ready(function(){
    //     $('.dropify').dropify();
    // });
    // $('#mydropzone').click(function()
    // {
    //     $('#attachment_input').trigger('click');
    // }); 
    //  toggle form
    $(".show_hide_form").click(function()
    { 
        if($(this).text()=="Show form")
        {
            $(this).text("Hide form");
        } else 
        {
            $(this).text("Show form");
        }
        $(".status_response_form").toggle();
    });
    
    // hide show respondant
    $('#complaint_status_id_fk').on('change', function() {
     $respondant_id = this.value;
         if($respondant_id == 8)
         {
            $('.asssingGridFour').removeClass('col-md-8');
            $('#respondatsHideShow').show();
            $('.asssingGridFour').addClass('col-md-4');
         }
         else
         {  
            $('.asssingGridFour').removeClass('col-md-4');
            $('.asssingGridFour').addClass('col-md-8');
            $('#respondatsHideShow').hide();
         }
    });
    

 // CURD start      
 $(document).ready(function()
{ $('.dropify').dropify();
        complaint_remarks_list();
        function complaint_remarks_list()
        {
        var complaint_id = <?= $complaint_id?>;
          var html   = '';
         $('.showRemarks').html('');
          var response_by = '';
          $.ajax({
            url: 'admin/complaints_remarks_list/'+complaint_id,
            dataType:'json',
            success: function(response)
            { 
              $.each(response, function( index, oneByOne ) 
              {
                if(oneByOne.respondent_title != '')
                {
                    response_by = 'Admin';
                }
                else
                {
                    response_by = oneByOne.respondent_title;
                }
                
                  html +='\
                            <div class="card card-success">\
                                    <div class="card-header">\
                                    <div class="row col-md-12">\
                                        <div class="col-md-4">\
                                            <p><strong>Remarks From: </strong>'+response_by+'</p>\
                                        </div>\
                                        <div class="col-md-4">\
                                            <p><strong>Status: </strong>'+ oneByOne.complaint_status_title +'</p>\
                                        </div>\
                                        <div class="col-md-4">\
                                            <p><strong>Date: </strong>'+ oneByOne.complaint_remarks_timestamp+'</p>\
                                        </div>\
                                        </div>\
                                    </div>\
                                    <div class="card-body">\
                                    <p>'+oneByOne.complaint_remarks_detail+'</p>';
                                    if(oneByOne.attachment != '')
                                    { 
                                        html +='\
                                                <div class="row">\
                                                    <label>Attachment:</label>\
                                                            <div class="col-md-2">\
                                                                <div class="form-group">\
                                                                    <center>\
                                                                        <a href="assets/complaint_remarks_attachment/'+oneByOne.attachment+'" target="_blank">view</a>\
                                                                        <a href="assets/complaint_remarks_attachment/'+oneByOne.attachment+'"  download style="color:blue">Download</a>\
                                                                    </center>\
                                                                    <input type="file" class="form-control dropify" data-height="100" data-default-file="assets/complaint_remarks_attachment/'+oneByOne.attachment+'" data-show-remove="false" data-show-loader="false">\
                                                                </div>\
                                                            </div>\
                                                </div>';
                                    }    
                                html +='\
                                    </div>\
                            </div>\
                          ';
              });
              $('.showRemarks').append(html);
              $('.dropify').dropify();
            }
          });
        }  // end complaint_remarks_list

        // add complaint category
      $('.comp_remarks_add_form').submit(function(e){
            e.preventDefault(); 
            $('.add_button').prop("disabled", true);
            var formData = new FormData( $(".comp_remarks_add_form")[0] );

            $.ajax({
                        url:"<?php echo base_url(); ?>admin/insert_comploaint_remarks",
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
                                $(".comp_remarks_add_form")[0].reset();
                                complaint_remarks_list();
                                // $('.show_hide_form').click();
                                $(".dropify-clear").click();
                                message(1,response);
                            }
                            else
                            { 
                              message(0,response);
                            }
                        }
                    }); 
                    
        });
        
      });
</script>>