<div class="section-body">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4>Price List</h4>
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12 text-center">
                <div style="margin-top: 8px" id="message">
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
                </div>
              </div>
            </div>
          </div>
        </div>
       


          <div class="card-body table-responsive">
            <div class="table-responsive">
              <table id="example1" class="table" style="width:100%;">
                <thead>
                  <tr>
                    <th width="20px">No</th>
                    <th>Product</th>

                  </tr>
                </thead>
                <tbody></tbody>
              </table>
              <script>
                $(document).ready(function() {
                  dataTable = $('#example1').DataTable({
                    // "rowReorder": {
                    //   "selector": 'td:nth-child(2)'
                    // },
                    "responsive": true,
                    "dom": '<"wrapper"flipt>',
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
                      url: "<?php echo site_url('produk/fetch_data2'); ?>",
                      type: "POST",
                      dataSrc: "data",
                      data: function(d) {
                        return d;
                      },
                    },
                    "columnDefs": [{
                      "targets": [0],
                      "className": 'text-center'
                    }, ],
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
                });
              </script>
            </div>
          </div>
       
        <div class="card-footer text-right">

        </div>
      </div>
    </div>
  </div>
</div>
</section>
<div class="row">
  <div class="col-md-6">


  </div>

</div>
</div>