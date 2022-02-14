
<!DOCTYPE html>
<html lang="en">

<head>
  <base href="<?=base_url()?>">
    <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Public Safety Comission Police Complaint Management System</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="assets/css/app.min.css">
  <link rel="stylesheet" href="assets/bundles/chocolat/dist/css/chocolat.css">
  <link rel="stylesheet" href="assets/bundles/datatables/datatables.min.css">
  <link rel="stylesheet" href="assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
  
  <link rel="stylesheet" href="assets/css/app.min.css">
  <link rel="stylesheet" href="assets/bundles/dropzonejs/dropzone.css">
     <!-- select 2 lib -->
  <link rel="stylesheet" href="assets/bundles/select2/dist/css/select2.min.css">
  <!----breadcrumb----->
  <link rel="stylesheet" href="breadcrumb_assets/style.css">
  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/custom.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="assets/css/custom.css">
  <link rel='shortcut icon' type='image/x-icon' href='assets/img/logo/logo-icon.png' />
  <script src="assets/js/jquery.min.js"></script>

  <style>

    table tr.bg-success th
    {
      color: #fff !important;
    }

  </style>
  
</head>

<body>
<style type="text/css">

@media print
{    
    #btn1, #btn2,.navbar,#sidebar-wrapper
    {
        display: none !important;
    }	
    .main-content
    {
        width:100% !important;
    }
}


@media print
{    
    #footer {
	position: fixed;
	bottom: 0;
	display: block !important;
	width:100%;
	}
}

</style>
 <!-- Main Content -->
 <!-- <div class="main-content"> -->
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <div class="col-md-8">
                      <!-- <a href="admin/complaints" id="btn1" class="btn btn-primary btn-sm fas fa-arrow-left"> Go Back</a> -->
                      <button onclick="window.print()" id="btn1" class="btn btn-primary btn-sm fas fa-print"> Print Complaint</button>
                        <!-- <h4><?= $title ?></h4> --> 
                        
                    </div>
                    <div class="col-md-4 text-right"><p style="margin-bottom:0px;">Source: <span class="text-capitalize"><?= $complaint_detail[0]->complaint_source ?><span></p></div>
                  </div>
                  <div class="card-body">
                      
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
                        
                        <!------ ::::::::::::::::::::::::: start Stats and respondant replay form --- :::::::::::::::::::::::::::-->
                        <?php if($complaints_remarks){ ?>
                        <div class="row bg-success text-white pt-2 text-center">
                            <div class="col-12 ">
                              <h3 class="text-center">Respondent Response</h3> 
                            </div>  
                        </div>
                            <?php foreach($complaints_remarks as $remarks){?>
                                <div class="card card-success">
                                      <div class="card-header">
                                        <!--- heading start-->
                                        <div class="row col-md-12">
                                            <div class="col-md-4">
                                                <p><strong>Remarks From: </strong><?= empty($remarks->respondent_title)?'Admin':$remarks->respondent_title ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><strong>Status: </strong><?= $remarks->complaint_status_title ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><strong>Date: </strong><?= date_format(date_create($remarks->complaint_remarks_timestamp),'d-m-Y') ?></p>
                                            </div>
                                         </div>
                                        <!-- end heading -->
                                      </div>
                                      <div class="card-body">
                                        <p><?= $remarks->complaint_remarks_detail?></p>
                                      </div>
                                </div>
                            <?php } } ?> 
                  </div>
                  <!-- <a class="btn btn-success" href="javascript:void(0)" style="float: right;" onclick="PrintDiv();">Print</a> -->
                  
                 </div> <!---pdf section end -->
                </div>
              </div>
            </div>
          </div>
        </section>
      <!-- </div> -->
<script>
    $('#mydropzone').click(function()
    {
        $('#attachment_input').trigger('click');
    }); 
    
    
 
</script>>
<script>

function ExportToExcel(type, fn, dl) {
var elt = document.getElementById('tbl_exporttable_to_xls');
var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
return dl ?
XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
XLSX.writeFile(wb, fn || ('PWD Certificate Beneficiary List.' + (type || 'xlsx')));
}

</script>



<script src="assets/js/app.min.js"></script>
  <!-- JS Libraies -->
  <script src="assets/bundles/datatables/datatables.min.js"></script>
  <script src="assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
  <script src="assets/bundles/jquery-ui/jquery-ui.min.js"></script>

  <script src="assets/bundles/select2/dist/js/select2.full.min.js"></script>
  <!-- Page Specific JS File -->
   <script src="assets/js/page/datatables.js"></script>
   <!-- JS Libraies -->
  <script src="assets/bundles/dropzonejs/min/dropzone.min.js"></script>
  <!-- Page Specific JS File -->
  <script src="assets/js/page/multiple-upload.js"></script>
  <!-- Template JS File -->
  <script src="assets/js/scripts.js"></script>
  <!-- Custom JS File -->
  <script src="assets/js/custom.js"></script>
  <script src="breadcrumb_assets/script.js"></script>
  <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">
</body>