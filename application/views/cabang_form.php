
 <div class="main-content">
<section class="section">
  <div class="section-header">
    <h1> Cabang </h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item active"><a href="<?php echo base_url(); ?>dashboard"><i class="fa fa-dashboard"></i> Home</a></div>
      <div class="breadcrumb-item"><a href="#"> Cabang </a></div>
    </div>
  </div>

  <div class="section-body">
  <div class="row">
      <div class="col-12 col-md-12 col-lg-12">
        <div class="card">
        <div class="card-header">
            <h4>Form Cabang </h4>
        </div>
        <form action="<?php echo $action; ?>" method="post" class="form-horizontal">
	   
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Kode Cabang <?php echo form_error('kode_cabang') ?></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="kode_cabang" id="kode_cabang" placeholder="Kode Cabang" value="<?php echo $kode_cabang; ?>" />
                </div>
              </div>
	      
              <div class="form-group">
                <label class="col-sm-2 control-label" for="nama_cabang">Nama Cabang <?php echo form_error('nama_cabang') ?></label>
                <div class="col-sm-12">
                    <textarea class="form-control" rows="3" name="nama_cabang" id="nama_cabang" placeholder="Nama Cabang"><?php echo $nama_cabang; ?></textarea>
                </div>
              </div>
	   
     
        <div class="card-footer text-left">
        <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><span class="fa fa-edit"></span><?php echo $button ?></button> 
	    <a href="<?php echo site_url('cabang') ?>" class="btn btn-icon icon-left btn-success">Cancel</a>
	
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
</div>
