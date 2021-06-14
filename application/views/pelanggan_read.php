
 <div class="main-content">
<section class="section">
  <div class="section-header">
    <h1> Pelanggan </h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item active"><a href="<?php echo base_url(); ?>dashboard"><i class="fa fa-dashboard"></i> Home</a></div>
      <div class="breadcrumb-item"><a href="#"> Pelanggan </a></div>
    </div>
  </div>

  <div class="section-body">
  <div class="row">
      <div class="col-12 col-md-12 col-lg-12">
        <div class="card">
        <div class="card-header">
            <h4>Form Pelanggan </h4>
        </div>
        <form class="form-horizontal">
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Kode Customer <?php echo form_error('kode_customer') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="kode_customer" id="kode_customer" placeholder="Kode Customer" value="<?php echo $kode_customer; ?>" readonly />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Npwp <?php echo form_error('npwp') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="npwp" id="npwp" placeholder="Npwp" value="<?php echo $npwp; ?>" readonly />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Nama Customer <?php echo form_error('nama_customer') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="nama_customer" id="nama_customer" placeholder="Nama Customer" value="<?php echo $nama_customer; ?>" readonly />
                </div>
              </div>
	      
            <div class="card-body">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="alamat">Alamat <?php echo form_error('alamat') ?></label>
                <div class="col-sm-12">
                    <textarea class="form-control" rows="3" name="alamat" id="alamat" placeholder="Alamat"><?php echo $alamat; ?></textarea>
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Telepon <?php echo form_error('telepon') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="telepon" id="telepon" placeholder="Telepon" value="<?php echo $telepon; ?>" readonly />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Kode Pos <?php echo form_error('kode_pos') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="kode_pos" id="kode_pos" placeholder="Kode Pos" value="<?php echo $kode_pos; ?>" readonly />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Passpor <?php echo form_error('passpor') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="passpor" id="passpor" placeholder="passpor" value="<?php echo $passpor; ?>" readonly />
                </div>
              </div>
	   
     
        <div class="card-footer text-left">
        <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <a href="<?php echo site_url('pelanggan') ?>" class="btn btn-icon icon-left btn-success">Cancel</a>
	
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
</div>
