
       <footer class="main-footer">
       
      </footer>
    

   <!-- General JS Scripts -->
   <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="<?php echo base_url()?>/assets/js/stisla.js"></script>
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/js/bootstrap-modal.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/js/bootstrap-modal.min.js"></script> -->

  <!-- load script chart js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
  <!-- Template JS File -->
  <script src="<?php echo base_url()?>/assets/js/scripts.js"></script>
  <script src="<?php echo base_url()?>/assets/js/custom.js"></script>

  <!-- Page Specific JS File -->
 <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
 <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
 <script src="assets/js/page/forms-advanced-forms.js"></script>
 <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
 <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap.min.js"></script>
 <script src=" https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script> 
 <script src=" https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script> 
 <!-- <script src="https://cdn.datatables.net/fixedheader/3.1.9/js/dataTables.fixedHeader.min.js"></script>
 <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
 <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap.min.js"></script> -->
  
  <script>
  // In your Javascript (external .js resource or <script> tag)
  $(document).ready(function() {
    // load select2
    $('.select2').select2(
      {
      width: 'resolve',
      placeholder:"Select an option"
    }
    );

  });
  
  $(function () {
          $('#datetimepicker3').datetimepicker({
            defaultDate: new Date(),
            format:'YYYY-MM-DD'
          });
      });
      $(function () {
          var offset = +7;
          var today = new Date();
          var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
          var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
          var dateTime = date+' '+time;
          $('#datetimepicker4').datetimepicker({
            defaultDate: today.getTime(),
            format:'HH:mm:ss'
          });
      });

  $('.datepicker').datepicker({
            uiLibrary: 'bootstrap4'
        });
  $('#datepicker2').datepicker({
            uiLibrary: 'bootstrap4'
        });
     
  </script>
 <script>
       CKEDITOR.replace('alamat');
 </script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js" integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg==" crossorigin="anonymous" />
<script>
  $('.timepicker').timepicker();
</script>
</body>
</html>
