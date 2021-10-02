<div class="main-content">
<section class="section">
  <div class="section-header">
    <h1> DO Tracking </h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item active"><a href="<?php echo base_url(); ?>dashboard"><i class="fa fa-dashboard"></i> Home</a></div>
      <div class="breadcrumb-item"><a href="#"> DO Tracking </a></div>
    </div>
  </div>



  <div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>DO Tracking</h4>
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="varchar">Filter </label>
                                    <div class="col-sm-12">
                                        <select name="filter" id="filter" class="form-control">
                                            <option value="">Choose an option</option>
                                            <option value="no_po">Cari Berdasarkan No PO</option>
                                            <option value="no_do">Cari Berdasarkan No DO</option>
                                        </select>
                                    </div>
                                </div>  
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="varchar">Kata Kunci </label>
                                    <div class="col-sm-12">
                                        <input type="text" name="kata_kunci" id="kata_kunci" class="form-control" placeholder="Kata Kunci">
                                    </div>
                                </div>  
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="varchar">&nbsp; </label>
                                    <div class="col-sm-12">
                                        <button class="btn btn-primary btn-md" id="btn-cari"><span class="fa fa-search"></span> Cari</button>
                                        <button class="btn btn-warning btn-md" id="btn-reset"><span class="fa fa-trash"></span> Reset</button>
                                    </div>
                                </div>  
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Detail</h5>
                            </div>
                        </div>
                        <div class="row">
                           <div class="table-responsive">
                            <table class="table" id="example1" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NO DO</th>
                                        <th>NO PO</th>
                                        <th>Customer</th>
                                        <th>Tanggal Pengiriman</th>
                                        <th>Jam Pengiriman</th>
                                        <th>Nama Ekspedisi</th>
                                        <th>Status</th>
                                        <th>Driver</th>
                                        <th>Penerima</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <script>
                                  $(document).ready(function(){
                                    $('#btn-cari').click(function(){
                                    $('#filter').val();
                                    $('#kata_kunci').val();
                                    dataTable.draw();
                                    });
                                    $('#btn-reset').click(function(){
                                    $('#filter').val('');
                                    $('#kata_kunci').val('');
                                    dataTable.draw();
                                    });
                                });
                                $(document).ready(function() {
                                dataTable = $('#example1').DataTable({
                                    "processing": true,
                                    "paging": true,
                                    "serverSide": true,
                                    "scrollX": true,
                                    "language": {
                                        "infoFiltered": "",
                                        "processing": "",
                                    },
                                    "order": [],
                                    "lengthMenu": [
                                        [10, 25, 50, 75, 100],
                                        [10, 25, 50, 75, 100]
                                    ],
                                    "ajax": {
                                        url: "<?php echo site_url('do_tracking/fetch_data'); ?>",
                                        type: "POST",
                                        dataSrc: "data",
                                        data: function(d) {
                                            d.filter= $('#filter').val();
                                            d.kata_kunci= $('#kata_kunci').val();
                                        },
                                    },
                                    "columnDefs": [
                                        {
                                        "targets": [0],
                                        "className": 'text-center'
                                        },
                                    ],
                                    });
                                    dataTable.on('draw.dt', function() {
                                    var info = dataTable.page.info();
                                    dataTable.column(0, {
                                        search: 'applied',
                                        order: 'applied',
                                        page: 'applied'
                                    }).nodes().each(function(cell, i) {
                                        cell.innerHTML = i + 1 + info.start + ".";
                                    });
                                });
                                new $.fn.dataTable.FixedHeader( table );
                                });
                                </script>
                           </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
  </section>
  </div>