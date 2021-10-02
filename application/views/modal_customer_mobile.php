<style>
body .modal-dialog { 
    /* max-width: 70%; */
 
}
</style>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cari Data Customer</h5>
        <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="table-responsive">
                  <table id="example1" class="table" style="width:100% !important;table-layout:auto">
                    <thead>
                    <tr>
                          <th>No</th>
                          <th>Nama Customer</th>
                          <th>Alamat</th>
                          <th>Action</th>
                    </tr>
                    </thead> 
                    <tbody></tbody>
                    </table>
                       
                        <script>
                          $(document).ready(function() {
                          dataTable = $('#example1').DataTable({
                              "processing": true,
                              "serverSide": true,
                              "scrollX": false,
                              "paging": true,
                              "autoWidth":true,
                             
                              "language": {
                                "infoFiltered": "",
                                "processing": "",
                              },
                              "order": [],
                              "lengthMenu": [
                                [5,10, 25, 50, 75, 100],
                                [5,10, 25, 50, 75, 100]
                              ],
                              "ajax": {
                                url: "<?php echo site_url('pelanggan/fetch_data_modals_mobile'); ?>",
                                type: "POST",
                                dataSrc: "data",
                                data: function(d) {
                                   return d;
                                },
                              },
                              "columnDefs": [
                                { "targets":[0],"orderable":false},
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
                          });
                        </script>
                        </div>
                        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>