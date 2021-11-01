<body onload="cek_jenis();">
  
</body>
 <div class="main-content">
<section class="section">
  <div class="section-header">
    <h1> Pengiriman Barang </h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item active"><a href="<?php echo base_url(); ?>dashboard"><i class="fa fa-dashboard"></i> Home</a></div>
      <div class="breadcrumb-item"><a href="#"> Pengiriman Barang </a></div>
    </div>
  </div>

  <div class="section-body">
  <div class="row">
      <div class="col-12 col-md-12 col-lg-12">
        <div class="card">
        <div class="card-header">
            <h4>Form Pengiriman Barang </h4>
        </div>
        <form action="<?php echo $action; ?>" method="post" class="form-horizontal">
	   
                  <input type="hidden" class="form-control" name="id_pengiriman" id="id_pengiriman" placeholder="No Do" value="<?php echo $id_pengiriman; ?>" />
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">No Do <?php echo form_error('no_do') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="no_do" id="no_do" placeholder="No Do" value="<?php echo $no_do; ?>" />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">No Po <?php echo form_error('no_po') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="no_po" id="no_po" placeholder="No Po" value="<?php echo $no_po; ?>" />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Customer <?php echo form_error('customer') ?></label>
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-sm-8">
                      <input type="text" class="form-control" readonly name="customer" id="customer" placeholder="Customer" value="<?php echo $customer; ?>" />
                      <input type="hidden" class="form-control" readonly name="customer_id" id="customer_id" placeholder="Customer" value="<?php echo $customer; ?>" />
                    </div>
                    <div class="col-sm-4">
                    <button type="button" id="searchCustomer" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"> <span class="fa fa-search"> Cari Customer</span> </button> 
                    </div>
                  </div>
                </div>
              </div>
	   

              <div class="form-group" id="datetimepicker3" data-target-input="nearest">
                <label class="col-sm-2 control-label" for="date">Tanggal <?php echo form_error('tanggal_pengiriman') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker3" data-toggle="datetimepicker" name="tanggal_pengiriman" id="tanggal_pengiriman" placeholder="Date" value="<?php echo $tanggal_pengiriman; ?>" />
                </div>
              </div>
              <div class="form-group" id="datetimepicker4" data-target-input="nearest">
                <label class="col-sm-2 control-label" for="date">Waktu Pengiriman <?php echo form_error('waktu_pengiriman') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker4" data-toggle="datetimepicker" name="waktu_pengiriman" id="waktu_pengiriman" placeholder="Date" value="<?php echo $waktu_pengiriman; ?>" />
                </div>
              </div>
	   
            
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Status <?php echo form_error('status') ?></label>
                <div class="col-sm-12">
                  <select name="status" id="status" class="form-control">
                    <option value="<?=$status?>"><?=$status == "" ? "Choose an option" : $status?></option>
                    <option value="Shipping">Shipping</option>
                    <option value="On Delivery">On Delivery</option>
                    <option value="Complete">Complete</option>
                  </select>
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Jenis Pengiriman <?php echo form_error('jenis_pengiriman') ?></label>
                <div class="col-sm-12">
                  <select name="jenis_pengiriman" id="jenis_pengiriman" class="form-control">
                    <option value="<?php echo $jenis_pengiriman; ?>">Choose</option>
                    <option value="internal">Internal</option>
                    <option value="expedisi">Expedisi</option>
                  </select>
                </div>
              </div>
              <div id="ekspedisi">
                <div class="form-group" >
                  <label class="col-sm-2 control-label" for="varchar">Nama Ekspedisi <?php echo form_error('driver') ?></label>
                  <div class="col-sm-12">
                    <input type="text" class="form-control" name="nama_ekspedisi" id="nama_ekspedisi" placeholder="Nama Ekspedisi"  />
                  </div>
                </div>
              </div>
              <div id="internal">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Driver <?php echo form_error('driver') ?></label>
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-sm-8">
                      <input type="text" class="form-control" name="driver" id="driver" placeholder="driver" value="<?php echo $driver; ?>" />
                    </div>
                    <div class="col-sm-4">
                    <button type="button" id="searchDriver" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal2"> <span class="fa fa-search"> Cari Driver</span> </button> 
                    </div>
                  </div>
                </div>
              </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Penerima <?php echo form_error('penerima') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="penerima" id="penerima" placeholder="Penerima" value="<?php echo $penerima; ?>" />
                </div>
              </div>
	   
     
        <div class="card-footer text-left">
        <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><span class="fa fa-edit"></span><?php echo $button ?></button> 
	    <a href="<?php echo site_url('pengiriman_barang') ?>" class="btn btn-icon icon-left btn-success">Cancel</a>
	
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
</div>
<?php $this->load->view('modal_customer');?>
<script>
function getCustomer(id) {
          $.ajax({
            beforeSend: function() {
              $('#searchCustomer').attr('disabled', true);
              $('#searchCustomer').html('<i class="fa fa-spinner fa-spin">');
            },
            url: '<?= base_url('pelanggan/getById') ?>',
            type: "POST",
            data: {id},
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              console.log(response);
              if (response.status == 'sukses') {
                $('#customer').val(response.value.nama);   
                $('#customer_id').val(response.value.id);     
              } else {
                alert(response.pesan);
              }
              $('#searchCustomer').attr('disabled', false);
              $('#searchCustomer').html('<i class="fa fa-search">');
            },
            error: function() {
              alert("Something Went Wrong !");
              $('#searchCustomer').attr('disabled', false);
              $('#searchCustomer').html('<i class="fa fa-search">');

            }
          });
        }

</script>
<script type="text/javascript">
$(document).ready(function(){

$('#jenis_pengiriman').change(function(){    
var jenis = $('#jenis_pengiriman').val(); 
  if(jenis=="internal"){
    $('#ekspedisi').hide();
    $('#internal').show();
    $('#searchDriver').show();
    $('#driver').attr('readonly',true);
  }else{
    $('#ekspedisi').show();
    $('#searchDriver').hide();
    $('#driver').attr('readonly',false);
  }
})
});
function cek_jenis(){
  var jenis = $('#jenis_pengiriman').val(); 
  if(jenis==""){
    $('#ekspedisi').hide();
    $('#searchDriver').hide();
  }else if(jenis=="internal"){
    $('#ekspedisi').hide();
    $('#internal').show();
    $('#searchDriver').show();
  }else{
    $('#ekspedisi').show();
    $('#searchDriver').hide();
    
  }
}
</script>