
 <div class="main-content">
<section class="section">
  <div class="section-header">
    <h1> Jadwal </h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item active"><a href="<?php echo base_url(); ?>dashboard"><i class="fa fa-dashboard"></i> Home</a></div>
      <div class="breadcrumb-item"><a href="#"> Jadwal </a></div>
    </div>
  </div>

  <div class="section-body">
  <div class="row">
      <div class="col-12 col-md-12 col-lg-12">
        <div class="card">
        <div class="card-header">
            <h4>Form Jadwal </h4>
        </div>
        <form class="form-horizontal">
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Id Sales <?php echo form_error('id_sales') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="id_sales" id="id_sales" placeholder="Id Sales" value="<?php echo $id_sales; ?>" readonly />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Customer <?php echo form_error('customer') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="customer" id="customer" placeholder="Customer" value="<?php echo $customer; ?>" readonly />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="date">Tanggal <?php echo form_error('tanggal') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="tanggal" id="tanggal" placeholder="Tanggal" value="<?php echo $tanggal; ?>" readonly />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="time">Jam <?php echo form_error('jam') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="jam" id="jam" placeholder="Jam" value="<?php echo $jam; ?>" readonly />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Jenis Kunjungan <?php echo form_error('jenis_kunjungan') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="jenis_kunjungan" id="jenis_kunjungan" placeholder="Jenis Kunjungan" value="<?php echo $jenis_kunjungan; ?>" readonly />
                </div>
              </div>
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Status <?php echo form_error('status') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="status" id="status" placeholder="Status" value="<?php echo $status; ?>" readonly />
                </div>
              </div>
	      
            <div class="card-body">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="catatan">Catatan <?php echo form_error('catatan') ?></label>
                <div class="col-sm-12">
                    <textarea class="form-control" rows="3" name="catatan" id="catatan" placeholder="Catatan"><?php echo $catatan; ?></textarea>
                </div>
              </div>
	   
     
        <div class="card-footer text-left">
        <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <a href="<?php echo site_url('jadwal') ?>" class="btn btn-icon icon-left btn-success">Cancel</a>
	
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
</div>
