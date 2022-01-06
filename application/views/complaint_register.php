<style>
  .asterisk {
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
                                <h4>Complainant Personal Information</h4>
                            </div>
                            <div class="card-body" bis_skin_checked="1">
                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="test">Name <span class="asterisk">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control"  name="complainant_name" id="complainant_name" required  maxlength="70" onkeyup="this.value=this.value.replace(/[^A-Za-z\s]/g,'');">
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Father/Husband Name <span class="asterisk">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control"  name="complainant_guardian_name" id="complainant_guardian_name" required maxlength="70" onkeyup="this.value=this.value.replace(/[^A-Za-z\s]/g,'');">
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
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>CNIC <span class="asterisk">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="complainant_cnic" id="complainant_cnic" data-inputmask="'mask': '99999-9999999-9'" required >
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
                                                <input type="text" class="form-control"  name="complainant_contact" id="complainant_contact" data-inputmask="'mask': '0399-99999999'" required maxlength = "12">
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
                                                <select class="form-control select2" id="home_district_id" name="home_district_id" style="width:100%" required>
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
                                                <select class="form-control select2" id="complainant_council_id_fk" name="complainant_council_id_fk" style="width:100%" required>
                                                    <option disabled value="" selected hidden>Please Select Union-concil</option>
                                                        <?php if($district_councils){ foreach($district_councils as $union_dist){?>
                                                    <option value="<?= $union_dist->district_council_id?>"><?= $union_dist->district_council_name?></option>
                                                        <?php } }?>
                                                </select>
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
                                                <input type="text" class="form-control"  name="complainant_address" id="complainant_address" required onkeyup="this.value=this.value.replace(/[^A-Za-z\s]/g,'');">
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
                                                <select class="form-control select2" id="district_id_fk" name="district_id_fk" style="width:100%" required>
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
                                                <select class="form-control select2" id="complaint_council_id_fk" name="complaint_council_id_fk" style="width:100%" required>
                                                    <option disabled value="" selected hidden>Please Select Union-concil</option>
                                                        <?php if($district_councils){ foreach($district_councils as $union_dist){?>
                                                    <option value="<?= $union_dist->district_council_id?>"><?= $union_dist->district_council_name?></option>
                                                        <?php } }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Police Station <span class="asterisk">*</span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" id="district_id_fk" name="police_station_id_fk" style="width:100%" required>
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
                                                <textarea class="form-control" name="complaint_detail" id="complaint_detail" rows="3" onkeyup="this.value=this.value.replace(/[^A-Za-z\s]/g,'');" required></textarea>
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Attachments: </label>
                                            <div class="input-group dropzone" id="mydropzone">
                                                <div class="">
                                                    <input name="attachments[]" type="file" id="attachment_input" multiple style="display:none;"  />
                                                </div>
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-success p-r-15 waves-effect" style="font-size:16px;">Submit Complaint</button>
                                    </div>

                                </div> <!-- end of row -->
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

    $('#mydropzone').click(function(){
        $('#attachment_input').trigger('');
        
    }); 
    $(":input").inputmask();



    // add record script
    $(document).ready(function(){
        $('#register_complaint_form').submit(function(e){
            e.preventDefault(); 
            var formData = new FormData( $("#register_complaint_form")[0] );
            console.log(formData);
            $.ajax({
                 url:"<?php echo base_url(); ?>admin/complaint_register_ajax",
                 type:"post",
                 data:formData,
                 processData:false,
                 contentType:false,
                 cache:false,
                 async:false,
                 success: function(response){ alert(response);
                //    if(data == 'Record Add Successfully'){
                //        $("#register_complaint_form")[0].reset();
                //      }
                 }
            });
        });
    });
</script>>