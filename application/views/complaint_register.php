<style>
    .asterisk 
    {
        color: red;font-weight: bolder;
    }
</style>

 <!-- Main Content -->
 <div class="main-content">
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12">

                    <!-- start messages --->
                        <div style="text-align: center">
                            <?php 
                            if($feedback =$this->session->flashdata('feedback'))
                            {
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

                    <!-- <form class="register_complaint_form" method="post" action="<?= base_url("admin/complaint_register_ajax") ?>" enctype="multipart/form-data"> -->
                     <form class="register_complaint_form" id="register_complaint_form" method="post" enctype="multipart/form-data">
                     
                        <div class="card card-success" bis_skin_checked="1">
                            <div class="card-header" bis_skin_checked="1">
                                <h4>Applicant Personal Information</h4>
                            </div>
                            <div class="card-body" bis_skin_checked="1">
                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="test">Name <span class="asterisk">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control"  name="complainant_name" id="complainant_name" required  maxlength="30" onkeyup="this.value=this.value.replace(/[^A-Za-z\s]/g,'');">
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Father/Husband Name <span class="asterisk">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control"  name="complainant_guardian_name" id="complainant_guardian_name" required maxlength="30"  onkeyup="this.value=this.value.replace(/[^A-Za-z\s]/g,'');">
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Gender <span class="asterisk">*</span></label>
                                            <div class="input-group">
                                                <select class="form-control" name="complainant_gender" id="complainant_gender" required>
                                                    <option disabled value="" selected hidden>Select Gender</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                    <option value="Custom">Custom</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>CNIC <span class="asterisk">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="complainant_cnic" id="complainant_cnic" data-inputmask="'mask': '99999-9999999-9'" required minlength="15" maxlength="15" onblur="cnic_validation()">
                                            </div>
                                        </div>
                                    </div> 

                                </div>
                                <!-- end of row -->

                                <!-- second row -->
                                <div class="row"> 

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Contact No <span class="asterisk">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control"  name="complainant_contact" id="complainant_contact" data-inputmask="'mask': '0399-99999999'" required maxlength = "12" minlenth="12" onblur="contact_validation()">
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Email:</label>
                                                <div class="input-group">
                                                    <input type="email" class="form-control"  name="complainant_email" id="complainant_email" >
                                                </div>
                                            </div>
                                    </div> 

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Home District <span class="asterisk">*</span></label>
                                            <div class="input-group">
                                                <select class="form-control select2 home_district_id" id="home_district_id" name="home_district_id" style="width:100%" required>
                                                    <option disabled value="" selected hidden>Please Select District</option>
                                                        <?php if($district){ foreach($district as $dist){?>
                                                    <option value="<?= $dist->district_id?>"><?= $dist->district_name?></option>
                                                        <?php } }?>
                                            </select>
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Union Council <span class="asterisk">*</span></label>
                                            <div class="input-group">
                                              <input type="text" class="form-control"  name="complainant_council" id="complainant_council" required maxlength="30"> <!-- onkeyup="this.value=this.value.replace(/[^A-Za-z\s]/g,'');" -->
                                            </div>
                                        </div>
                                    </div> 

                                </div> 
                                <!-- end of row -->

                                 <!-- third row -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Full Address <span class="asterisk">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control"  name="complainant_address" id="complainant_address" required> <!-- onkeyup="this.value=this.value.replace(/[^A-Za-z0-9\s]/g,'');" -->
                                            </div>
                                        </div>
                                    </div> 
                                </div> 
                                <!-- end row -->

                            </div>
                        </div>

                        <div class="card card-success" bis_skin_checked="1">
                            <div class="card-header" bis_skin_checked="1">
                                <h4>Complaint Detail</h4>
                            </div>
                            <div class="card-body" bis_skin_checked="1">
                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Complaint Type <span class="asterisk">*</span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" id="complaint_category_id" name="complaint_category_id" style="width:100%" required>
                                                <option disabled value="" selected hidden>Please Select Complaint Type</option>
                                                    <?php if($complaint_categories){ foreach($complaint_categories as $type){?>
                                                <option value="<?= $type->complaint_category_id?>"><?= $type->complaint_category_name?></option>
                                                    <?php } }?>
                                            </select>
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>District <span class="asterisk">*</span></label>
                                            <div class="input-group">
                                                <select class="form-control select2 district_id_fk" id="district_id_fk" name="district_id_fk" style="width:100%" required>
                                                       <option disabled value="" selected hidden>Please Select District</option>
                                                    <?php if($district){ foreach($district as $dist){?>
                                                       <option value="<?= $dist->district_id?>"><?= $dist->district_name?></option>
                                                    <?php } }?>
                                            </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Union Council <span class="asterisk">*</span></label>
                                            <div class="input-group">
                                               <input type="text" class="form-control"  name="complaint_council" id="complaint_council" required maxlength="30"> <!-- onkeyup="this.value=this.value.replace(/[^A-Za-z\s]/g,'');" -->
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Police Station <span class="asterisk">*</span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" id="police_station_id_fk" name="police_station_id_fk" style="width:100%" required>
                                                    <option disabled value="" selected hidden>Please Select Police Station</option>
                                                    <?php if($police_stations){ foreach($police_stations as $row){?>
                                                    <option value="<?= $row->police_station_id?>"><?= $row->police_station_name?></option>
                                                        <?php } }?>
                                            </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Complaint Details <span class="asterisk">*</span></label>
                                            <div class="input-group">
                                                <textarea class="form-control" name="complaint_detail" id="complaint_detail" rows="3"  required></textarea>
                                            </div>
                                        </div>
                                    </div> 
                                </div> <!-- end of row-->
                                <div class="row optionBox">

                                    <!-- <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Attachments: </label>
                                            <div class="input-group dropzone" id="mydropzone">
                                                <div class="">
                                                   <span class="btn-file">
                                                     <input name="attachments[]" type="file" id="attachment_input" multiple  />
                                                    </span>
                                                </div>
                                            </div>
                                            
                                    </div>  -->

                                    <div class="col-md-3">
                                        <div class="form-group ">
                                            <label>Attachments: <span class="add" style="cursor:pointer;color:blue;"><b>Add More Attachments</b></span></label>
                                            <input type="file" name="attachments[]"  class="form-control dropify" data-height="100"  data-max-file-size="200M" data-allowed-file-extensions="pdf png jpg jpeg doc docx csv xlsx mp4 mpeg mp3" >
                                        </div> 
                                    </div> 

                                </div> <!-- end of row-->
                            </div>

                            <div class="card-footer" style="border-top: 1px solid #e9e7e7; background-color: #f9f9f9;">
                                <div class="col-md-12 text-right mb-2">
                                    <button type="submit" id="submitButton" class="btn btn-icon btn-success custom-success-btn" style="font-size:16px;">Submit Complaint</button>
                                     <button id="loaderButton" class="btn btn-success custom-success-btn"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i></button>
                                </div>
                            </div>

                        </div>

                    </form>
                    
              </div>
            </div>
          </div>
        </section>
      </div>
      <script src="assets/js/jquery.inputmask.bundle.js"></script>
<<script>
   

    $('.add').click(function() {
        $('.optionBox:last').append('<div class="col-md-3 block"><label>Attachment:</label><div class="form-group"><input type="file" name="attachments[]"  class="form-control dropify" data-height="100"  data-max-file-size="200M" data-allowed-file-extensions="pdf png jpg jpeg doc docx csv xlsx mp4 mpeg mp3" ><span class="remove" style="cursor:pointer;color:blue;">Remove</span> </div> </div>');
        $('.dropify').dropify();
    });
    $('.optionBox').on('click','.remove',function() { 
        $(this).parent().parent().remove();
    });

    // $('#mydropzone').click(function(){


    //    $('#attachment_input').trigger('click');  
    // }); 
    $(":input").inputmask();
    
  
    $(document).ready(function()
    {
        $('.dropify').dropify();
        $('#loaderButton').hide();
        $('.dropify').dropify({
            messages: {
                'default': 'Drag and drop a file here or click to select manually',
                'replace': 'Drag and drop or click to replace',
                'remove':  'Remove',
                'error':   'Ooops, something went wrong'
            }
        });
      
    // add record script
        $('#register_complaint_form').submit(function(e){  
            $('#submitButton').hide();
            $('#loaderButton').show();
            var formData = new FormData( $("#register_complaint_form")[0] );

            $.ajax({
                        url:"<?php echo base_url(); ?>admin/complaint_register_ajax",
                        type:"post",
                        data:formData,
                        processData:false,
                        contentType:false,
                        cache:false,
                        async:false,
                        success: function(response)
                        { //alert(response);
                            if(response == 'Complaint Registered Successfully')
                            { 
                                $("#register_complaint_form")[0].reset();
                                 message(1,response);
                              window.location.href = "admin/complaints/1";
                            }
                            else
                            {
                                message(0,response);
                                $('#loaderButton').hide();
                                $('#submitButton').show();
                            }
                        }
                    });
            e.preventDefault();        
        });
    
    // ::::::::::::::::: Selection union againt complaint  
    $("#district_id_fk").change(function(district_id_fk)
        {
            var district_id_fk = $(this).val(); 

            $.ajax({
                url: 'admin/get_police_station_ajax/'+district_id_fk,
                dataType: 'json',
                success: function(response)
                { 
                    $('#police_station_id_fk').empty();
                    $('#police_station_id_fk').append('<option disabled value="" selected hidden>Select Police Station</option>');
                    $.each(response, function(key, value) 
                    {
                        $('#police_station_id_fk').append('<option value="'+ value.police_station_id +'">'+value.police_station_name+'</option>');
                        // $('#police_station_id_fk option[value='+value.police_station_id+']').attr('selected','selected'); 
                    });
                }
            });
        });
    });

function cnic_validation()
{
    var cnic          = $('#complainant_cnic').val();
    var rem_uderscore = cnic.replace(/-/g, "");
    var rem_hyphens   = rem_uderscore.replace(/_/g, "");
    var cnic_length   = rem_hyphens.length;
    if(cnic_length < 13)
    { 
        $('#complainant_cnic').css({'border' : '1px solid red'});
        $('#complainant_cnic').focus();
        return false;
    }
    else
    {
        $('#complainant_cnic').css({'border' : '1px solid #e4e6fc'});
        $('#complainant_cnic').blur();
        return true;
    }
}
function contact_validation()
{
    var contact       = $('#complainant_contact').val();
    var rem_uderscore = contact.replace(/-/g, "");
    var rem_hyphens   = rem_uderscore.replace(/_/g, "");
    var contact_length= rem_hyphens.length;
    if(contact_length < 11)
    { 
        $('#complainant_contact').css({'border' : '1px solid red'});
        $('#complainant_contact').focus();
        return false;
    }
    else
    {
        $('#complainant_contact').css({'border' : '1px solid #e4e6fc'});
        $('#complainant_contact').blur();
        return true;
    }
}
</script>

