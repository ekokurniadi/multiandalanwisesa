<?php 

class Do_tracking extends MY_Controller{
        
    public function index(){
        $this->load->view('header');
        $this->load->view('do_tracking');
        $this->load->view('footer');
    }
    public function index_mobile(){
        $this->load->view('header2');
        $this->load->view('do_tracking2');
        $this->load->view('footer2');
    }

    public function fetch_data(){
        $starts       = $this->input->post("start");
        $length       = $this->input->post("length");
        $LIMIT        = "LIMIT  $starts, $length ";
        $draw         = $this->input->post("draw");
        $search       = $this->input->post('search')['value'];
        $orders       = isset($_POST['order']) ? $_POST['order'] : ''; 
        $filter       = $this->input->post('filter');
        $kata         = $this->input->post('kata_kunci');
        if($filter == ""){
            $where ="WHERE 1=1 and no_do = '$kata'";
        }else{
            $where = "WHERE 1=1 AND $filter = '$kata'";
        }
        $searchingColumn;
        $result=array();
        if (isset($search)) {
          if ($search != '') {
             $searchingColumn = $search;
                $where .= " AND (no_do LIKE '%$search%'
                                OR no_po LIKE '%$search%'
                                OR customer LIKE '%$search%'
                                OR tanggal_pengiriman LIKE '%$search%'
                                OR waktu_pengiriman LIKE '%$search%'
                                OR status LIKE '%$search%'
                                OR driver LIKE '%$search%'
                                OR penerima LIKE '%$search%'
                                )";
              }
          }

        if (isset($orders)) {
            if ($orders != '') {
              $order = $orders;
              $order_column = ['','no_do','no_po','customer','tanggal_pengiriman','waktu_pengiriman','status','driver','peneriman'];
              $order_clm  = $order_column[$order[0]['column']];
              $order_by   = $order[0]['dir'];
              $where .= " ORDER BY $order_clm $order_by ";
            } else {
              $where .= " ORDER BY id ASC ";
            }
          } else {
            $where .= " ORDER BY id ASC ";
          }
          if (isset($LIMIT)) {
            if ($LIMIT != '') {
              $where .= ' ' . $LIMIT;
            }
          }
        $index=1;
        $button="";
        $fetch = $this->db->query("SELECT * FROM history_pengiriman $where");
        $fetch2 = $this->db->query("SELECT * FROM history_pengiriman $where");
        foreach($fetch->result() as $rows){
            $sub_array=array();
            $sub_array[]=$index;
            $sub_array[]=$rows->no_do;
            $sub_array[]=$rows->no_po;
            $sub_array[]=$rows->customer;
            $sub_array[]=tgl_indo($rows->tanggal_pengiriman);
            $sub_array[]=$rows->waktu_pengiriman;
            $sub_array[]=$rows->nama_ekspedisi;
            $sub_array[]=$rows->status;
            $sub_array[]=$rows->driver;
            $sub_array[]=$rows->penerima;
           
            $result[]      = $sub_array;
            $index++;
        }
        $output = array(
          "draw"            =>     intval($this->input->post("draw")),
          "recordsFiltered" =>     $fetch2->num_rows(),
          "data"            =>     $result,
         
        );
        echo json_encode($output);

    }
}
?>