
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
        <form class="form-horizontal">
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">No Claim <?php echo form_error('no_claim') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="no_claim" id="no_claim" placeholder="No Claim" value="<?php echo $no_claim; ?>" readonly />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="date">Tanggal Pengajuan <?php echo form_error('tanggal_pengajuan') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="tanggal_pengajuan" id="tanggal_pengajuan" placeholder="Tanggal Pengajuan" value="<?php echo $tanggal_pengajuan; ?>" readonly />
                </div>
              </div>
	   
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
                <label class="col-sm-2 control-label" for="varchar">Barang <?php echo form_error('barang') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="barang" id="barang" placeholder="Barang" value="<?php echo $barang; ?>" readonly />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="int">Kuantitas <?php echo form_error('kuantitas') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="kuantitas" id="kuantitas" placeholder="Kuantitas" value="<?php echo $kuantitas; ?>" readonly />
                </div>
              </div>
	      
            <div class="card-body">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="kondisi_barang">Kondisi Barang <?php echo form_error('kondisi_barang') ?></label>
                <div class="col-sm-12">
                    <textarea class="form-control" rows="3" name="kondisi_barang" id="kondisi_barang" placeholder="Kondisi Barang"><?php echo $kondisi_barang; ?></textarea>
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Foto Barang <?php echo form_error('foto_barang') ?></label>
                <div class="col-sm-12">
                <img src="<?php echo base_url().'image/'.$foto_barang ?>" class='img-fluid' alt="photo" width="350px">
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Status <?php echo form_error('status') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="status" id="status" placeholder="Status" value="<?php echo $status; ?>" readonly />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Catatan <?php echo form_error('catatan') ?></label>
                <div class="col-sm-12">
                  <textarea class="form-control" rows="3" name="catatan" id="catatan" placeholder="Kondisi Barang"><?php echo $catatan; ?></textarea>
                </div>
              </div>
                  <input type="hidden" class="form-control" name="sales_id" id="sales_id" placeholder="Sales Id" value="<?php echo $sales_id; ?>" readonly />
             
	   
     
        <div class="card-footer text-left">
        <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <a href="<?php echo site_url('claim') ?>" class="btn btn-icon icon-left btn-success">Cancel</a>
	
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
</div>
