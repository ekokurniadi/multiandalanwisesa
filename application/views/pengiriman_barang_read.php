
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
        <form class="form-horizontal">
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">No Do <?php echo form_error('no_do') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="no_do" id="no_do" placeholder="No Do" value="<?php echo $no_do; ?>" readonly />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">No Po <?php echo form_error('no_po') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="no_po" id="no_po" placeholder="No Po" value="<?php echo $no_po; ?>" readonly />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Customer <?php echo form_error('customer') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="customer" id="customer" placeholder="Customer" value="<?php echo $customer; ?>" readonly />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="date">Tanggal Pengiriman <?php echo form_error('tanggal_pengiriman') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="tanggal_pengiriman" id="tanggal_pengiriman" placeholder="Tanggal Pengiriman" value="<?php echo $tanggal_pengiriman; ?>" readonly />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="time">Waktu Pengiriman <?php echo form_error('waktu_pengiriman') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="waktu_pengiriman" id="waktu_pengiriman" placeholder="Waktu Pengiriman" value="<?php echo $waktu_pengiriman; ?>" readonly />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Status <?php echo form_error('status') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="status" id="status" placeholder="Status" value="<?php echo $status; ?>" readonly />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Jenis Pengiriman <?php echo form_error('jenis_pengiriman') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="jenis_pengiriman" id="jenis_pengiriman" placeholder="Jenis Pengiriman" value="<?php echo $jenis_pengiriman; ?>" readonly />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Driver <?php echo form_error('driver') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="driver" id="driver" placeholder="Driver" value="<?php echo $driver; ?>" readonly />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Penerima <?php echo form_error('penerima') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="penerima" id="penerima" placeholder="Penerima" value="<?php echo $penerima; ?>" readonly />
                </div>
              </div>
	   
     
        <div class="card-footer text-left">
        <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <a href="<?php echo site_url('pengiriman_barang') ?>" class="btn btn-icon icon-left btn-success">Cancel</a>
	
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
</div>
