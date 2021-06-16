
 <div class="main-content">
<section class="section">
  <div class="section-header">
    <h1> Claim </h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item active"><a href="<?php echo base_url(); ?>dashboard"><i class="fa fa-dashboard"></i> Home</a></div>
      <div class="breadcrumb-item"><a href="#"> Claim </a></div>
    </div>
  </div>

  <div class="section-body">
  <div class="row">
      <div class="col-12 col-md-12 col-lg-12">
        <div class="card">
        <div class="card-header">
            <h4>Form Claim </h4>
        </div>
        <form action="<?php echo $action; ?>" method="post" class="form-horizontal" enctype="multipart/form-data">
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">No Claim <?php echo form_error('no_claim') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="no_claim" id="no_claim" placeholder="No Claim" value="<?php echo $no_claim; ?>" />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="date">Tanggal Pengajuan <?php echo form_error('tanggal_pengajuan') ?></label>
                <div class="col-sm-12">
                  <input type="date" class="form-control" name="tanggal_pengajuan" id="tanggal_pengajuan" placeholder="Tanggal Pengajuan" value="<?php echo $tanggal_pengajuan; ?>" />
                </div>
              </div>
	   
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
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Barang <?php echo form_error('barang') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="barang" id="barang" placeholder="Barang" value="<?php echo $barang; ?>" />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="int">Kuantitas <?php echo form_error('kuantitas') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="kuantitas" id="kuantitas" placeholder="Kuantitas" value="<?php echo $kuantitas; ?>" />
                </div>
              </div>
	      
              <div class="form-group">
                <label class="col-sm-2 control-label" for="kondisi_barang">Kondisi Barang <?php echo form_error('kondisi_barang') ?></label>
                <div class="col-sm-12">
                    <textarea class="form-control" rows="3" name="kondisi_barang" id="kondisi_barang" placeholder="Kondisi Barang"><?php echo $kondisi_barang; ?></textarea>
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Foto Barang <?php echo form_error('foto_barang') ?></label>
                <div class="col-sm-12">
                  <input type="file" class="form-control" name="foto_barang" id="foto_barang" placeholder="Foto Barang" value="<?php echo $foto_barang; ?>" />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Status <?php echo form_error('status') ?></label>
                <div class="col-sm-12">
                  <select name="status" id="status" class="form-control">
                    <option value="<?=$status?>"><?=$status == "" ? "Choose an option" : $status?></option>
                    <option value="Pengajuan">Pengajuan</option>
                    <option value="Proses">Proses</option>
                    <option value="Tolak">Tolak</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Catatan <?php echo form_error('catatan') ?></label>
                <div class="col-sm-12">
                  <textarea class="form-control" rows="3" name="catatan" id="catatan" placeholder="Kondisi Barang"><?php echo $catatan; ?></textarea>
                </div>
              </div>
                  <input type="hidden" class="form-control" name="sales_id" id="sales_id" placeholder="Sales Id" value="<?php echo $sales_id; ?>" />
	   
	   
     
        <div class="card-footer text-left">
        <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><span class="fa fa-edit"></span><?php echo $button ?></button> 
	    <a href="<?php echo site_url('claim') ?>" class="btn btn-icon icon-left btn-success">Cancel</a>
	
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