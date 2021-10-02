<script src="<?= base_url("js/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("js/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("js/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("js/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("js/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("js/lodash.min.js") ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?= base_url("js/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("js/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("js/daterangepicker.css") ?>" />
<script>
  Vue.use(VueNumeric.default);
</script>
<div class="main">
  <section class="section">


    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <h4>Form Jadwal </h4>
            </div>
            <form id="form_" class="form-horizontal" method="post" enctype="multipart/form-data">

              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Id Jadwal <?php echo form_error('id_sales') ?></label>
                <div class="col-sm-12">
                  <input type="text" readonly class="form-control" name="id_jadwal" id="id_jadwal" placeholder="Id Sales" value="<?php echo $id_jadwal; ?>" />
                </div>
              </div>
           
                  <input type="hidden" readonly class="form-control" name="id_sales" id="id_sales" placeholder="Id Sales" value="<?php echo $id_sales; ?>" />
               

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

              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar"> <?php echo form_error('id_sales') ?></label>
                <div class="col-sm-12">
                  <input type="button" class="btn btn-danger btn-flat btn-block" value="Detail Waktu" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar"> <?php echo form_error('id_sales') ?></label>
                <div class="col-sm-12">
                  <div class="table-responsive">
                    <table class="table table-condensed">
                      <?php $detail = $this->db->get_where('jadwal_detail', array('id_jadwal' => $id_jadwal))->result() ?>
                      <thead v-for="(wt,index) of detail">
                        <tr>
                          <td width="5%">No</td>
                          <td>{{index+1}}</td>
                        </tr>
                        <tr>
                          <td>Tanggal</td>
                          <td>
                            {{wt.tanggal}}
                          </td>
                        </tr>
                        <tr>
                          <td>Jam</td>
                          <td>{{wt.jam}}</td>
                        </tr>
                        <tr>
                          <td>Jenis Kunjungan</td>
                          <td>{{wt.jenis_kunjungan}}</td>
                        </tr>
                        <tr>
                          <td>Perjalanan Dinas</td>
                          <td>{{wt.perjalanan_dinas}}</td>
                        </tr>
                        <tr>
                          <td>Action</td>
                          <td align="left" v-if="mode=='edit'|| mode=='create'">
                            <button @click.prevent="del(index)" type="button" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i></button>
                          </td>
                        </tr>
                      </thead>
                      
                    </table>
                    <table class="table" v-if="mode!='read'">
                      <tfoot>
                      <tr>
                          <td width="5%">Perjalanan Dinas</td>
                          <td>
                            <div class="form-group">
                              <div class="col-sm-12">
                                <select name="perjalanan_dinas" id="perjalanan_dinas" v-model="detstc.perjalanan_dinas" class="form-control">
                                  <option value="">choose</option>
                                  <option value="Ya">Ya</option>
                                  <option value="Tidak">Tidak</option>
                                </select>
                               
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td width="5%">Jenis Kunjungan</td>
                          <td>
                            <div class="form-group">
                              <div class="col-sm-12">
                                <select name="jenis_kunjungan" id="jenis_kunjungan" v-model="detstc.jenis_kunjungan" class="form-control">
                                  <option value="">choose</option>
                                  <option value="Online">Online</option>
                                  <option value="Langsung">Langsung</option>
                                </select>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>Tanggal</td>
                          <td>
                            <div class="form-group" id="datetimepicker3" data-target-input="nearest">

                              <div class="col-sm-12">
                                <input type="date" class="form-control" v-model="detstc.tanggal" />
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>Jam</td>
                          <td>
                            <div class="form-group" id="datetimepicker4" data-target-input="neareste">
                              <div class="col-sm-12">
                                <input type="time" class="form-control" v-model="detstc.jam" />
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>Action</td>
                          <td align="left" v-if="mode=='edit'|| mode=='create'">
                            <button @click.prevent="addDetails" type="button" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus"></i></button>
                          </td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>

              <div class="card-footer text-left">
                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                <button v-if="mode!='read'" type="button" id="submitBtn" class="btn btn-primary"><span class="fa fa-edit"></span><?php echo $button ?></button>
                <a href="<?php echo site_url('jadwal?session='.$id_sales) ?>" class="btn btn-icon icon-left btn-success">Cancel</a>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php $this->load->view('modal_customer_mobile') ?>
<script>
  function getCustomer(id) {
    $.ajax({
      beforeSend: function() {
        $('#searchCustomer').attr('disabled', true);
        $('#searchCustomer').html('<i class="fa fa-spinner fa-spin">');
      },
      url: '<?= base_url('pelanggan/getById') ?>',
      type: "POST",
      data: {
        id
      },
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
<script>
  <?php date_default_timezone_set('Asia/Jakarta');?>
  var form_ = new Vue({
    el: '#form_',
    data: {
      mode: '<?php echo $mode ?>',
      detstc: {
        id_jadwal: '',
        no_prospek: '',
        tanggal: '',
        jam: '',
        status: '',
        jenis_kunjungan: '',
        perjalanan_dinas: '',
        hasil:''

      },
      detail: <?php echo isset($detail) ? json_encode($detail) : '[]' ?>,
    },
    methods: {
      clearDetail: function() {
        this.detstc = {}
      },
      addDetails: function() {
        console.log(this.detail);
        console.log(this.detstc);
        if (this.detstc.tanggal === '' || this.detstc.jam === '' || this.detstc.jenis_kunjungan === '' || this.detstc.tanggal === undefined || this.detstc.jam === undefined || this.detstc.jenis_kunjungan === undefined) {
          alert('Mohon lengkapi data');
          return false;
        }
        this.detail.push(this.detstc);
        this.clearDetail();
      },
      del: function(index) {
        this.detail.splice(index, 1);
      }
    }
  });
  $('#submitBtn').click(function() {
    if(form_.detail==""){
      alert('Mohon lengkapi data');
    }else{
      var values = {
      detail: form_.detail,
    };
    var form = $('#form_').serializeArray();
    for (field of form) {
      values[field.name] = field.value;
    }

    $.ajax({
      beforeSend: function() {
        $('#submitBtn').attr('disabled', true);
        $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
      },
      url: '<?=$action?>',
      type: "POST",
      data: values,
      cache: false,
      dataType: "JSON",
      success: function(response) {
        $('#submitBtn').html('<i class="fa fa-save"></i> Save');
        if (response.status == 'sukses') {
          window.location = response.link;
        } else {
          $('#submitBtn').attr('disabled', false);
          alert(response.pesan);
        }
      },
      error:function(){
        alert("Gagal");
        $('#submitBtn').attr('disabled', false);
      }
    });
    }
   
  });
</script>