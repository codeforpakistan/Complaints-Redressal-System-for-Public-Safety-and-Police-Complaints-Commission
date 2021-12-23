<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">
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
                    <form class="" method="post" action="<?= base_url("admin/complaint_register") ?>" enctype="multipart/form-data">
                        <div class="row">
                        <div class="col-md-4">
                                <div class="form-group">
                                    <label>Name:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control"  name="complainant_name" id="complainant_name" required >
                                    </div>
                                </div>
                            </div> <!-- end of col-md-4 -->

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Contact No:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control"  name="complainant_contact" id="complainant_contact" required >
                                    </div>
                                </div>
                            </div> <!-- end of col-md-4 -->

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Father/Husband Name:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control"  name="complainant_guardian_name" id="complainant_guardian_name" required >
                                    </div>
                                </div>
                            </div> <!-- end of col-md-4 -->
                        </div> <!-- end of row -->
                        
                        <div class="row">
                        <div class="col-md-4">
                                <div class="form-group">
                                    <label>City:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control"  name="complainant_city" id="complainant_city" required >
                                    </div>
                                </div>
                            </div> <!-- end of col-md-4 -->

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Union Council:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control"  name="complainant_union_council" id="complainant_union_council" required >
                                    </div>
                                </div>
                            </div> <!-- end of col-md-4 -->

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email:</label>
                                    <div class="input-group">
                                        <input type="email" class="form-control"  name="complainant_email" id="complainant_email" required >
                                    </div>
                                </div>
                            </div> <!-- end of col-md-4 -->
                        </div> <!-- end of row -->

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Gender:</label>
                                    <div class="input-group">
                                        <select class="form-control" name="complainant_gender" id="complainant_gender">
                                            <option selected value="0">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div> <!-- end of col-md-4 -->

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CNIC:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="complainant_cnic" id="complainant_cnic" required >
                                    </div>
                                </div>
                            </div> <!-- end of col-md-4 -->

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Complaint Type:</label>
                                    <div class="input-group">
                                        <select class="form-control select2" id="complaint_category_id" name="complaint_category_id" style="width:90%">
                                          <option disabled value="" selected hidden>Please Select Complaint Type</option>
                                              <?php if($complaint_categories){ foreach($complaint_categories as $type){?>
                                           <option value="<?= $type->complaint_category_id?>"><?= $type->complaint_category_name?></option>
                                             <?php } }?>
                                       </select>
                                    </div>
                                </div>
                            </div> <!-- end of col-md-4 -->
                        </div> <!-- end of row -->

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Full Address:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control"  name="complainant_address" id="complainant_address" required >
                                    </div>
                                </div>
                            </div> <!-- end of col-md-4 --> 

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>District:</label>
                                    <div class="input-group">
                                        <select class="form-control select2" id="district_id_fk" name="district_id_fk" style="width:90%">
                                          <option disabled value="" selected hidden>Please Select District</option>
                                              <?php if($district){ foreach($district as $dist){?>
                                           <option value="<?= $dist->district_id?>"><?= $dist->district_name?></option>
                                             <?php } }?>
                                       </select>
                                    </div>
                                </div>
                            </div> <!-- end of col-md-4 -->

                        </div>  <!-- end row --> 
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Complaint Details:</label>
                                    <div class="input-group">
                                        <textarea class="form-control" name="complaint_detail" id="complaint_detail" rows="3"></textarea>
                                    </div>
                                </div>
                            </div> <!-- end of col-md-12 -->                         
                        </div>  <!-- end row --> 

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Attachments:</label>
                                    <!-- <input name="file1" type="file" class="dropify" data-height="100" multiple/> -->
                                    <!-- <input name="attachments[]" type="file" multiple /> -->
                                    <!-- <div class="input-group dropzone" id="mydropzone"> -->
                                    <div class="input-group dropzone" id="mydropzone">
                                        <!-- <input name="attachments[]" type="file" multiple /> -->
                                        <div class="fallback">
                                            <input name="attachments[]" type="file" multiple />
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end of col-md-12 -->                         
                        </div>  <!-- end row --> 


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
          </div>
        </section>
      </div>
<<script>
$('.dropify').dropify();
</script>>