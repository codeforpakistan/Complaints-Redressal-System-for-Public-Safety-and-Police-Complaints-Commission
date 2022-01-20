    <footer class="main-footer" style="display:none">
        <div class="footer-left">
          <a href="">Code For Pakistan</a></a>
        </div>
        <div class="footer-right">
        </div>
    </footer>

    </div>
  </div>
  <!-- General JS Scripts -->
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


<!-- index.html  21 Nov 2019 03:47:04 GMT -->
</html>

<script>

   function onlyCNIC(obj, evt) {
    
    obj.on('paste', function(e){
        /// v variable have paste value
        //var v = e.originalEvent.clipboardData.getData('Text');
        return false;
        obj.val('');       
    });
    
    var charCode = (evt.which) ? evt.which : event.keyCode;
    
    if(obj.val().length < 15)
    {
        if(onlyDigits(charCode))
        {
            if(obj.val().length == 5 || obj.val().length == 13)
                obj.val(obj.val()+'-');
        }
        else
            return false;
    }
    else
        return false;
}



//// only digits function
function onlyDigits(charCode)
{
    
    if(charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }    
    else
    {
        return true;
    }
}

 function onlyNumber(obj, evt) {
   
    obj.on('paste', function(e){
        /// v variable have paste value
        //var v = e.originalEvent.clipboardData.getData('Text');
        return false;
        obj.val('');       
    });
    
    var charCode = (evt.which) ? evt.which : event.keyCode;
    
   
     return  onlyDigits(charCode);
      
    }
    
//// only digits function
function onlyDigits(charCode)
{
    
    if(charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }    
    else
    {
        return true;
    }
}
 
</script>