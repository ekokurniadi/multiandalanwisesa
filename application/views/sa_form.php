<script src="assets/moment/moment.min.js"></script>
<base href="<?php echo base_url(); ?>" />
<?php if (!isset($iframe)) { ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $title; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
        <li class="">H2</li>
        <li class="">Work Order</li>
        <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
      </ol>
    </section>
  <?php } ?>

  <section class="content">
    <?php
    if ($set == "form") {
      $form     = '';
      $disabled = '';
      $readonly = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'edit') {
        $form = 'save_edit';
      }
      if ($mode == 'detail') {
        $disabled = 'disabled';
      }
      if ($mode == 'insert_wo') {
        // $disabled = 'disabled';
        $form = 'save_wo';
      }
      if ($mode == 'update_wo') {
        // $disabled = 'disabled';
        $form = 'save_update_wo';
      }
      if ($mode == 'detail_wo') {
        $disabled = 'disabled';
        $form = '';
      }
    ?>
      <style>
        .isi {
          height: 25px;
          padding-left: 4px;
          padding-right: 4px;
        }
      </style>

      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <link href='assets/select2/css/select2.min.css' rel='stylesheet' type='text/css'>
      <script src="assets/jquery/jquery.min.js"></script>
      <script src='assets/select2/js/select2.min.js'></script>

      <script>
        Vue.use(VueNumeric.default);
        $(document).ready(function() {
          <?php if (isset($row)) { ?>
            getSaForm('detail');
          <?php } ?>
          <?php if (isset($row_wo)) { ?>
            getDataWO('detail');
          <?php } ?>
        })
        Vue.filter('toCurrency', function(value) {
          return accounting.formatMoney(value, "", 0, ".", ",");
          return value;
        });

        Vue.filter('cekType', function(value, arg1) {
          if (arg1 == 'persen') {
            return value + ' %';
          } else if (arg1 == 'rupiah') {
            return 'Rp. ' + accounting.formatMoney(value, "", 0, ".", ",");
          } else if (arg1 == 'value') {
            return 'Rp. ' + accounting.formatMoney(value, "", 0, ".", ",");
          } else if (arg1 == 'Percentage') {
            return value + ' %';
          } else {
            return value;
          }
        });
      </script>

      <div class="box box-default">
        <div class="box-header with-border">
          <?php if (!isset($iframe)) { ?>
            <h3 class="box-title">
              <a href="dealer/<?= $this->uri->segment(2); ?>">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
              </a>
            </h3>
            <div class="box-tools pull-right">
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
          <?php } ?>
        </div><!-- /.box-header -->
        <div class="box-body">
          <?php
          if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {
          ?>
            <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
              <strong><?php echo $_SESSION['pesan'] ?></strong>
              <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
              </button>
            </div>
          <?php
          }
          $_SESSION['pesan'] = '';

          ?>
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" id="form_" action="dealer/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <?php if ($mode == 'insert_wo' || $mode == 'detail_wo' || $mode == 'update_wo') : ?>
                      <label for="inputEmail3" class="col-sm-2 control-label">ID SA Form</label>
                      <div class="col-sm-4">
                        <input class="form-control" id="id_sa_form" name="id_sa_form" readonly value="<?= isset($row_wo) ? $row_wo->id_sa_form : '' ?>" />
                      </div>
                      <div class="col-sm-2" v-if="mode=='insert_wo'">
                        <button type="button" onclick="showModalSaForm()" class="btn btn-primary btn-flat"><i class="fa fa-search"></i></button>
                      </div>
                      <input type="hidden" value="<?= isset($row_wo->id_work_order) ? $row_wo->id_work_order : '' ?>" name="id_work_order" />
                    <?php endif ?>
                    <?php if ($mode == 'insert' || $mode == 'edit' || $mode == 'detail') : ?>
                      <div class="form-input">
                        <label for="inputEmail3" class="col-sm-2 control-label">No. Antrian *</label>
                        <div class="col-sm-4">
                          <input class="form-control" id="id_antrian" name="id_antrian" readonly value="<?= isset($row) ? $row->id_antrian : '' ?>" required />
                          <input type="hidden" class="form-control" id="id_sa_form" name="id_sa_form" readonly value="<?= isset($row) ? $row->id_sa_form : '' ?>" required />
                        </div>
                      </div>
                      <div class="col-sm-2" v-if="mode=='insert'||mode=='edit'">
                        <button type="button" onclick="showModalAntrian()" class="btn btn-primary btn-flat"><i class="fa fa-search"></i></button>
                      </div>
                    <?php endif ?>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Jenis Customer *</label>
                      <div class="col-sm-4">
                        <input type="text" v-model="sa.jenis_customer" class="form-control" name="jns_customer_datang" readonly>
                        <input type="hidden" id="id_customer" v-model="sa.id_customer" class="form-control" readonly>
                      </div>
                    </div>
                  </div>
                  <?php
                  $id_user_sa = $this->session->userdata('id_user');
                  $kry = $this->m_h2->getKaryawanDealerLogin($id_user_sa);
                  // if (isset($row_wo)) {
                  //   $id_user_sa = $row_wo->id_user_sa;
                  //   $kry = $this->m_h2->getKaryawanDealerLogin($id_user_sa);
                  // }
                  ?>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Honda ID Service Advisor</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly value="<?= $kry->row()->honda_id ?>">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Service Advisor</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly value="<?= $kry->row()->nama_lengkap ?>">
                      <input type="hidden" class="form-control" name="id_karyawan_dealer" value="<?= $kry->row()->id_karyawan_dealer ?>">
                    </div>
                  </div>
                  <?php if (!isset($iframe)) { ?>
                    <div class="col-sm-5">
                      <button type="button" class="btn btn-primary btn-flat" id="btnRiwayatServis" onclick="cekRiwayatServis()">Riwayat Servis</button>
                      <button type="button" class="btn btn-danger btn-flat" v-if="srbu==1"><i class="fa fa-flag"></i> Unit SRBU !</button>
                    </div>
                    <br><br>
                  <?php } ?>
                  <div class="col-md-12">
                    <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Data Unit & Pemilik</button><br><br>
                  </div>
                  <h4 style="padding-left: 15px"><b>Data Pemilik</b></h4>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer *</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" name="nama_customer" required :readonly="edit_cust==''" v-model="customer.nama_customer">
                      </div>
                    </div>
                    <div class="col-sm-3" v-if="mode=='insert' || mode=='edit'">
                      <button type="button" id="searchCustomer" onclick="showModalAllCustomer()" class="btn btn-flat btn-primary"><i class="fa fa-search"></i></button>
                      <span v-if="customer.id_customer">
                        <button v-if="edit_cust==''" type="button" @click.prevent="editCust" class="btn btn-flat btn-warning">Edit Data Customer</button>
                        <button v-if="edit_cust=='1'" type="button" @click.prevent="editCust" class="btn btn-flat btn-danger">Batal Edit</button>
                      </span>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Nama Sesuai STNK *</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" name="nama_stnk" required :readonly="edit_cust==''" v-model="customer.nama_stnk">
                      </div>
                    </div>
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">No. HP / No. Telp *</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" name="no_hp" required :readonly="edit_cust==''" v-model="customer.no_hp">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" name="email" v-model="customer.email">
                      </div>
                    </div>
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Alamat Saat Ini *</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" name="alamat" id="alamat" :readonly="edit_cust==''" v-model="customer.alamat" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kelamin *</label>
                      <div class="col-sm-4">
                        <select name="jenis_kelamin" class="form-control" required :disabled="edit_cust==''" v-model="customer.jenis_kelamin" required>
                          <option value="">-choose-</option>
                          <option value="Laki-laki">Laki-laki</option>
                          <option value="Perempuan">Perempuan</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Agama</label>
                      <div class="col-sm-4">
                        <?php $agama = $this->m_h2->getAgama(); ?>
                        <select name="id_agama" class="form-control" :required="edit_cust==''" :disabled="edit_cust==''" v-model="customer.id_agama">
                          <option value="">-choose-</option>
                          <?php foreach ($agama->result() as $agm) { ?>
                            <option value="<?= $agm->id_agama ?>"><?= $agm->agama ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Lahir</label>
                      <div class="col-sm-4">
                        <date-picker name="tgl_lahir" v-model="customer.tgl_lahir" id="tgl_lahir" readonly></date-picker>
                      </div>
                    </div>
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>
                      <div class="col-sm-4">
                        <?php $agama = $this->m_h2->getPekerjaan(); ?>
                        <select name="id_pekerjaan" class="form-control" :required="edit_cust==''" :disabled="edit_cust==''" v-model="customer.id_pekerjaan">
                          <option value="">-choose-</option>
                          <?php foreach ($agama->result() as $pk) { ?>
                            <option value="<?= $pk->id_pekerjaan ?>"><?= $pk->pekerjaan ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" readonly v-model="customer.kelurahan">
                    </div>
                    <div class="col-sm-1">
                      <button type="button" v-if="edit_cust!=''" onclick="showModalKelurahan()" class="btn btn-flat btn-primary"><i class="fa fa-search"></i></button>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model="customer.kecamatan">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model="customer.kabupaten">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model="customer.provinsi">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Longitude</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" :disabled="edit_cust==''" v-model="customer.longitude">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Latitude</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" :disabled="edit_cust==''" v-model="customer.latitude">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Jenis Identitas</label>
                    <div class="col-sm-4">
                      <select name="jenis_identitas" class="form-control" :required="edit_cust==''" :disabled="edit_cust==''" v-model="customer.jenis_identitas">
                        <option value="">-choose-</option>
                        <option value="ktp">KTP</option>
                        <option value="sim">SIM</option>
                        <option value="kitap">KITAP</option>
                        <option value="npwp">NPWP</option>
                      </select>
                    </div>
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">No. Identitas</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" :minlength="min_cust_id" :maxlength="max_cust_id" onkeypress="return number_only(event)" name="no_identitas" id="no_identitas" :readonly="edit_cust==''" v-model="customer.no_identitas">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="field-1" class="col-sm-2 control-label">Alamat Sama ?</label>
                    <div class="col-sm-2"><input v-model="alamat_cust_sama" type="checkbox" value="sama"></div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Alamat Identitas *</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" name="alamat_identitas" id="alamat_identitas" :readonly="edit_cust==''" v-model="customer.alamat_identitas" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" readonly v-model="customer.kelurahan_identitas">
                    </div>
                    <div class="col-sm-1">
                      <button v-if="edit_cust!=''" type="button" onclick="showModalKelurahan('identitas')" class="btn btn-flat btn-primary"><i class="fa fa-search"></i></button>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model="customer.kecamatan_identitas">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model="customer.kabupaten_identitas">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model="customer.provinsi_identitas">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-ipnut">
                      <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pembelian Customer</label>
                      <div class="col-sm-4">
                        <select name="jenis_customer_beli" class="form-control" :required="edit_cust==''" :disabled="edit_cust==''" v-model="customer.jenis_customer_beli">
                          <option value="">-choose-</option>
                          <option value="Regular">Regular</option>
                          <option value="Group Sales">Group Sales</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Tipe Coming Customer *</label>
                      <div class="col-sm-4">
                        <!--   <select name="" id="" class="form-control">
                        <option value="">-choose-</option>
                        <option value="milik">Milik</option>
                        <option value="bawa">Bawa</option>
                        <option value="pakai">Pakai</option>
                      </select>  -->
                        <input type="checkbox" v-model="tipe_coming" value="milik" :disabled="mode=='detail' || mode=='detail_wo' || mode=='insert_wo' || mode=='update_wo'"> Pemilik &nbsp;&nbsp;
                        <span v-if="ada_milik==0">
                          <input type="checkbox" v-if="ada_milik==0" v-model="tipe_coming" value="bawa" :disabled="mode=='detail' || mode=='detail_wo' || mode=='insert_wo' || mode=='update_wo'"> Pembawa &nbsp;&nbsp;
                        </span>
                        <span v-if="ada_milik==0">
                          <input type="checkbox" v-if="ada_milik==0" v-model="tipe_coming" value="pakai" :disabled="mode=='detail' || mode=='detail_wo' || mode=='insert_wo' || mode=='update_wo'"> Pemakai
                        </span>
                      </div>
                    </div>
                  </div>
                  <h4 style="padding-left: 15px"><b>Data Pembawa</b></h4>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Nama *</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" v-model="pembawa.nama_pembawa" :readonly="coming_cust=='milik'" required>
                      </div>
                    </div>
                    <div class="col-sm-1" v-if="pilih_pembawa=='1' && (mode=='insert' || mode=='edit')">
                      <button id="btnCariPembawa" type="button" onclick="showModalPembawa()" class="btn btn-flat btn-primary"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">No. HP / No. Telp *</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" v-model="pembawa.no_hp" :readonly="coming_cust=='milik'" required name="no_hp_pembawa">
                      </div>
                    </div>
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                      <div class="col-sm-4">
                        <input type="email" class="form-control" v-model="pembawa.email" name="email_pembawa">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Alamat Saat Ini *</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" v-model="pembawa.alamat_saat_ini" :readonly="coming_cust=='milik'" required name="alamat_saat_ini_pembawa">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kelamin *</label>
                      <div class="col-sm-4">
                        <select v-model="pembawa.jenis_kelamin" class="form-control" :disabled="coming_cust=='milik'" name="jk_pembawa" required>
                          <option value="">-choose-</option>
                          <option value="Laki-laki">Laki-laki</option>
                          <option value="Perempuan">Perempuan</option>
                        </select>
                      </div>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Agama</label>
                    <div class="col-sm-4">
                      <?php $agama = $this->m_h2->getAgama(); ?>
                      <select name="id_agama" class="form-control" :readonly="coming_cust=='milik'" v-model="pembawa.id_agama">
                        <option value="">-choose-</option>
                        <?php foreach ($agama->result() as $agm) { ?>
                          <option value="<?= $agm->id_agama ?>"><?= $agm->agama ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" v-model="pembawa.kelurahan" readonly>
                    </div>
                    <div class="col-sm-1">
                      <button type="button" v-if="pilih_pembawa=='1' && coming_cust!='milik'" onclick="showModalKelurahan('pembawa')" class="btn btn-flat btn-primary"><i class="fa fa-search"></i></button>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" v-model="pembawa.kecamatan" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kabupaten/Kota</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" v-model="pembawa.kabupaten" readonly>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" v-model="pembawa.provinsi" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Jenis Identitas</label>
                    <div class="col-sm-4">
                      <select name="jenis_identitas" class="form-control" :disabled="coming_cust=='milik'" v-model="pembawa.jenis_identitas">
                        <option value="">-choose-</option>
                        <option value="ktp">KTP</option>
                        <option value="sim">SIM</option>
                        <option value="kitap">KITAP</option>
                        <option value="npwp">NPWP</option>
                      </select>
                    </div>
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">No. Identitas</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" :readonly="coming_cust=='milik'" v-model="pembawa.no_identitas" :minlength="min_pembawa_id" onkeypress="return number_only(event)" :maxlength="max_pembawa_id">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="field-1" class="col-sm-2 control-label">Alamat Sama ?</label>
                    <div class="col-sm-2"><input v-model="alamat_pembawa_sama" type="checkbox" value="sama"></div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Alamat Identitas *</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" :readonly="coming_cust=='milik'" v-model="pembawa.alamat_identitas" required name="alamat_id_pembawa">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" readonly v-model="pembawa.kelurahan_identitas">
                    </div>
                    <div class="col-sm-1">
                      <button type="button" v-if="pilih_pembawa=='1' && coming_cust!='milik'" onclick="showModalKelurahan('identitas_pembawa')" class="btn btn-flat btn-primary"><i class="fa fa-search"></i></button>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model="pembawa.kecamatan_identitas">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model="pembawa.kabupaten_identitas">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model="pembawa.provinsi_identitas">
                    </div>
                  </div>
                  <div class="form-group" v-if="pilih_pembawa==1">
                    <label for="inputEmail3" class="col-sm-2 control-label">Hubungan dengan Pemilik *</label>
                    <div class="col-sm-4">
                      <select v-model="pembawa.hubungan_dengan_pemilik" class="form-control" :disabled="mode=='detail' || mode=='detail_wo' || mode=='insert_wo' || mode=='update_wo'" <?= $disabled ?> required>
                        <option value="">-choose-</option>
                        <option value="Keluarga">Keluarga</option>
                        <option value="Teman/Kerabat">Teman/Kerabat</option>
                        <option value="Karyawan">Karyawan</option>
                        <option value="Lainnya">Lainnya</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group" v-if="ada_milik==0">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Pemakai</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" v-model="sa.nama_pemakai" :disabled="mode=='detail'|| mode=='detail_wo' || mode=='insert_wo'|| mode=='update_wo'" required>
                    </div>
                  </div>
                  <h4 style="padding-left: 15px"><b>Data Unit</b></h4>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">No. Polisi *</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" name="no_polisi" v-model="customer.no_polisi" :readonly="edit_cust==''" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Kode Tipe Unit *</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" v-model="sa.id_tipe_kendaraan" readonly required name="id_tipe_kendaraan" id='id_tipe_kendaraan'>
                      </div>
                    </div>
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi Tipe Unit *</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" v-model="sa.tipe_ahm" readonly name="desk_tipe_unit" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">No. Mesin *</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" v-model="customer.no_mesin" :readonly="sa.id_dealer_h1!=null || mode=='detail_wo' || mode=='insert_wo' || edit_cust==''" required name="no_mesin">
                      </div>
                    </div>
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">No. Rangka *</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" v-model="customer.no_rangka" :readonly="sa.id_dealer_h1!=null || mode=='detail_wo' || mode=='insert_wo' || edit_cust==''" required name="no_rangka">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Tahun Motor *</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" v-model="customer.tahun_produksi" :readonly="sa.id_dealer_h1!=null || mode=='detail_wo' || mode=='insert_wo' || edit_cust==''" required name="tahun_motor" onkeypress="return number_only(event)">
                      </div>
                    </div>
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Pembelian *</label>
                      <div class="col-sm-4">
                        <date-picker name="tgl_pembelian" v-model="sa.tgl_pembelian" id="tgl_pembelian" readonly required></date-picker>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Informasi Bahan Bakar *</label>
                      <div class="col-sm-4">
                        <select name="informasi_bensin" v-model="sa.informasi_bensin" class="form-control" :disabled="mode=='detail' || mode=='detail_wo' || mode=='insert_wo' || mode=='update_wo'" <?= $disabled ?> required>
                          <option value="">-choose-</option>
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">KM Saat Ini *</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" id="km_terakhir" name="km_terakhir" v-model="sa.km_terakhir" :disabled="mode=='detail' || mode=='detail_wo' || mode=='insert_wo' || mode=='update_wo'" <?= $disabled ?> required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-6"></div>
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">KM Service Sebelumnya</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" v-model="sa.km_sebelumnya" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-warning btn-flat btn-sm" disabled>Keluhan/Kebutuhan Konsumen</button><br><br>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Alasan Ke AHASS *</label>
                      <div class="col-sm-3">
                        <select name="alasan_ke_ahass" v-model="sa.alasan_ke_ahass" class="form-control" :disabled="mode=='detail' || mode=='detail_wo' || mode=='insert_wo' || mode=='update_wo'" <?= $disabled ?> required>
                          <option value="">-choose-</option>
                          <option value="SMS">SMS</option>
                          <option value="Telepon">Telepon</option>
                          <option value="Stiker Reminder">Stiker Reminder</option>
                          <option value="Inisiatif sendiri">Inisiatif sendiri</option>
                          <option value="Lainnya">Lainnya</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Keluhan Konsumen (Kebutuhan Konsumen) *</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" v-model="sa.keluhan_konsumen" name="keluhan_konsumen" value="<?= isset($row) ? $row->keluhan_konsumen : '' ?>" :disabled="mode=='detail' || mode=='detail_wo' || mode=='insert_wo' || mode=='update_wo'" <?= $disabled ?> required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Rekomendasi SA *</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" v-model="sa.rekomendasi_sa" name="rekomendasi_sa" value="<?= isset($row) ? $row->rekomendasi_sa : '' ?>" :disabled="mode=='detail' || mode=='detail_wo' || mode=='insert_wo' || mode=='update_wo'" <?= $disabled ?> required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Asal Unit Entry *</label>
                      <div class="col-sm-4">
                        <select name="asal_unit_entry" v-model="sa.asal_unit_entry" class="form-control" :disabled="mode=='detail' || mode=='detail_wo' || mode=='insert_wo' || mode=='update_wo'" <?= $disabled ?> required>
                          <option value="">-choose-</option>
                          <?php $activity_promotion = $this->m_sm->getActivityPromotion()->result(); ?>
                          <?php foreach ($activity_promotion as $act) { ?>
                            <option value="<?= $act->name ?>"><?= $act->name ?></option>
                          <?php } ?>
                          <!--<option value="Others">Others</option>-->
                        </select>
                      </div>
                      <div class="col-sm-4">
                        <select name="activity_capacity_id" v-model="sa.activity_capacity_id" class="form-control" :disabled="mode=='detail' || mode=='detail_wo' || mode=='insert_wo' || mode=='update_wo'" <?= $disabled ?> required>
                          <option value="">-choose-</option>
                          <?php $activity_cap = $this->m_sm->getActivityCapacity()->result(); ?>
                          <?php foreach ($activity_cap as $act) { ?>
                            <option value="<?= $act->id ?>"><?= $act->keterangan ?></option>
                          <?php } ?>
                          <!--<option value="Others">Others</option>-->
                        </select>
                      </div>
                    </div>
                  </div>
                  <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-primary btn-flat btn-sm" disabled>Pekerjaan & Kebutuhan Parts</button><br><br>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Job Return *</label>
                      <div class="col-sm-4">
                        <select class="form-control" name="job_return" :disabled="mode=='detail_wo' || mode=='detail' || mode=='update_wo'" v-model="sa.job_return" required>
                          <option value="">- choose -</option>
                          <option value="1">Ya</option>
                          <option value="0" selected>Tidak</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Motor Ditinggal *</label>
                      <div class="col-sm-4">
                        <select class="form-control" name="motor_ditinggal" :disabled="mode=='detail_wo' || mode=='detail' || mode=='update_wo'" v-model="sa.motor_ditinggal" required>
                          <option value="">- choose -</option>
                          <option value="1">Ya</option>
                          <option value="0">Tidak</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group" v-if="job_return=='ya'">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nomor WO Sebelumnya</label>
                    <div class="col-sm-4">
                      <select name="id_wo_job_return" id="id_wo_job_return" class="form-control" :disabled="mode=='detail_wo' || mode=='detail' || mode=='update_wo'">
                        <?php if (isset($row_wo)) { ?>
                          <option value="<?= $row_wo->id_wo_job_return ?>"><?= $row_wo->id_wo_job_return ?> | <?= $row_wo->nama_customer ?></option>
                        <?php } else { ?>
                          <option value="">- choose -</option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div id="tabel_pekerjaan">
                    <table class="table table-bordered table-striped">
                      <thead>
                        <th width="38%">Deskripsi Pekerjaan & Kebutuhan Part</th>
                        <th width="5%" v-if="mode=='insert'|| mode=='edit' || mode=='update_wo'" align='center'>Action</th>
                        <th width="5%" v-if="mode=='insert_wo'">Verifikasi</th>
                      </thead>
                      <tbody>
                        <tr v-for="(dt, index) of details" v-bind:style="[dt.pekerjaan_batal==1?{'background':'#f1d6ce'}:{}]">
                          <td>
                            <h4>Deskripsi Pekerjaan</h4>
                            <table class="table table-condensed table-bordered" v-bind:style="[dt.pekerjaan_batal==1?{'background':'#f1d6ce'}:{}]">
                              <tr>
                                <td>Kategori Pekerjaan</td>
                                <td colspan=3>{{dt.kategori}}</td>
                              </tr>
                              <tr>
                                <td>Type Pekerjaan</td>
                                <td><span v-if="dt.id_type!=''">{{dt.id_type}} | {{dt.desk_type}}</span></td>
                                <td>Pekerjaan</td>
                                <td><span v-if="dt.jasa!=''">{{dt.jasa}} | {{dt.deskripsi}}</span></td>
                              </tr>
                              <tr>
                                <td>Tipe Servis</td>
                                <td colspan=3>{{dt.tipe_servis}}</td>
                              </tr>
                              <tr>
                                <td>Merupakan Pekerjaan Luar</td>
                                <td>{{dt.pekerjaan_luar==1?'Ya':'Tidak'}}</td>
                                <td>Promo</td>
                                <td v-if="dt.id_promo!=''">:
                                  <span style="font-weight:bold">{{dt.id_promo}}</span><br>
                                  Nama Promo : {{dt.nama_promo}} <br>
                                  Tipe : {{dt.tipe_diskon}} <br>
                                  Diskon : {{dt.diskon | cekType(dt.tipe_diskon)}}
                                </td>
                                <td v-if="dt.id_promo==''">Tidak ada</td>
                              </tr>
                              <tr v-if="dt.id_type=='C1' || dt.id_type=='C2'">
                                <td>FRT</td>
                                <td>{{dt.frt_claim}}</td>
                                <td>Labour Cost</td>
                                <td>{{dt.labour_cost | toCurrency}}</td>
                              </tr>
                              <tr>
                                <td v-if="dt.id_type=='C1' || dt.id_type=='C2'">Estimasi Harga Jasa</td>
                                <td v-else>Estimasi Biaya Service</td>
                                <td>{{estimasi_biaya_servis(dt) | toCurrency}}</td>
                                <td>Estimasi Waktu Pekerjaan</td>
                                <td>{{dt.waktu}} menit</td>
                              </tr>
                            </table>
                            <h4>Kebutuhan Parts</h4>
                            <h5 style='padding-left:10px;font-weight:bold;'>Apakah Membutuhkan Part : {{dt.need_parts}}</h5>
                            <table class="table table-bordered" style="margin-bottom: 10px;width: 100%" v-bind:style="[dt.pekerjaan_batal==1?{'background':'#f1d6ce'}:{}]">
                              <tr v-if="dt.need_parts=='yes' || dt.need_parts=='Yes'">
                                <td><b>No.</b></td>
                                <td><b>Nomor Parts</b></td>
                                <td><b>Part Deskripsi</b></td>
                                <td><b>Jenis Order</b></td>
                                <td><b>Kuantitas</b></td>
                                <td width="10%" v-if="is_claim(dt)==0"><b>Tipe Disc.</b></td>
                                <td v-if="is_claim(dt)==0"><b>Promo</b></td>
                                <td v-if="dt.id_type=='C1' || dt.id_type=='C2'"><b>HET/1.1</b></td>
                                <td v-else><b>HET</b></td>
                                <td><b>Total HET</b></td>
                                <td><b>Total Diskon</b></td>
                                <td><b>Subtotal</b></td>
                                <td v-if="dt.id_type=='C1' || dt.id_type=='C2'"><b>Part Utama</b></td>
                                <td v-if="mode=='update_wo'"><b>Status Picking</b></td>
                                <td v-if="mode=='update_wo' && dt.pekerjaan_batal!=1"><b>Aksi</b></td>
                              </tr>
                              <tr v-for="(prt, index_prt) of dt.parts">
                                <td>{{index_prt+1}}</td>
                                <td>{{prt.id_part}}</td>
                                <td>{{prt.nama_part}}</td>
                                <td>{{prt.jenis_order}}
                                  <span v-if="prt.jenis_order=='HLO'"><br><b>Order To : <br>{{prt.order_to_name}}</b></span>
                                </td>
                                <td>{{prt.qty}}</td>
                                <td v-if="is_claim(dt)==0"><span v-if="prt.tipe_diskon!=''">{{prt.tipe_diskon}} </br> {{ prt.diskon_value | cekType(prt.tipe_diskon)}}</span></td>
                                <td v-if="is_claim(dt)==0">{{prt.id_promo}}</td>
                                <td>{{prt.harga_dealer_user | toCurrency }}</td>
                                <td>{{subTotalPart(prt,'total_het') | toCurrency }}</td>
                                <td>{{subTotalPart(prt,'total_diskon') | toCurrency }}</td>
                                <td>{{subTotalPart(prt,'subtotal') | toCurrency }}</td>
                                <td v-if="dt.id_type=='C1' || dt.id_type=='C2'">{{prt.part_utama==1?'Yes':'No'}}</td>
                                <td v-if="mode=='update_wo'">{{prt.status_picking_slip}}</td>
                                <td v-if="mode=='update_wo' && dt.pekerjaan_batal!=1" align='center'>
                                  <button v-if="(prt.status_picking_slip=='Cancel' || prt.status_picking_slip==null)" style='margin-bottom:1px' type="button" @click.prevent="editDetailsParts(index_prt,prt,index)" class="btn btn-flat btn-warning btn-xs"><i class="fa fa-pencil"></i></button>
                                  <button v-if="(prt.status_picking_slip=='Cancel' || prt.status_picking_slip==null)" type="button" @click.prevent="delDetailsParts(index_prt,prt,index)" class="btn btn-flat btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                                </td>
                              </tr>
                              <tr v-if="mode=='update_wo' && dt.pekerjaan_batal!=1 && dt.need_parts=='Yes'">
                                <td>
                                  <button @click.prevent="form_.showModalPart()" class="btn btn-primary btn-xs">Pilih</button>
                                </td>
                                <td><span v-if="dtl_part.id_part!=''">{{dtl_part.id_part}}</span></td>
                                <td><span v-if="dtl_part.id_part!=''">{{dtl_part.nama_part}}</span></td>
                                <td>{{dtl_part.jenis_order}}
                                  <span v-if="dtl_part.jenis_order=='HLO'"><br><b>Order To : <br>{{dtl_part.order_to_name}}</b></span>
                                </td>
                                <td><input type="number" class="form-control isi" v-model="dtl_part.qty"></td>
                                <td v-if="is_claim(dt)==0">
                                  <select class='form-control' v-model="dtl_part.tipe_diskon">
                                    <option value="">-No Disc-</option>
                                    <option value="Percentage">Percentage</option>
                                    <option value="FoC">FoC</option>
                                    <option value="Value">Value</option>
                                  </select>
                                  <vue-numeric v-if='dtl_part.tipe_diskon != ""' style='margin-top: 10px' :disabled="mode == 'detail'" class="form-control" thousand-separator="." v-model="dtl_part.diskon_value" />
                                </td>
                                <td v-if="is_claim(dt)==0">
                                  <input style="display:inline;width:75%" v-model='dtl_part.id_promo' type="text" class="form-control isi" readonly onclick="showModalPromoPart()" placeholder='Pilih Promo'>
                                  <button id="btnSearchPromo" onclick="showModalPromoPart()" type="button" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-search"></i></button>
                                </td>
                                <td>{{dtl_part.harga_dealer_user | toCurrency}}</td>
                                <td>{{subTotalPart(dtl_part,'total_het') | toCurrency}}</td>
                                <td>{{subTotalPart(dtl_part,'total_diskon') | toCurrency}}</td>
                                <td>{{subTotalPart(dtl_part,'subtotal') | toCurrency}}</td>
                                <td v-if="dt.id_type=='C1' || dt.id_type=='C2'">
                                  <input v-model='dtl_part.part_utama' type="checkbox" true-value='1' false-value='0'>
                                </td>
                                <td align="center" width="5%" colspan=2><button type="button" @click.prevent="addPart(index)" class="btn btn-flat btn-primary btn-xs"><i class="fa fa-plus"></i> Tambah Part</button></td>
                              </tr>
                            </table>
                            <h4 v-if="dt.parts_demand.length > 0">Record Parts Demand</h4>
                            <table class="table table-bordered" style="margin-bottom: 0px;width: 100%" v-if="dt.parts_demand.length > 0">
                              <tr>
                                <th>No.</th>
                                <th>Nomot Parts</th>
                                <th>Part Deskripsi</th>
                                <th>Qty</th>
                                <th>Alasan</th>
                              </tr>
                              <tr v-for="(prt, index) of dt.parts_demand">
                                <td>{{index+1}}</td>
                                <td>{{prt.id_part}}</td>
                                <td>{{prt.nama_part}}</td>
                                <td>{{prt.qty}}</td>
                                <td>{{prt.alasan}}</td>
                              </tr>
                              <tr>
                                <td colspan=4><b>Total</b></td>
                                <td><b>{{totQtyPart(dt.parts_demand)}}</b></td>
                              </tr>
                            </table>
                          </td>
                          <td v-if="mode=='insert'|| mode=='edit' || mode=='update_wo'" style="vertical-align: middle;text-align: center;">
                            <button v-if="mode=='insert' || mode=='edit' || dt.update" type="button" @click.prevent="updatePekerjaan(index)" class="btn btn-flat btn-warning" style="margin-bottom:3px"><i class="fa fa-pencil"></i></button>
                            <button v-if="mode=='insert' || mode=='edit' || dt.update" type="button" @click.prevent="delDetails(index)" class="btn btn-flat btn-danger"><i class="fa fa-trash"></i></button>
                            <button v-if="mode=='update_wo' && dt.pekerjaan_batal==0" type="button" @click.prevent="setBatal(index)" class="btn btn-flat btn-danger" style="margin-bottom:3px">Batal</button>
                            <button v-if="mode=='update_wo' && dt.pekerjaan_batal==1" type="button" @click.prevent="setBatal(index)" class="btn btn-flat btn-success" style="margin-bottom:3px">Lanjut</button>
                          </td>
                          <td align="center" style="background-color:red;" v-if="mode=='insert_wo'"><input type="checkbox" v-model="dt.masukkan_wo" required style="width:50px;font-size:25px;color:red;"></td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr v-if="insPekerjaan==1" v-if="mode=='insert'|| mode=='edit' || mode=='update_wo'" id="fieldEditPekerjaan">
                          <td colspan=2>
                            <h4>Deskripsi Pekerjaan</h4>
                            <table class="table table-condensed table-bordered">
                              <tr>
                                <td>Kategori Pekerjaan</td>
                                <td colspan=3>
                                  <input style="width:80%; display:inline;margin-right:10px" @click.prevent="showModalJasa" type="text" class="form-control isi" v-model="dtl.kategori" readonly>
                                  <button id="btnSearchJasa" @click.prevent="showModalJasa" type="button" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-search"></i> Pilih Pekerjaan</button>
                                </td>
                              </tr>
                              <tr>
                                <td>Type Pekerjaan</td>
                                <td><span v-if="dtl.id_type!=''">{{dtl.id_type}} | {{dtl.desk_type}}</span></td>
                                <td>Pekerjaan</td>
                                <td><span v-if="dtl.jasa!=''">{{dtl.jasa}} | {{dtl.deskripsi}}</span></td>
                              </tr>
                              <tr>
                                <td>Tipe Servis</td>
                                <td colspan=3>
                                  <select v-model="dtl.id_tipe_servis" id="tipe_servis">
                                    <?php $tipe_servis = $this->m_wo->setupTipeServis(); ?>
                                    <option value="">- choose -</option>
                                    <?php foreach ($tipe_servis->result() as $val) { ?>
                                      <option value="<?= $val->id ?>"><?= $val->tipe_servis ?></option>
                                    <?php } ?>
                                  </select>
                                </td>
                              </tr>
                              <tr>
                                <td>Merupakan Pekerjaan Luar ?</td>
                                <td>
                                  <select style='width:100%' v-model="dtl.pekerjaan_luar">
                                    <option value="">-choose-</option>
                                    <option value="1">Ya</option>
                                    <option value="0">Tidak</option>
                                  </select>
                                </td>
                                <td v-if="dtl.id_type=='C1' || dtl.id_type=='C2'"></td>
                                <td v-else>Promo</td>
                                <td v-if="dtl.id_type=='C1' || dtl.id_type=='C2'"></td>
                                <td v-else>
                                  <table style="width:100%">
                                    <tr>
                                      <td width="85%">
                                        <input onclick="showModalPromoServis()" type="text" class="form-control isi" readonly v-model="dtl.id_promo">
                                      </td>
                                      <td width="15%" align="right">
                                        <button style='margin-left:10px' id="btnSearchPromo" onclick="showModalPromoServis()" type="button" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-search"></i> Pilih Promo</button>
                                      </td>
                                    </tr>
                                    <tr v-if="dtl.id_promo!=''" style="color:#0fa7ff">
                                      <td colspan=2>
                                        Nama Promo : {{dtl.nama_promo}} <br>
                                        Tipe : {{dtl.tipe_diskon}} <br>
                                        Diskon : {{dtl.diskon | cekType(dtl.tipe_diskon)}}
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr v-if="dtl.id_type=='C1' || dtl.id_type=='C2'">
                                <td>FRT</td>
                                <td>
                                  <select name='frt_claim' v-model='dtl.frt_claim'>
                                    <option value=''>-choose-</option>
                                    <?php
                                    for ($i = 0; $i <= 8; $i += 0.1) { ?>
                                      <option value='<?= $i ?>'><?= $i ?></option>
                                    <?php } ?>
                                  </select>
                                </td>
                                <td>Labour Cost</td>
                                <td colspan>{{dtl.labour_cost | toCurrency}}</td>
                              </tr>
                              <tr>
                                <td v-if="dtl.id_type=='C1' || dtl.id_type=='C2'">Estimasi Harga Jasa</td>
                                <td v-else>Estimasi Biaya Service</td>
                                <td>{{estimasi_biaya_servis(dtl) | toCurrency}}</td>
                                <td>Estimasi Waktu Pekerjaan</td>
                                <td>{{dtl.waktu}} menit</td>
                              </tr>
                            </table>
                            <h4>Kebutuhan Parts</h4>
                            <div class="form-group">
                              <label for="inputEmail3" class="col-sm-3 control-label">Apakah Membutuhkan Part ?</label>
                              <div class="col-sm-2">
                                <select class='form-control' v-model="dtl.need_parts">
                                  <option value="" selected>- choose -</option>
                                  <option value="yes">Yes</option>
                                  <option value="no">No</option>
                                </select>
                              </div>
                            </div>
                            <table class="table table-bordered" style="margin-bottom: 10px;width: 100%">
                              <tr v-if="dtl.need_parts=='yes'">
                                <td><b>No.</b></td>
                                <td><b>Nomor Parts</b></td>
                                <td><b>Part Deskripsi</b></td>
                                <td width="10%"><b>Jenis Order</b></td>
                                <td><b>Kuantitas</b></td>
                                <td width="13%" v-if="is_claim(dtl)==0"><b>Tipe Disc.</b></td>
                                <td v-if="is_claim(dtl)==0"><b>Promo</b></td>
                                <td v-if="dtl.id_type=='C1' || dtl.id_type=='C2'"><b>HET/1.1</b></td>
                                <td v-else><b>HET</b></td>
                                <td><b>Total HET</b></td>
                                <td><b>Total Diskon</b></td>
                                <td><b>Subtotal</b></td>
                                <td v-if="dtl.id_type=='C1' || dtl.id_type=='C2'"><b>Part Utama</b></td>
                                <td><b>Aksi</b></td>
                              </tr>
                              <tr v-for="(prt, index) of dtl.parts">
                                <td>{{index+1}}</td>
                                <td>{{prt.id_part}}</td>
                                <td>{{prt.nama_part}}</td>
                                <td>{{prt.jenis_order}}
                                  <span v-if="prt.jenis_order=='HLO'"><br><b>Order To : <br>{{prt.order_to_name}}</b></span>
                                </td>
                                <td>{{prt.qty}}</td>
                                <td v-if="is_claim(dtl)==0"><span v-if="prt.tipe_diskon!=''">{{prt.tipe_diskon}} </br> {{ prt.diskon_value | cekType(prt.tipe_diskon)}}</span></td>
                                <td v-if="is_claim(dtl)==0">{{prt.id_promo}}</td>
                                <td>{{prt.harga_dealer_user | toCurrency }}</td>
                                <td>{{subTotalPart(prt,'total_het') | toCurrency }}</td>
                                <td>{{subTotalPart(prt,'total_diskon') | toCurrency }}</td>
                                <td>{{subTotalPart(prt,'subtotal') | toCurrency }}</td>
                                <td v-if="dtl.id_type=='C1' || dtl.id_type=='C2'">
                                  <input v-model='prt.part_utama' type="checkbox" true-value='1' false-value='0' disabled>
                                </td>
                                <td align="center"><button type="button" @click.prevent="delPart(index)" class="btn btn-flat btn-danger btn-xs"><i class="fa fa-trash"></i></button></td>
                              </tr>
                              <tr v-if="dtl.need_parts=='yes'">
                                <td>
                                  <button @click.prevent="form_.showModalPart()" class="btn btn-primary btn-xs">Pilih</button>
                                </td>
                                <td><span v-if="dtl_part.id_part!=''">{{dtl_part.id_part}}</span></td>
                                <td><span v-if="dtl_part.id_part!=''">{{dtl_part.nama_part}}</span></td>
                                <td>{{dtl_part.jenis_order}}
                                  <span v-if="dtl_part.jenis_order=='HLO'"><br><b>Order To : <br>{{dtl_part.order_to_name}}</b></span>
                                </td>
                                <td><input type="number" class="form-control isi" v-model="dtl_part.qty"></td>
                                <td v-if="is_claim(dtl)==0">
                                  <select class='form-control' v-model="dtl_part.tipe_diskon">
                                    <option value="">-No Disc-</option>
                                    <option value="Percentage">Percentage</option>
                                    <option value="FoC">FoC</option>
                                    <option value="Value">Value</option>
                                  </select>
                                  <vue-numeric v-if='dtl_part.tipe_diskon != ""' style='margin-top: 10px' :disabled="mode == 'detail'" class="form-control" thousand-separator="." v-model="dtl_part.diskon_value" />
                                </td>
                                <td v-if="is_claim(dtl)==0">
                                  <input style="display:inline;width:75%" v-model='dtl_part.id_promo' type="text" class="form-control isi" readonly onclick="showModalPromoPart()" placeholder='Pilih Promo'>
                                  <button id="btnSearchPromo" onclick="showModalPromoPart()" type="button" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-search"></i></button>
                                </td>
                                <td>{{dtl_part.harga_dealer_user | toCurrency}}</td>
                                <td>{{subTotalPart(dtl_part,'total_het') | toCurrency}}</td>
                                <td>{{subTotalPart(dtl_part,'total_diskon') | toCurrency}}</td>
                                <td>{{subTotalPart(dtl_part,'subtotal') | toCurrency}}</td>
                                <td v-if="dtl.id_type=='C1' || dtl.id_type=='C2'">
                                  <input v-if="dtl.parts.length<1" v-model='dtl_part.part_utama' type="checkbox" true-value='1' false-value='0'>
                                </td>
                                <td align="center" width="5%"><button type="button" @click.prevent="addPart()" class="btn btn-flat btn-primary btn-xs"><i class="fa fa-plus"></i> Tambah Part</button></td>
                              </tr>
                              <!-- <tr v-if="dtl.need_parts=='yes'">
                                <td colspan=3><b>Total Tanpa PPN</b></td>
                                <td colspan=2><b>{{totQtyPart(dtl.parts)}}</b></td>
                              </tr> -->
                            </table>
                            <h4 v-if="dtl.parts_demand.length > 0">Record Parts Demand</h4>
                            <table class="table table-bordered" style="margin-bottom: 0px;width: 100%" v-if="dtl.parts_demand.length > 0">
                              <tr>
                                <th>No.</th>
                                <th>Nomor Parts</th>
                                <th>Part Deskripsi</th>
                                <th>Qty</th>
                                <th>Alasan</th>
                                <th v-if="mode=='insert'" width="13%" align="center">Aksi</th>
                              </tr>
                              <tr v-for="(prt, index) of dtl.parts_demand">
                                <td>{{index+1}}</td>
                                <td>{{prt.id_part}}</td>
                                <td>{{prt.nama_part}}</td>
                                <td>{{prt.qty}}</td>
                                <td>{{prt.alasan}}</td>
                                <td v-if="mode=='insert'">
                                  <button type="button" @click.prevent="editRecordPartDemand('detail',index)" class="btn btn-flat btn-warning btn-xs"><i class="fa fa-pencil"></i></button>
                                  <button type="button" @click.prevent="delRecordPartDemand('detail',index)" class="btn btn-flat btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                                </td>
                              </tr>
                            </table>
                            <div class="col-sm-12" align='center'>
                              <button id="btnAddDetails" class="btn btn-success btn-flat" type="button" @click.prevent="addDetails()"><b>Simpan Pekerjaan & Sparepart</b></button>
                            </div>
                          </td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                  <table class="table table-bordered table-condensed" style="background:#f9f9f9;font-weight:600">
                    <tr>
                      <td width="19%">Estimasi Waktu Pendaftaran</td>
                      <td>: {{estimasi_waktu_daftar}} WIB</td>
                      <td>Total Qty Part</td>
                      <td>: {{totals('qty_parts')}}</td>
                    </tr>
                    <tr>
                      <td>Total FRT</td>
                      <td>: {{tot_frt}} Menit</td>
                      <td>Total Harga Part <span style='color:blue' v-if="dtl.id_type=='C1' || dtl.id_type=='C2'"><br><i>Untuk Claim C1/C2 harga parts adalah HET / 1.1</i></span></td>
                      <td>: {{totals('biaya_parts') | toCurrency}}</td>
                    </tr>
                    <tr>
                      <td>Estimasi Waktu Selesai</td>
                      <td>: {{estimasi_waktu_selesai}} WIB</td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>Total Biaya Service</td>
                      <td>: {{totals('biaya_servis') | toCurrency}}</td>
                      <td></td>
                      <td></td>
                    </tr>
                    <!-- <tr style="font-size:12pt;text-align:right" v-if="pkp==1">
                      <td colspan=3><b>Total Tanpa PPN</b></td>
                      <td><b> {{totals('tot_tanpa_ppn') | toCurrency}}</b></td>
                    </tr>
                    <tr style="font-size:12pt;text-align:right" v-if="pkp==1">
                      <td colspan=3><b>PPN</b></td>
                      <td><b> {{totals('ppn') | toCurrency}}</b></td>
                    </tr> -->
                    <tr style="font-size:12pt;text-align:right">
                      <td colspan=3><b>Grand Total</b></td>
                      <td><b> {{totals('grand_total') | toCurrency}}</b></td>
                    </tr>
                    <tr v-if="insPekerjaan==0 && (mode=='insert'|| mode=='edit' || mode=='update_wo')">
                      <td colspan=4 align="center">
                        <button style="width:40%;font-size:16px;padding:10px" class="btn btn-primary" @click.prevent="setInsPekerjaan"><i class="fa fa-plus"></i> <b>Tambah Pekerjaan</b></button>
                      </td>
                    </tr>
                  </table>
                </div>
                <br>
                <div class="form-group">
                  <div class="form-input">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tipe Pembayaran <i style="color: red">*</i></label>
                    <div class="col-sm-4">
                      <select name="tipe_pembayaran" id="tipe_pembayaran" class="form-control" required <?= $disabled ?> v-model="sa.tipe_pembayaran" :readonly="mode=='detail'||mode=='insert_wo'||mode=='detail_wo' || mode=='update_wo'">
                        <option value="">-choose-</option>
                        <option value="cash">Cash</option>
                        <option value="top">TOP</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Buku Khusus Claim C2</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" v-model="sa.no_buku_claim_c2" name="no_buku_claim_c2" :readonly="mode=='detail_wo' || mode=='insert_wo' || mode=='detail' || mode=='update_wo'">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Claim C2</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" v-model="sa.no_claim_c2" name="no_claim_c2" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Konfirmasi Pekerjaan Tambahan</label>
                  <div class="col-sm-4">
                    <select name="konfirmasi_pekerjaan_tambahan" id="konfirmasi_pekerjaan_tambahan" class="form-control" <?= $disabled ?> v-model="sa.konfirmasi_pekerjaan_tambahan" :readonly="mode=='detail'||mode=='insert_wo'||mode=='detail_wo' || mode=='update_wo'">
                      <option value="">-choose-</option>
                      <option value="langsung">Langsung Dilakukan</option>
                      <option value="via_no_hp">Konfirmasi Via No. HP</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Catatan Tambahan</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" v-model="sa.catatan_tambahan" name="catatan_tambahan" :readonly="mode=='detail_wo' || mode=='insert_wo' || mode=='detail' || mode=='update_wo'">
                  </div>
                </div>

                <div class="form-group">
                  <div class="form-input">
                    <label for="inputEmail3" class="col-sm-2 control-label">PIT <span v-if="mode=='insert_wo'"> *</span></label>
                    <div class="col-sm-4">
                      <select name="id_pit" id="id_pit" class="form-control" :required="mode=='insert_wo'" <?= $disabled ?>>
                        <?php
                        $opt_pit = '<option value="">- choose -</option>';
                        if (isset($row)) {
                          $opt_pit = '<option value="' . $row->id_pit . '">' . $row->id_pit . ' | ' . $row->jenis_pit . '</option>';
                        }
                        if (isset($row_wo)) {
                          $opt_pit = '<option value="' . $row_wo->id_pit . '">' . $row_wo->id_pit . ' | ' . $row_wo->jenis_pit . '</option>';
                        }
                        echo $opt_pit;
                        ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group" v-if="mode=='insert_wo' || mode=='detail_wo' || mode=='update_wo'">
                  <div class="form-input">
                    <label for="inputEmail3" class="col-sm-2 control-label">Mekanik <i style="color: red">*</i></label>
                    <div class="col-sm-4">
                      <select name="id_karyawan_dealer" id="id_karyawan_dealer" class="form-control" required <?= $disabled ?>>
                        <?php
                        $opt_mekanik = '<option value="">- choose -</option>';
                        if (isset($row)) {
                          $opt_mekanik = '<option value="' . $row->id_karyawan_dealer . '">' . $row->id_karyawan_dealer . ' | ' . $row->nama_lengkap . '</option>';
                        }
                        if (isset($row_wo)) {
                          $opt_mekanik = '<option value="' . $row_wo->id_karyawan_dealer . '">' . $row_wo->id_karyawan_dealer . ' | ' . $row_wo->nama_lengkap . '</option>';
                        }
                        echo $opt_mekanik;
                        ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="box-footer" v-if="mode=='insert' || mode=='edit' ||mode=='insert_wo'||mode=='update_wo'">
                  <div class="col-sm-12" align="center">
                    <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  </div>
                  <!-- <div class="col-sm-12" v-if="mode=='insert_wo'" align="center">
                    <button type="button" id="submitBtn" name="save" value="save" class="btn btn-primary btn-flat">Generate Work Order</button>
                  </div>
                  <div class="col-sm-12" v-if="mode=='update_wo'" align="center">
                    <button type="button" id="submitBtn" name="save" value="save" class="btn btn-primary btn-flat">Update Work Order</button>
                  </div> -->
                  <?php
                  if (isset($row_wo)) { ?>
                    <!-- <div class="row">
                      <div class="col-sm-12">
                        <a href="dealer/h3_dealer_sales_order/add?id_customer=<?= $row_wo->id_customer  ?>&id_work_order=<?= $row_wo->id_work_order ?>"><button type="button" class="btn btn-primary btn-flat">Create Sales Order</button></a>
                      </div>
                    </div> -->
                  <?php } ?>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div>
      <?php
      $uri = $this->uri->segment(2);
      if ($uri == 'work_order_dealer') {
        $data['data'] = [
          'riwayatServisCustomerH23', 'partWithAllStock', 'sa_form', 'open_sa_form', 'modal_demand',
          'modalJasa', 'filter_hirarki_jasa',
          'modalPromoServis',
          'modalPromoPart'
        ];
      } else {
        $data['data'] = [
          'riwayatServisCustomerH23', 'partWithAllStock', 'kelurahan', 'pembawa', 'antrian', 'new_antrian', 'sa_form', 'open_sa_form', 'modal_demand',
          'modalJasa', 'filter_hirarki_jasa',
          'modalPromoServis',
          'modalPromoPart',
          'allCustomer'
        ];
      }

      $this->load->view('dealer/h2_api', $data); ?>
      <script src="assets/panel/plugins/datepicker/bootstrap-datepicker.js"></script>
      <script>
        function pilihAllCustomer(customer) {
          $.ajax({
            beforeSend: function() {
              $('#searchCustomer').attr('disabled', true);
              $('#searchCustomer').html('<i class="fa fa-spinner fa-spin">');
            },
            url: '<?= base_url('api/H2/getCustomer') ?>',
            type: "POST",
            data: customer,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              if (response.status == 'sukses') {
                form_.customer = response.data;
                form_.customer_old = response.data;
                if (form_.customer_from != 'booking') {
                  form_.customer_from = customer.customer_from;
                }
                form_.customer.id_booking = customer.id_booking;
                form_.customer_old.id_booking = customer.id_booking;
                form_.customer.nama_pembawa = customer.nama_pembawa;
                form_.customer_old.nama_pembawa = customer.nama_pembawa;
                form_.customer.keluhan = customer.keluhan;
                form_.customer_old.keluhan = customer.keluhan;
                $("#id_type").val(customer.id_type).trigger('change');

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
          form_.customer = customer;
          form_.customer_old = customer;
          form_.customer_from = customer.customer_from;
        }
        Vue.component('date-picker', {
          template: '<input type="text" v-datepicker class="form-control isi_combo" :value="value" @input="update($event.target.value)">',
          directives: {
            datepicker: {
              inserted(el, binding, vNode) {
                $(el).datepicker({
                  autoclose: true,
                  format: 'yyyy-mm-dd',
                  todayHighlight: false,
                }).on('changeDate', function(e) {
                  vNode.context.$emit('input', e.format(0))
                })
              }
            }
          },
          props: ['value'],
          methods: {
            update(v) {
              this.$emit('input', v)
            }
          }
        })
        $(document).ready(function() {

          // $("#id_sa_form").select2({
          //   // minimumInputLength: 2,
          //   ajax: {
          //     url: "<?= site_url('dealer/work_order_dealer/get_select_sa_form') ?>",
          //     type: "post",
          //     dataType: 'json',
          //     delay: 250,
          //     data: function(params) {
          //       return {
          //         searchTerm: params.term, // search term
          //         mode: '<?= $mode ?>'
          //       };
          //     },
          //     processResults: function(response) {
          //       return {
          //         results: response
          //       };
          //     },
          //     cache: true
          //   }
          // });
          $("#id_wo_job_return").select2({
            // minimumInputLength: 2,
            ajax: {
              url: "<?= site_url('dealer/sa_form/get_sel_wo_job_return') ?>",
              type: "post",
              dataType: 'json',
              delay: 250,
              data: function(params) {
                return {
                  searchTerm: params.term, // search term
                  id_customer: $('#id_customer').val(), // search term
                  mode: '<?= $mode ?>'
                };
              },
              processResults: function(response) {
                return {
                  results: response
                };
              },
              cache: true
            }
          });
          // $("#id_antrian").select2({
          //   // minimumInputLength: 2,
          //   ajax: {
          //     url: "<?= site_url('dealer/sa_form/get_select_antrian') ?>",
          //     type: "post",
          //     dataType: 'json',
          //     delay: 250,
          //     data: function(params) {
          //       return {
          //         searchTerm: params.term, // search term
          //         mode: '<?= $mode ?>'
          //       };
          //     },
          //     processResults: function(response) {
          //       return {
          //         results: response
          //       };
          //     },
          //     cache: true
          //   }
          // });
          $("#id_pit").select2({
            // minimumInputLength: 2,
            ajax: {
              url: "<?= site_url('dealer/work_order_dealer/get_select_pit') ?>",
              type: "post",
              dataType: 'json',
              delay: 250,
              data: function(params) {
                return {
                  searchTerm: params.term, // search term
                  mode: '<?= $mode ?>'
                };
              },
              processResults: function(response) {
                return {
                  results: response
                };
              },
              cache: true
            }
          });
          $("#id_karyawan_dealer").select2({
            // minimumInputLength: 2,
            ajax: {
              url: "<?= site_url('dealer/work_order_dealer/get_sel_mekanik_ready') ?>",
              type: "post",
              dataType: 'json',
              delay: 250,
              data: function(params) {
                return {
                  searchTerm: params.term, // search term
                  mode: '<?= $mode ?>',
                  id_pit: $('#id_pit').val()
                };
              },
              processResults: function(response) {
                return {
                  results: response
                };
              },
              cache: true
            }
          });
        }); // End Of Document Ready

        function pilihPromoPart(promo) {
          form_.dtl_part.id_promo = '';
          form_.dtl_part.tipe_diskon = '';
          form_.dtl_part.diskon_value = '';
          let part = form_.dtl_part;
          let all_parts = [];
          for (dt of form_.details) {
            for (prt of dt.parts) {
              all_parts.push(prt);
            }
          }
          all_parts.push(part);

          if (promo.tipe_promo == 'Bertingkat') {
            for (var index = 0; index < promo.promo_items.length; index++) {
              const element = promo.promo_items[index];
              if (part.qty >= element.qty) {
                form_.dtl_part.tipe_diskon = element.tipe_disc;
                form_.dtl_part.diskon_value = element.disc_value;
                form_.dtl_part.id_promo = promo.id_promo;

                return;
              }
            }
          } else if (promo.tipe_promo == 'Standar') {
            for (var index = 0; index < promo.promo_items.length; index++) {
              const element = promo.promo_items[index];
              if (part.id_part == element.id_part) {
                form_.dtl_part.tipe_diskon = element.tipe_disc;
                form_.dtl_part.diskon_value = element.disc_value;
                form_.dtl_part.id_promo = promo.id_promo;

                return;
              }
            }
          } else if (promo.tipe_promo == 'Paket') {
            count = 0;
            for (let index_promo = 0; index_promo < promo.promo_items.length; index_promo++) {
              const item = promo.promo_items[index_promo];
              for (let index_part = 0; index_part < all_parts.length; index_part++) {
                const part = all_parts[index_part];
                if (item.id_part == part.id_part && part.qty >= item.qty) {
                  count += 1;
                }
              }
            }

            if (count == promo.promo_items.length && this.totals('biaya_parts') >= promo.minimal_pembelian) {
              id_part = form_.dtl_part.id_part;
              part_promo_item = _.find(promo.promo_items, ['id_part', id_part]);
              form_.dtl_part.tipe_diskon = part_promo_item.tipe_disc;
              form_.dtl_part.diskon_value = part_promo_item.disc_value;
              form_.dtl_part.id_promo = promo.id_promo;
            } else {
              form_.dtl_part.tipe_diskon = '';
              form_.dtl_part.diskon_value = '';
            }
          } else if (promo.tipe_promo == 'Bundling') {
            count = 0;
            for (let index_promo = 0; index_promo < promo.promo_items.length; index_promo++) {
              const item = promo.promo_items[index_promo];
              for (let index_part = 0; index_part < all_parts.length; index_part++) {
                const part = all_parts[index_part];
                if (item.id_part == part.id_part && part.qty >= item.qty) {
                  count += 1;
                }
              }
            }
            if (count == promo.promo_items.length) {
              form_.dtl_part.id_promo = promo.id_promo;
              form_.dtl_part.tipe_diskon = promo.tipe_diskon_master;
              form_.dtl_part.diskon_value = promo.diskon_value_master;
            } else {
              form_.dtl_part.tipe_diskon = '';
              form_.dtl_part.diskon_value = '';
            }
          }
        }

        function labourCost() {
          let values = {
            id_tipe_kendaraan: form_.sa.id_tipe_kendaraan === undefined ? '' : form_.sa.id_tipe_kendaraan,
          }
          let result = '';
          $.ajax({
            beforeSend: function() {},
            url: '<?= base_url('dealer/sa_form/getLabourCost') ?>',
            type: "POST",
            data: values,
            cache: false,
            dataType: 'JSON',
            async: false,
            success: function(response) {
              if (response.status == 'sukses') {
                result = response.labour_cost
              } else {
                alert(response.pesan);
              }
            },
            error: function() {
              alert("Something went wrong !");
            },
          });
          return result;
        }

        function pilihJasa(js) {
          // console.log(js);
          let labour_cost = 0;
          if (js.id_type == 'C1' || js.id_type == 'C2') {
            labour_cost = labourCost()
          }
          form_.dtl = {
            jasa: js.id_jasa,
            deskripsi: js.deskripsi,
            harga: js.harga,
            waktu: js.waktu,
            parts: [],
            parts_demand: [],
            tipe_motor: form_.customer.tipe_motor,
            kategori: js.kategori,
            id_type: js.id_type,
            desk_type: js.desk_tipe,
            pekerjaan_luar: '',
            need_parts: '',
            id_promo: '',
            nama_promo: '',
            tipe_diskon: '',
            diskon: 0,
            frt_claim: '',
            labour_cost: labour_cost
          }

        }

        function pilihPromoServis(ps) {
          form_.dtl.id_promo = ps.id_promo;
          form_.dtl.nama_promo = ps.nama_promo;
          form_.dtl.tipe_diskon = ps.tipe_diskon;
          form_.dtl.diskon = parseInt(ps.diskon);
        }
        var kelurahan_untuk = '';
        <?php
        $job_return = '';
        if (isset($row_wo)) $job_return = $row_wo->job_return;
        if (isset($row)) $job_return = $row->job_return;
        ?>
        var elmnt = document.getElementById("fieldEditPekerjaan");

        var cek_parts_all = 0;
        var form_ = new Vue({
          el: '#form_',
          data: {
            edit_demand: [],
            ada_milik: 0,
            kosong: '',
            indexPekerjaanUpd: '',
            mode: '<?= $mode ?>',
            edit_cust: '',
            jenis_customer: '',
            pkp: <?= $pkp ?>,
            estimasi_waktu_daftar: moment('<?= $estimasi_waktu_daftar ?>', 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YYYY HH:mm'),
            job_return: '<?= $job_return ?>',
            tipe_coming: <?= isset($row) ? json_encode($tipe_coming) : '[]' ?>,
            details: <?= isset($row) ? json_encode($details) : '[]' ?>,
            booking: {
              id_booking: ''
            },
            pencarian: '',
            pembawa: {
              kelurahan: '',
              kelurahan_identitas: ''
            },
            unit: [],
            coming_cust: 'milik',
            sa: {},
            srbu: 0,
            dtl: {
              waktu: 0,
              kategori: '',
              type_pekerjaan: '',
              need_parts: '',
              id_promo: '',
              jasa: '',
              id_type: '',
              pekerjaan_luar: '',
              id_tipe_servis: '',
              tipe_servis: '',
              parts: [],
              parts_demand: []
            },
            edit_cust: '',
            customer: {
              tipe_ahm: '',
              kelurahan: '',
              kelurahan_identitas: ''
            },
            customer_old: '',
            pilih_pembawa: '',
            dtl_part: {
              id_part: '',
              tipe_diskon: '',
            },
            insPekerjaan: 0,
            alamat_cust_sama: '',
            alamat_pembawa_sama: '',
          },
          methods: {
            is_claim: function(dtl) {
              is_claim = 0;
              if (dtl.id_type == 'C1') is_claim = 1;
              if (dtl.id_type == 'C2') is_claim = 1;
              return is_claim
            },
            setBatal: function(idx) {
              let cek = this.details[idx].pekerjaan_batal;
              let details = this.details[idx];
              if (cek == 0) {
                if (details.need_parts === 'Yes') {
                  for (x of details.parts) {
                    if (typeof x.status_picking_slip != 'undefined') {
                      if (x.status_picking_slip.toLowerCase() == 'closed') {
                        alert('Gagal pembatalan. Status picking slip part pada pekerjaan ini sudah closed !');
                        return false;
                      }
                    }
                  }
                }
                if (confirm('Apakah Anda yakin membatalkan pekerjaan ini ?') === true) {
                  this.details[idx].pekerjaan_batal = 1;
                } else {
                  return false;
                }
              } else {
                if (confirm('Apakah Anda yakin melanjutkan pekerjaan ini ?') === true) {
                  this.details[idx].pekerjaan_batal = 0;
                } else {
                  return false;
                }
              }
            },
            estimasi_biaya_servis: function(dt) {
              let estimasi = 0;
              let waktu = 0;
              if (dt.id_type == 'C1' || dt.id_type == 'C2') {
                estimasi = dt.frt_claim * dt.labour_cost
                waktu = parseFloat(dt.frt_claim) * 60;
                if (isNaN(waktu)) waktu = 0;
                this.dtl.waktu = waktu;
                this.dtl.harga = estimasi;
              } else {
                estimasi = dt.harga - this.cek_diskon(dt);
              }
              return estimasi
              // return 0;
            },
            subTotalPart: function(part, tipe) {
              console.log(part)
              if (part.harga === undefined) {
                harga = parseInt(part.harga_dealer_user);
              } else {
                harga = parseInt(part.harga);
              }
              harga_old = harga;
              harga_real = harga;
              if (this.pkp === 1) {
                // harga = parseInt(harga / 1.1);
                harga = parseInt(harga);
              }
              if (part.tipe_diskon == 'Percentage') {
                diskon = (part.diskon_value / 100) * harga;
                // console.log(diskon)
                harga_real -= diskon;
              }
              kuantitas = Number(part.qty);
              if (part.tipe_diskon == 'FoC') {
                kuantitas -= Number(part.diskon_value);
              }

              potongan_harga = 0;
              if (part.tipe_diskon == 'Value') {
                potongan_harga = Number(part.diskon_value);
              }
              let subtotal = (kuantitas * harga_real) - potongan_harga;
              let total_het = parseInt(part.qty) * parseInt(harga_old);
              let total_diskon = total_het - subtotal;
              console.log(total_het);
              if (tipe == 'subtotal') {
                return subtotal;
              } else if (tipe == 'total_het') {
                return total_het;
              } else if (tipe == 'total_diskon') {
                return total_diskon;
              }
            },
            updatePekerjaan: function(idx) {
              this.insPekerjaan = 1;
              this.indexPekerjaanUpd = idx;
              let details = this.details[idx];
              this.details.splice(idx, 1);
              this.dtl = details;
              if (details.pekerjaan_luar == null) {
                this.dtl.pekerjaan_luar = 0;
              }
              setTypePekerjaan()
              console.log(this.indexPekerjaanUpd)
            },
            refreshModalRecordDemand: function() {
              $('#modalRecordDemand').modal('hide');
              $('#rec_id_part').val('');
              $('#rec_nama_part').val('');
              $('#rec_qty').val('');
              $('#alasan').val('');
            },
            editRecordPartDemand: function(pos, index) {
              fr_.upd_record = 1;
              this.upd_demand = {
                pos: pos,
                index: index
              }
              if (pos == 'detail') {
                let part = this.dtl.parts_demand[index];
                $('#rec_id_part').val(part.id_part);
                $('#rec_nama_part').val(part.nama_part);
                $('#rec_qty').val(part.qty);
                $('#alasan').val(part.alasan);
                $('#modalRecordDemand').modal('show');
              }
            },
            delRecordPartDemand: function(pos, index) {
              if (pos == 'detail') {
                if (confirm("Apakah anda yakin ?") == true) {
                  this.dtl.parts_demand.splice(index, 1);
                } else {
                  return false
                }
              }
            },
            editCust: function() {
              if (this.edit_cust == 1) {
                this.edit_cust = '';
                this.customer = form_.customer_old;
                $('#tgl_pembelian').attr('disabled', true);
              } else {
                $('#tgl_pembelian').attr('disabled', false);
                this.edit_cust = 1;
              }
            },
            setInsPekerjaan: function() {
              // console.log(this.sa.id_customer)
              if (this.customer.id_customer === undefined) {
                alert('Silahkan pilih customer terlebih dahulu');
                return false;
              }
              if (this.insPekerjaan == 1) {
                this.insPekerjaan = 0;
              } else {
                this.insPekerjaan = 1;
              }
            },
            setBooking: function(detail) {
              this.booking = {
                id_booking: detail.id_booking,
                id_customer: detail.id_customer,
                nama_customer: detail.nama_customer,
                nama_pembawa: detail.nama_pembawa,
                jenis_pekerjaan: detail.jenis_pekerjaan,
                tgl_servis: detail.tgl_servis,
                jam_servis: detail.jam_servis,
                no_hp: detail.no_hp,
                no_polisi: detail.no_polisi
              };
              $('.modalKelurahan').modal('hide');
            },
            clearDetails: function() {
              this.dtl = {
                waktu: 0,
                kategori: '',
                type_pekerjaan: '',
                need_parts: '',
                id_promo: '',
                jasa: '',
                id_type: '',
                pekerjaan_luar: '',
                id_tipe_servis: '',
                tipe_servis: '',
                parts: [],
                parts_demand: []
              }
            },
            clearDtlPart: function() {
              this.dtl_part = {
                id_part: '',
                tipe_diskon: '',
              }
            },
            showModalPart: function() {
              cek_parts_all++;
              if (cek_parts_all == 1) {
                $('#tbl_reguler_part_sales').DataTable({
                  processing: true,
                  serverSide: true,
                  "language": {
                    "infoFiltered": ""
                  },
                  order: [],
                  ajax: {
                    url: "<?= base_url('api/h2/salesPartsDealer') ?>",
                    dataSrc: "data",
                    data: function(d) {
                      d.id_part = $('#sr_id_part').val();
                      d.nama_part = $('#sr_nama_part').val();
                      d.id_tipe_kendaraan = cari_part.id_tipe_kendaraan;
                      d.id_tipe_kendaraan_konsumen = form_.sa.id_tipe_kendaraan;
                      return d;
                    },
                    type: "POST"
                  },
                  "columnDefs": [{
                      "targets": [0, 1, 2, 3, 4, 5, 6, 7],
                      "orderable": false
                    },
                    {
                      "targets": [7],
                      "className": 'text-center'
                    },
                    {
                      "targets": [2],
                      "className": 'text-right'
                    },
                    // { "targets":[4], "searchable": false } 
                  ]
                });

                $('#tbl_dealer_lain_part_sales').DataTable({
                  processing: true,
                  serverSide: true,
                  "language": {
                    "infoFiltered": ""
                  },
                  order: [],
                  ajax: {
                    url: "<?= base_url('api/h2/salesPartsDealerLain') ?>",
                    dataSrc: "data",
                    data: function(d) {
                      d.id_part = $('#sr_id_part').val();
                      d.nama_part = $('#sr_nama_part').val();
                      d.qty_part = $('#sr_qty_part').val();
                      d.id_tipe_kendaraan = cari_part.id_tipe_kendaraan;
                      return d;
                    },
                    type: "POST"
                  },
                  "columnDefs": [{
                      "targets": [0, 1, 2, 3, 4, 5, 6],
                      "orderable": false
                    },
                    {
                      "targets": [2],
                      "className": 'text-center'
                    },
                    // { "targets":[4], "searchable": false } 
                  ]
                });

                $('#tbl_hlo_part_sales').DataTable({
                  processing: true,
                  serverSide: true,
                  "language": {
                    "infoFiltered": ""
                  },
                  order: [],
                  ajax: {
                    url: "<?= base_url('api/h2/salesHLOPartsDealer') ?>",
                    dataSrc: "data",
                    data: function(d) {
                      d.id_part = $('#sr_id_part').val();
                      d.nama_part = $('#sr_nama_part').val();
                      d.id_tipe_kendaraan = cari_part.id_tipe_kendaraan;
                      return d;
                    },
                    type: "POST"
                  },
                  "columnDefs": [{
                      "targets": [0, 1, 2, 3, 4],
                      "orderable": false
                    },
                    {
                      "targets": [4],
                      "className": 'text-center'
                    },
                    // { "targets":[4], "searchable": false } 
                  ]
                });
              }
              // if (this.dtl.id_type === 'ASS1' || this.dtl.id_type === 'ASS2' || this.dtl.id_type === 'ASS3' || this.dtl.id_type === 'ASS4') {
              //   cp_.id_tipe_kendaraan = this.customer.id_tipe_kendaraan;
              //   cari_part.id_tipe_kendaraan = this.customer.id_tipe_kendaraan;
              //   $('#tipe_ahm_cari_part').val(this.customer.tipe_ahm);
              //   $('#tipe_ahm_cari_part').removeAttr("data-toggle");
              // } else {
              $('#tipe_ahm_cari_part').attr("data-toggle", "modal");
              // }
              $('#tbl_part_all_stock').DataTable().ajax.reload();
              $('#modalPartWithAllStock').modal('show');
            },
            showModalJasa: function() {
              $('#tbl_jasa').DataTable().ajax.reload();
              $('.modalJasa').modal('show');
            },
            delPart: function(index) {
              if (confirm("Apakah anda yakin ?") == true) {
                this.dtl.parts.splice(index, 1);
              } else {
                return false
              }
            },
            addPart: function(index = null) {
              // console.log(this.dtl_part)
              if (this.dtl_part.id_part === undefined || this.dtl_part.id_part === '') {
                alert('Part belum dipilih !');
                return false
              }
              if (this.dtl_part.qty < 0) {
                alert('Qty tidak boleh minus !');
                return false
              } else if (this.dtl_part.qty == 0) {
                alert('Qty tidak boleh nol !');
                return false
              }
              if (this.dtl_part.qty === undefined || parseInt(this.dtl_part.qty < 1)) {
                alert('Tentukan Qty part terlebih dahulu !');
                return false
              }
              if (parseInt(this.dtl_part.stock) < parseInt(this.dtl_part.qty)) {
                alert('Qty tidak boleh melebihi stok tersedia !');
                this.dtl_part.qty = this.dtl_part.stock;
                return false;
              }
              if (index === null) {
                this.dtl.parts.push(this.dtl_part);
              } else {
                this.details[index].parts.push(this.dtl_part);
              }
              this.clearDtlPart();
            },
            addDetails: function() {
              if (this.mode === 'update_wo') {
                this.dtl.update = 1;
              }
              if (this.dtl.kategori === '') {
                alert('Tentukan kategori pekerjaan !');
                return false;
              }
              if (this.dtl.id_tipe_servis === '' || this.dtl.id_tipe_servis === undefined) {
                alert('Tipe servis belum dipilih !');
                return false;
              }
              if (this.dtl.id_type === '' || this.dtl.id_type === undefined) {
                alert('Tentukan tipe pekerjaan !');
                return false;
              }
              if (this.dtl.jasa === '' || this.dtl.jasa === undefined) {
                alert('Tentukan pekerjaan !');
                return false;
              }
              // if (this.indexPekerjaanUpd === '') {
              if (this.dtl.pekerjaan_luar === '' || this.dtl.pekerjaan_luar === undefined) {
                alert('Tentukan apakah ini pekerjaan luar !');
                return false;
              }
              //   console.log(this.indexPekerjaanUpd)
              // }
              if (this.dtl.need_parts === '' || this.dtl.need_parts === undefined) {
                alert('Tentukan kebutuhan parts !');
                return false;
              }
              if (this.dtl.need_parts === 'yes') {
                if (this.dtl.parts.length === 0) {
                  alert('Kebutuhan part belum ditentukan !');
                  return false;
                }
              }

              this.dtl.tipe_servis = $("#tipe_servis option:selected").text();
              this.details.push(this.dtl);
              // console.log(this.details);
              this.dtl = {
                parts: [],
                parts_demand: []
              };
              this.setInsPekerjaan()
              this.indexPekerjaanUpd = '';
              console.log(this.details);
            },
            delDetails: function(index) {
              if (confirm("Apakah Anda yakin ?") === true) {
                this.details.splice(index, 1);
              } else {
                return false;
              }
            },
            delDetailsParts: function(index_part, part, index) {
              if (confirm("Apakah Anda yakin ?") === true) {
                console.log(this.details[index].parts);
                this.details[index].parts.splice(index_part, 1);
              } else {
                return false;
              }
            },
            editDetailsParts: function(index_part, part, index) {
              this.dtl_part = part;
              this.details[index].parts.splice(index_part, 1);
            },
            clearPembawa: function() {
              this.pembawa = {
                kelurahan: '',
                kelurahan_identitas: ''
              };
            },
            totQtyPart: function(parts) {
              let tot = 0;
              // console.log(parts);
              if (parts.length > 0) {
                for (prt of parts) {
                  tot += parseInt(prt.qty)
                }
              }
              return tot;
            },
            cek_diskon: function(dt) {
              let diskon = 0;
              if (dt.id_promo != '') {
                if (dt.tipe_diskon == 'rupiah') {
                  diskon = parseInt(dt.diskon);
                } else {
                  harga = parseInt(dt.harga);
                  // if (this.pkp === 1) {
                  //   harga = parseInt(dt.harga / 1.1);
                  // } else {
                  //   harga = parseInt(dt.harga);
                  // }
                  diskon = harga * (parseInt(dt.diskon) / 100);
                }
              }
              return parseInt(diskon)
            },
            totals: function(tipe) {
              let grand = 0;
              let tot = 0;
              let tot_tanpa_ppn = 0;
              let ppn = 0;
              let tot_biaya_parts = 0;
              let tot_biaya_servis = 0;
              for (dt of this.details) {
                let diskon = 0;
                // console.log(dt);
                if (tipe == 'biaya_servis' && (dt.pekerjaan_batal == 0 || dt.pekerjaan_batal == undefined)) {
                  if (dt.id_promo != '') {
                    diskon = this.cek_diskon(dt);
                  }
                  tot += parseInt(dt.harga) - diskon;
                } else if (tipe == 'qty_parts') {
                  if (dt.parts.length > 0) {
                    for (prt of dt.parts) {
                      tot += parseInt(prt.qty);
                    }
                  }
                } else if (tipe == 'biaya_parts') {
                  if (dt.parts.length > 0) {
                    for (prt of dt.parts) {
                      tot += this.subTotalPart(prt, 'subtotal');
                    }
                  }
                  // console.log(tot);
                } else if (tipe == 'grand_total' || tipe == 'tot_tanpa_ppn' || tipe == 'ppn') {
                  let diskon = 0;
                  if (dt.pekerjaan_batal == 0 || dt.pekerjaan_batal == undefined) {
                    if (dt.id_promo != '') {
                      diskon = this.cek_diskon(dt);
                    }
                    tot_biaya_servis += parseInt(dt.harga) - diskon;
                  }
                  if (dt.parts.length > 0) {
                    for (prt of dt.parts) {
                      tot_biaya_parts += this.subTotalPart(prt, 'subtotal');
                    }
                  }
                }
              }
              if (tipe == 'tot_tanpa_ppn') {
                tot = parseInt(tot_biaya_parts) + parseInt(tot_biaya_servis);
              } else if (tipe == 'ppn') {
                tot_tanpa_ppn = parseInt(tot_biaya_parts) + parseInt(tot_biaya_servis);
                if (this.pkp === 1) {
                  // ppn = (10 / 100) * parseInt(tot_biaya_parts);
                }
                tot = ppn;
              } else if (tipe == 'grand_total') {
                tot_tanpa_ppn = parseInt(tot_biaya_parts) + parseInt(tot_biaya_servis);
                if (this.pkp === 1) {
                  // ppn = (10 / 100) * parseInt(tot_biaya_parts);
                }
                tot = tot_tanpa_ppn + ppn;
              }
              return tot;
            }
          },
          watch: {
            tipe_coming: function() {
              this.ada_milik = 0;
              for (x of this.tipe_coming) {
                if (x == 'milik') {
                  this.ada_milik = 1;
                }
              }
              if (this.ada_milik > 0) {
                let cust = this.customer;
                this.pembawa = {
                  nama_pembawa: cust.nama_customer,
                  jenis_kelamin: cust.jenis_kelamin,
                  alamat_saat_ini: cust.alamat,
                  kelurahan: cust.kelurahan,
                  kecamatan: cust.kecamatan,
                  kabupaten: cust.kabupaten,
                  provinsi: cust.provinsi,
                  nama_pemakai: cust.nama_customer,
                  no_hp: cust.no_hp,
                  email: cust.email,
                  agama: cust.agama,
                  id_agama: cust.id_agama,
                  jenis_identitas: cust.jenis_identitas,
                  no_identitas: cust.no_identitas,
                  alamat_identitas: cust.alamat_identitas,
                  kelurahan_identitas: cust.kelurahan_identitas,
                  kecamatan_identitas: cust.kecamatan_identitas,
                  kabupaten_identitas: cust.kabupaten_identitas,
                  provinsi_identitas: cust.provinsi_identitas,
                }
                console.log(this.pembawa)
                this.coming_cust = 'milik';
                this.pilih_pembawa = '';
              } else {
                this.clearPembawa();
                this.coming_cust = 'milik';
                this.pilih_pembawa = 1;
              }
            },
            alamat_cust_sama: function() {
              if (this.alamat_cust_sama === true) {
                this.customer.alamat_identitas = this.customer.alamat;
                this.customer.id_kelurahan_identitas = this.customer.id_kelurahan;
                this.customer.kelurahan_identitas = this.customer.kelurahan;
                this.customer.kecamatan_identitas = this.customer.kecamatan;
                this.customer.kabupaten_identitas = this.customer.kabupaten;
                this.customer.provinsi_identitas = this.customer.provinsi;
              } else {
                this.customer.alamat_identitas = '';
                this.customer.id_kelurahan_identitas = '';
                this.customer.kelurahan_identitas = '';
                this.customer.kecamatan_identitas = '';
                this.customer.kabupaten_identitas = '';
                this.customer.provinsi_identitas = '';
              }
            },
            alamat_pembawa_sama: function() {
              if (this.alamat_pembawa_sama === true) {
                this.pembawa.alamat_identitas = this.pembawa.alamat_saat_ini;
                this.pembawa.id_kelurahan_identitas = this.pembawa.id_kelurahan;
                this.pembawa.kelurahan_identitas = this.pembawa.kelurahan;
                this.pembawa.kecamatan_identitas = this.pembawa.kecamatan;
                this.pembawa.kabupaten_identitas = this.pembawa.kabupaten;
                this.pembawa.provinsi_identitas = this.pembawa.provinsi;
              } else {
                this.pembawa.alamat_identitas = '';
                this.pembawa.id_kelurahan_identitas = '';
                this.pembawa.kelurahan_identitas = '';
                this.pembawa.kecamatan_identitas = '';
                this.pembawa.kabupaten_identitas = '';
                this.pembawa.provinsi_identitas = '';
              }
            }
          },
          computed: {
            tot_frt: function() {
              let tot_ = 0;
              for (dt of this.details) {
                tot_ += parseInt(dt.waktu);
              }
              return tot_
            },
            estimasi_waktu_selesai: function() {
              let startTime = this.estimasi_waktu_daftar;
              let durationInMinutes = this.tot_frt;
              let endTime = moment(startTime, 'DD/MM/YYYY HH:mm').add(durationInMinutes, 'minutes').format('DD/MM/YYYY HH:mm');
              return endTime;
            },
            min_cust_id: function() {
              let set = 0;
              if (this.customer.jenis_identitas == 'ktp') set = 16;
              if (this.customer.jenis_identitas == 'sim') set = 12;
              if (this.customer.jenis_identitas == 'kitap') set = 16;
              return set;
            },
            max_pembawa_id: function() {
              let set = 30;
              if (this.customer.jenis_identitas == 'ktp') set = 16;
              if (this.customer.jenis_identitas == 'sim') set = 12;
              if (this.customer.jenis_identitas == 'kitap') set = 16;
              if (this.customer.jenis_identitas == 'npwp') set = 30;
              return set;
            },
            min_pembawa_id: function() {
              let set = 0;
              if (this.customer.jenis_identitas == 'ktp') set = 16;
              if (this.customer.jenis_identitas == 'sim') set = 12;
              if (this.customer.jenis_identitas == 'kitap') set = 16;
              return set;
            },
            max_cust_id: function() {
              let set = 30;
              if (this.customer.jenis_identitas == 'ktp') set = 16;
              if (this.customer.jenis_identitas == 'sim') set = 12;
              if (this.customer.jenis_identitas == 'kitap') set = 16;
              if (this.customer.jenis_identitas == 'npwp') set = 30;
              return set;
            },
            totalPartTanpaPPN: function() {
              tot = 0;
              for (dt of this.details.parts) {
                tot += parseInt()
              }
            }
          }
        });

        function partRecordDemand(prt) {
          // console.log(prt)
          $('#rec_id_part').val(prt.id_part);
          $('#rec_nama_part').val(prt.nama_part);
          fr_.upd_record = '';
          $('#modalRecordDemand').modal('show')
        }

        function simpanDemand() {
          let part_demand = {
            id_part: $('#rec_id_part').val(),
            nama_part: $('#rec_nama_part').val(),
            qty: $('#rec_qty').val(),
            alasan: $('#alasan').val(),
          }
          $('#modalPartWithAllStock').modal('hide');
          form_.dtl.parts_demand.push(part_demand);
          form_.refreshModalRecordDemand();
        }

        function updateDemand() {
          let upd = form_.upd_demand;
          if (upd.pos == 'detail') {
            form_.dtl.parts_demand[upd.index].qty = $('#rec_qty').val()
            form_.dtl.parts_demand[upd.index].alasan = $('#alasan').val()
          }
          form_.refreshModalRecordDemand();
        }

        function pilihAntrian(params) {
          $('#id_antrian').val(params.id_antrian);
          getSaForm();
        }

        function getSaForm(mode = null) {
          let values = {
            id_antrian: $("#id_antrian").val()
          }
          $.ajax({
            beforeSend: function() {
              // $('#btnRiwayatServis').attr('disabled',true);
            },
            url: '<?= base_url('dealer/sa_form/getSaForm') ?>',
            type: "POST",
            data: values,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              console.log(response);
              if (response.status == 'sukses') {
                form_.customer = response.customer;
                form_.customer_old = response.customer;
                form_.sa = response.sa;
                form_.srbu = response.srbu;
                if (form_.mode == 'edit' || form_.mode == 'detail' || form_.mode == 'detail_wo') {
                  if (form_.pembawa != null) {
                    form_.pembawa = response.pembawa;
                  }
                }
              }
            },
            error: function() {
              alert("Something went wrong !");
            },
          });
        }

        function pilihSaForm(params) {
          $('#id_sa_form').val(params.id_sa_form)
          getDataWO()
        }

        function getDataWO(mode = null) {

          let values = {
            id_sa_form: $("#id_sa_form").val(),
            id_dealer: '<?= isset($row_wo) ? $row_wo->id_dealer : '' ?>'
          }
          $.ajax({
            beforeSend: function() {},
            url: '<?= base_url('dealer/work_order_dealer/getDataWO') ?>',
            type: "POST",
            data: values,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              if (response.status == 'sukses') {
                form_.customer = response.customer;
                form_.customer_old = response.customer;
                form_.sa = response.sa;
                form_.job_return = response.sa.job_return
                form_.srbu = response.srbu;
                let milik = 0;
                for (tc of response.tipe_coming) {
                  form_.tipe_coming.push(tc);
                  if (tc == 'milik') {
                    milik = parseInt(milik) + 1;
                  }
                }
                form_.details = [];
                for (dtl of response.details) {
                  form_.details.push(dtl);
                }
                if (milik == 0) {
                  pilihPembawa(response.pembawa);
                }
                // $('#id_pit').val(form_.sa.id_pit_sa).trigger('change');
              }
            },
            error: function() {
              alert("failure");
            },
            statusCode: {
              500: function() {
                alert('fail');
              }
            }
          });
        }

        function pilihPembawa(pembawa) {
          form_.coming_cust = 'milik';
          $.ajax({
            beforeSend: function() {
              $('#btnCariPembawa').attr('disabled', true);
              $('#btnCariPembawa').html('<i class="fa fa-spinner fa-spin">');
            },
            url: '<?= base_url('api/H2/getPembawa') ?>',
            type: "POST",
            data: pembawa,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              if (response.status == 'sukses') {
                form_.pembawa = response.data;
                // console.log(form_.pembawa);
              } else {
                alert(response.pesan);
              }
              $('#btnCariPembawa').attr('disabled', false);
              $('#btnCariPembawa').html('<i class="fa fa-search">');
            },
            error: function() {
              alert("Something Went Wrong !");
              $('#btnCariPembawa').attr('disabled', false);
              $('#btnCariPembawa').html('<i class="fa fa-search">');
            }
          });
        }

        function pembawaBaru() {
          form_.coming_cust = '';
          form_.clearPembawa();
          $('#modalPembawa').modal('hide');
        }

        function pilihItem(item) {
          form_.details[0].tipe_ahm = item.id_tipe_kendaraan + ' | ' + item.tipe_ahm;
          form_.details[0].warna = item.id_warna + ' | ' + item.warna;
        }

        function pilihKelurahan(data) {
          // console.log(kelurahan_untuk);
          if (kelurahan_untuk == 'customer') {
            form_.customer.kelurahan = data.kelurahan;
            form_.customer.id_kelurahan = data.id_kelurahan;
            form_.customer.kecamatan = data.kecamatan;
            form_.customer.kabupaten = data.kabupaten;
            form_.customer.provinsi = data.provinsi;
          } else if (kelurahan_untuk == 'identitas') {
            form_.customer.kelurahan_identitas = data.kelurahan;
            form_.customer.id_kelurahan_identitas = data.id_kelurahan;
            form_.customer.kecamatan_identitas = data.kecamatan;
            form_.customer.kabupaten_identitas = data.kabupaten;
            form_.customer.provinsi_identitas = data.provinsi;
          } else if (kelurahan_untuk == 'pembawa') {
            form_.pembawa.kelurahan = data.kelurahan;
            form_.pembawa.id_kelurahan = data.id_kelurahan;
            form_.pembawa.kecamatan = data.kecamatan;
            form_.pembawa.kabupaten = data.kabupaten;
            form_.pembawa.provinsi = data.provinsi;
          } else if (kelurahan_untuk == 'identitas_pembawa') {
            form_.pembawa.kelurahan_identitas = data.kelurahan;
            form_.pembawa.id_kelurahan_identitas = data.id_kelurahan;
            form_.pembawa.kecamatan_identitas = data.kecamatan;
            form_.pembawa.kabupaten_identitas = data.kabupaten;
            form_.pembawa.provinsi_identitas = data.provinsi;
          }
        }

        function pilihPart(part) {
          // console.log(part)
          for (prt of form_.dtl.parts) {
            if (prt.id_part == part.id_part) {
              alert('Part sudah dipilih !');
              return false;
            }
          }
          for (dtl of form_.details) {
            for (prt of dtl.parts) {
              if (prt.id_part == part.id_part) {
                alert('Part sudah dipilih pada ID Pekerjaan : ' + dtl.jasa);
                return false;
              }
            }
          }
          // console.log(part);
          let order_to = part.order_to === undefined ? '' : part.order_to;
          let order_to_name = part.order_to_name === undefined ? '' : part.order_to_name;
          if (form_.dtl.id_type === 'C1' || form_.dtl.id_type === 'C2') {
            harga = Math.round(part.harga_dealer_user / 1.1);
          } else {
            harga = part.harga_dealer_user;
          }
          form_.dtl_part = {
            id_part: part.id_part,
            nama_part: part.nama_part,
            stock: part.stock,
            harga_dealer_user: harga,
            id_gudang: part.id_gudang,
            id_rak: part.id_rak,
            jenis_order: part.jenis_order,
            order_to: order_to,
            order_to_name: order_to_name,
            tipe_diskon: form_.dtl_part.tipe_diskon,
            id_nama: part.id_part + ' | ' + part.nama_part,
            part_utama: 0
          };
          $('#modalPartWithAllStock').modal('hide');
        }

        $('#submitBtn').click(function() {
          $('#form_').validate({
            rules: {
              'checkbox': {
                required: true
              }
            },
            highlight: function(input) {
              $(input).parents('.form-input').addClass('has-error');
            },
            unhighlight: function(input) {
              $(input).parents('.form-input').removeClass('has-error');
            }
          })
          var values = {
            details: form_.details,
            tipe_coming: form_.tipe_coming,
            pembawa: form_.pembawa,
            customer: form_.customer,
            sa: form_.sa,
            edit_cust: form_.edit_cust,
            grand_total: form_.totals('grand_total'),
            total_jasa: form_.totals('biaya_servis'),
            total_part: form_.totals('biaya_parts'),
            total_tanpa_ppn: form_.totals('tot_tanpa_ppn'),
            total_ppn: form_.totals('ppn'),
          };
          var form = $('#form_').serializeArray();
          for (field of form) {
            values[field.name] = field.value;
          }
          if ($('#form_').valid()) // check if form is valid
          {
            if (form_.tipe_coming.length == 0) {
              alert('Tipe coming Customer belum dipilih !');
              return false
            }
            if (form_.details.length == 0) {
              // console.log(form_.details);
              alert('Pekerjaan belum dipilih !');
              return false
            }
            <?php if ($mode == 'insert_wo') { ?>
              for (dt of form_.details) {
                console.log(dt);
                if (dt.masukkan_wo === false || dt.masukkan_wo === '') {
                  if (confirm("Masih Ada pekerjaan yang belum dipilih. Apakah ingin tetap melanjutkan ?") == false) {
                    return false;
                  }
                }
              }
            <?php } ?>
            if (confirm("Apakah anda yakin ?") == true) {
              $.ajax({
                beforeSend: function() {
                  $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                  $('#submitBtn').attr('disabled', true);
                },
                url: '<?= base_url('dealer/' . $isi . '/' . $form) ?>',
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  if (response.status == 'sukses') {
                    window.location = response.link;
                  } else {
                    alert(response.pesan);
                    $('#submitBtn').attr('disabled', false);
                  }
                  $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                },
                error: function() {
                  alert("failure");
                  $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                  $('#submitBtn').attr('disabled', false);

                },
                statusCode: {
                  500: function() {
                    alert('fail');
                    $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                    $('#submitBtn').attr('disabled', false);

                  }
                }
              });
            } else {
              return false;
            }
          } else {
            alert('Silahkan isi field required !')
          }
        })
      </script>
    <?php
    } elseif ($set == "index") {
    ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <?php if (can_access($isi, 'can_insert')) : ?>
              <a href="dealer/sa_form/add">
                <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
              </a>
            <?php endif; ?>

            <a href="dealer/sa_form/history" class="btn bg-blue btn-flat margin"><i class="fa fa-list"></i> History</a>
          </h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <?php
          if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {
          ?>
            <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
              <strong><?php echo $_SESSION['pesan'] ?></strong>
              <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
              </button>
            </div>
          <?php
          }
          $_SESSION['pesan'] = '';

          ?>
          <table id="datatable_server" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID SA Form</th>
                <th>No. Antrian</th>
                <th>Tgl. Servis</th>
                <th>Jenis Customer</th>
                <th>No. Polisi</th>
                <th>Nama Customer</th>
                <th>No. Mesin</th>
                <th>No. Rangka</th>
                <th>Tipe Motor</th>
                <th>Warna</th>
                <th>Tahun Motor</th>
                <th>Status</th>
                <th width="10%">Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <script>
            function cancelPrompt(id_sa_form) {

              var alasan_cancel = prompt("Alasan melakukan cancel untuk ID SA Form : " + id_sa_form);

              if (alasan_cancel != null || alasan_cancel == "") {

                window.location = '<?= base_url("dealer/sa_form/cancel_sa?id=") ?>' + id_sa_form + '&alasan_cancel=' + alasan_cancel;

                return false;

              }

              return false

            }
            $(document).ready(function() {
              var dataTable = $('#datatable_server').DataTable({
                "processing": true,
                "serverSide": true,
                "scrollX": true,
                "language": {
                  "infoFiltered": "",
                  "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
                },
                "order": [],
                "lengthMenu": [
                  [10, 25, 50, 75, 100],
                  [10, 25, 50, 75, 100]
                ],
                "ajax": {
                  url: "<?php echo site_url('dealer/' . $isi . '/fetch'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                    d.status_form = 'open';
                    // d.tgl_servis = '<?= gmdate("Y-m-d", time() + 60 * 60 * 7); ?>';
                    return d;
                  },
                },
                "columnDefs": [
                  // { "targets":[2],"orderable":false},
                  {
                    "targets": [5],
                    "className": 'text-center'
                  },
                  // // { "targets":[0],"checkboxes":{'selectRow':true}}
                  // { "targets":[4],"className":'text-right'}, 
                  // // { "targets":[2,4,5], "searchable": false } 
                ],
              });
            });
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php } elseif ($set == "history") { ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/sa_form">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
            </a>
          </h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="datatable_server" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID SA Form</th>
                <th>No. Antrian</th>
                <th>Tgl. Servis</th>
                <th>Jenis Customer</th>
                <th>No. Polisi</th>
                <th>Nama Customer</th>
                <th>No. Mesin</th>
                <th>No. Rangka</th>
                <th>Tipe Motor</th>
                <th>Warna</th>
                <th>Tahun Motor</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <script>
            $(document).ready(function() {
              var dataTable = $('#datatable_server').DataTable({
                "processing": true,
                "serverSide": true,
                "scrollX": true,
                "language": {
                  "infoFiltered": "",
                  "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
                },
                "order": [],
                "lengthMenu": [
                  [10, 25, 50, 75, 100],
                  [10, 25, 50, 75, 100]
                ],
                "ajax": {
                  url: "<?php echo site_url('dealer/' . $isi . '/fetch'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                    // d.status_form = 'open,closed,cancel';
                    d.status_form = 'closed,cancel';
                    // d.tgl_servis_lebih_kecil_sama = '<?= gmdate("Y-m-d", time() + 60 * 60 * 7) ?>';
                    return d;
                  },
                },
                "columnDefs": [
                  // { "targets":[2],"orderable":false},
                  // {
                  //   "targets": [5],
                  //   "className": 'text-center'
                  // },
                  // // { "targets":[0],"checkboxes":{'selectRow':true}}
                  // { "targets":[4],"className":'text-right'}, 
                  // // { "targets":[2,4,5], "searchable": false } 
                ],
              });
            });
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->

    <?php } elseif ($set == "history_tes") { ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/sa_form">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
            </a>
          </h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="datatable_server" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID SA Form</th>
                <th>No. Antrian</th>
                <th>Tgl. Servis</th>
                <th>Jenis Customer</th>
                <th>No. Polisi</th>
                <th>Nama Customer</th>
                <th>No. Mesin</th>
                <th>No. Rangka</th>
                <th>Tipe Motor</th>
                <th>Warna</th>
                <th>Tahun Motor</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <script>
            $(document).ready(function() {
              var dataTable = $('#datatable_server').DataTable({
                "processing": true,
                "serverSide": true,
                "scrollX": true,
                "language": {
                  "infoFiltered": "",
                  "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
                },
                "order": [],
                "lengthMenu": [
                  [10, 25, 50, 75, 100],
                  [10, 25, 50, 75, 100]
                ],
                "ajax": {
                  url: "<?php echo site_url('dealer/' . $isi . '/fetch_history'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                    // d.status_form = 'open,closed,cancel';
                    d.status_form = 'closed,cancel';
                    // d.tgl_servis_lebih_kecil_sama = '<?= gmdate("Y-m-d", time() + 60 * 60 * 7) ?>';
                    return d;
                  },
                },
                "columnDefs": [
                  // { "targets":[2],"orderable":false},
                  // {
                  //   "targets": [5],
                  //   "className": 'text-center'
                  // },
                  // // { "targets":[0],"checkboxes":{'selectRow':true}}
                  // { "targets":[4],"className":'text-right'}, 
                  // // { "targets":[2,4,5], "searchable": false } 
                ],
              });
            });
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->

    <?php } elseif ($set == 'send_notif') { ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <script src="assets/jquery/jquery.min.js"></script>
      <script src='assets/select2/js/select2.min.js'></script>

      <script>
        Vue.use(VueNumeric.default);
        $(document).ready(function() {
          getETA()
        })
        Vue.filter('toCurrency', function(value) {
          // // console.log("type value ke currency filter" ,  value, typeof value, typeof value !== "number");
          // if (typeof value !== "number") {
          //     return value;
          // }
          return "Rp. " + accounting.formatMoney(value, "", 0, ".", ",");
          // return value;
        });
      </script>

      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/<?= $this->uri->segment(2); ?>">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
            </a>
          </h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <?php
          if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {
          ?>
            <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
              <strong><?php echo $_SESSION['pesan'] ?></strong>
              <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
              </button>
            </div>
          <?php
          }
          $_SESSION['pesan'] = '';

          ?>
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" id="form_" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID SA Form</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?= $row->id_sa_form ?>" class="form-control" name="id_sa_form" readonly id="id_sa_form">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Customer</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?= $row->id_customer ?>" class="form-control" name="id_customer" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?= $row->nama_customer ?>" class="form-control" name="nama_customer" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No. Polisi</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?= $row->no_polisi ?>" class="form-control" name="no_polisi" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi Tipe Unit </label>
                  <div class="col-sm-4">
                    <input type="text" value="<?= $row->tipe_ahm ?>" class="form-control" name="tipe_ahm" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Job Return <i style="color: red">*</i></label>
                  <div class="col-sm-4">
                    <select class="form-control" v-model="job_return" disabled>
                      <option value="">- choose -</option>
                      <option value="ya">Ya</option>
                      <option value="tidak" selected>Tidak</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Flag Numbering <i style="color: red">*</i></label>
                  <div class="col-sm-4">
                    <select class="form-control" name="flag_numbering" id="flag_numbering" required>
                      <option value="">- choose -</option>
                      <option value="1">Ya</option>
                      <option value="0">Tidak</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Vehicle Offroad <i style="color: red">*</i></label>
                  <div class="col-sm-4">
                    <select class="form-control" name="vehicle_offroad" id="vehicle_offroad" required>
                      <option value="">- choose -</option>
                      <option value="1">Ya</option>
                      <option value="0">Tidak</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ETA</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="eta" id="eta_hari" readonly>
                  </div>
                  <label for="inputEmail3" class="col-sm-1" align="left" style="padding-top:7px">Hari</label>
                  <label for="inputEmail3" class="col-sm-2 control-label">Pada Tanggal</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="eta" id="eta" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan Tambahan</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="keterangan_tambahan" id="keterangan_tambahan">
                  </div>
                </div>
                <div class="col-md-12">
                  <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Data Parts</button><br><br>
                </div>
                <div class="col-md-12">
                  <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                      <li v-for="(pr, index) of parts_order" v-bind:class="{ 'active': index==0 }">
                        <a :href="'#tab_'+index" data-toggle="tab">{{pr.order_to_name}}</a>
                      </li>
                    </ul>
                    <div class="tab-content">
                      <div v-for="(pr, index) of parts_order" v-bind:class="{ 'active': index==0,'tab-pane':true }" v-bind:id="'tab_'+index">
                        <table class="table table-bordered table-striped table-hover table-condensed">
                          <thead>
                            <th>Nomor Parts</th>
                            <th>Part Deskripsi</th>
                            <th>HET</th>
                            <th>Kuantitas</th>
                          </thead>
                          <tbody>
                            <tr v-for="(prt, index) of pr.parts">
                              <td>{{prt.id_part}}</td>
                              <td>{{prt.nama_part}}</td>
                              <td align="right">{{prt.harga | toCurrency}}</td>
                              <td>{{prt.qty}}</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="box-footer" v-if="mode!='detail'">
                  <div class="col-sm-12" align="center">
                    <button type="button" id="submitBtn" name="save" value="save" class="btn btn-primary btn-flat">Save & Send Notify</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div>

      <script>
        var eta = 0;
        var form_ = new Vue({
          el: '#form_',
          data: {
            kosong: '',
            mode: '<?= $mode ?>',
            parts_order: <?= isset($parts_order) ? json_encode($parts_order) : '[]' ?>,
            job_return: '<?= isset($row->job_return) ? $row->job_return : '' ?>',
            eta_parts: [],
          },
          methods: {

          },
          watch: {}
        });

        function getETA() {

          let data = {
            id_part: JSON.parse('<?php echo JSON_encode($id_parts); ?>')
          }
          $.ajax({
            url: '<?= base_url('api/estimated_time_arrived') ?>',
            type: "POST",
            data: data,
            cache: false,
            async: false,
            dataType: 'JSON',
            success: function(response) {
              for (rsp of response) {
                if (parseInt(rsp.eta_terlama) > parseInt(eta)) {
                  eta = rsp.eta_terlama
                }
                form_.eta_parts[rsp.id_part] = rsp.eta_terlama;
              }
              $("#eta_hari").val(eta);
              startTime = '<?= date_dmy(tanggal()) ?>';
              let eta_days = moment(startTime, 'DD/MM/YYYY').add(eta, 'day').format('DD/MM/YYYY');
              $("#eta").val(eta_days);
            },
            error: function() {
              alert("Something Went Wrong !");
            },
          });
        }

        $('#submitBtn').click(function() {
          $('#form_').validate({
            rules: {
              'checkbox': {
                required: true
              }
            },
            highlight: function(input) {
              $(input).parents('.form-group').addClass('has-error');
            },
            unhighlight: function(input) {
              $(input).parents('.form-group').removeClass('has-error');
            }
          })
          if ($('#form_').valid()) // check if form is valid
          {
            let job_return_flag = "<?= $row->job_return == 'ya' ? 1 : 0; ?>";
            // let eta = moment($("#eta").val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
            startTime = '<?= tanggal() ?>';

            i = 0;
            for (pr of form_.parts_order) {
              i2 = 0;
              for (prt of pr.parts) {
                let eta_days = moment(startTime, 'YYYY-MM-DD').add(form_.eta_parts[prt.id_part], 'day').format('YYYY-MM-DD');
                prt.eta_terlama = eta_days
                form_.parts_order[i].parts[i2] = prt
                i2++;
              }
              i++
            }
            var values = {
              id_sa_form: '<?= $row->id_sa_form ?>',
              id_customer: '<?= $row->id_customer ?>',
              no_buku_khusus_claim_c2: '<?= $row->no_buku_claim_c2 ?>',
              id_work_order: '<?= $row->id_work_order ?>',
              job_return_flag: job_return_flag,
              vor: $('#vehicle_offroad').val(),
              eta: $('#eta_hari').val(),
              penomoran_ulang: $('#penomoran_ulang').val(),
              flag_numbering: $('#flag_numbering').val(),
              keterangan_tambahan: $('#keterangan_tambahan').val(),
              parts_order: form_.parts_order,
              page: '<?= $isi ?>'
            };
            // console.log(values);
            // return false;
            if (confirm("Apakah anda yakin ?") == true) {
              $.ajax({
                beforeSend: function() {
                  $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                  $('#submitBtn').attr('disabled', true);
                },
                url: '<?= base_url('dealer/sa_form/save_send_notif') ?>',
                type: "POST",
                data: values,
                cache: false,
                async: false,
                dataType: 'JSON',
                success: function(response) {
                  if (response.status == 'sukses') {
                    window.location = response.link;
                  } else {
                    alert(response.pesan);
                    $('#submitBtn').attr('disabled', false);
                  }
                  $('#submitBtn').html('Save & Send Notify');
                },
                error: function() {
                  alert("Something Went Wrong !");
                  $('#submitBtn').html('Save & Send Notify');
                  $('#submitBtn').attr('disabled', false);
                },
              });
            } else {
              return false;
            }
          } else {
            alert('Silahkan isi field required !')
          }
        })
      </script>
    <?php } ?>
  </section>
  </div>