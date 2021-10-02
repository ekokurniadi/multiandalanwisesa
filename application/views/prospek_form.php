<div class="section-body">
  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <div class="card-header">
          <h4>Form Prospek </h4>
        </div>
        <form action="<?php echo $action; ?>" method="post" class="form-horizontal">

          <div class="form-group">
            <label class="col-sm-2 control-label" for="varchar">Kode Prospek <?php echo form_error('kode_prospek') ?></label>
            <div class="col-sm-12">
              <input type="text" class="form-control" readonly name="kode_prospek" id="kode_prospek" placeholder="Kode Prospek" value="<?php echo $kode_prospek; ?>" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="date">Tanggal <?php echo form_error('tanggal') ?></label>
            <div class="col-sm-12">
              <input type="date" class="form-control" name="tanggal" id="tanggal" placeholder="Tanggal" value="<?php echo $tanggal; ?>" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="time">Jam <?php echo form_error('jam') ?></label>
            <div class="col-sm-12">
              <input type="time" class="form-control" name="jam" id="jam" placeholder="Jam" value="<?php echo $jam; ?>" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="varchar">Customer <?php echo form_error('customer') ?></label>
            <div class="container-fluid">
              <div class="row">
                <div class="col-sm-8">
                  <select name="customer" id="customer" class="form-control select2">
                    <option value="<?= $customer ?>"><?= $customer == "" ? "Choose an option" : $customer ?></option>
                    <?php foreach ($this->db->get('pelanggan')->result() as $rows) : ?>
                      <option value="<?= $rows->nama_customer ?>"><?= $rows->nama_customer ?></option>
                    <?php endforeach; ?>
                  </select>
                  <input type="hidden" class="form-control" readonly name="customer_id" id="customer_id" placeholder="Customer" value="<?php echo $customer; ?>" />
                </div>

              </div>
            </div>
          </div>
          <input type="hidden" id="id_sales" name="id_sales" value="<?= $id_sales ?>">

          <div class="form-group">
            <label class="col-sm-2 control-label" for="varchar">Status <?php echo form_error('status') ?></label>
            <div class="col-sm-12">
              <select name="status" id="status" class="form-control">
                <option value="<?= $status ?>"><?= $status == "" ? "Pilih status" : $status ?></option>
                <option value="Deal">Deal</option>
                <option value="Not Deal">Not Deal</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="varchar">Catatan <?php echo form_error('catatan') ?></label>
            <div class="col-sm-12">
              <textarea class="form-control" rows="3" name="catatan" id="catatan" placeholder="Kondisi Barang"><?php echo $catatan; ?></textarea>
            </div>
          </div>


          <div class="card-footer text-left">
            <input type="hidden" name="id" value="<?php echo $id; ?>" />
            <button type="submit" class="btn btn-primary"><span class="fa fa-edit"></span><?php echo $button ?></button>
            <a href="<?php echo site_url('prospek?session='.$id_sales) ?>" class="btn btn-icon icon-left btn-success">Cancel</a>

          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</section>
</div>