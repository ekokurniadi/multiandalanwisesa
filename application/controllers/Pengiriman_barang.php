<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pengiriman_barang extends MY_Controller {

    // protected $access = array('Admin', 'Pimpinan','Finance');
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('Pengiriman_barang_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'pengiriman_barang/index.dart?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'pengiriman_barang/index.dart?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'pengiriman_barang/index.dart';
            $config['first_url'] = base_url() . 'pengiriman_barang/index.dart';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Pengiriman_barang_model->total_rows($q);
        $pengiriman_barang = $this->Pengiriman_barang_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'pengiriman_barang_data' => $pengiriman_barang,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('header');
        $this->load->view('pengiriman_barang_list', $data);
        $this->load->view('footer');
    }

    public function read($id) 
    {
        $row = $this->Pengiriman_barang_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'id_pengiriman' => $row->id_pengiriman,
		'no_do' => $row->no_do,
		'no_po' => $row->no_po,
		'customer' => $row->customer,
		'tanggal_pengiriman' => $row->tanggal_pengiriman,
		'waktu_pengiriman' => $row->waktu_pengiriman,
		'status' => $row->status,
		'jenis_pengiriman' => $row->jenis_pengiriman,
		'driver' => $row->driver,
		'penerima' => $row->penerima,
	    );
            $this->load->view('header');
            $this->load->view('pengiriman_barang_read', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('pengiriman_barang'));
        }
    }

    function acak($panjang)
    {
      $karakter= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';
      $string = '';
      for ($i = 0; $i < $panjang; $i++) {
        $pos = rand(0, strlen($karakter)-1);
        $string .= $karakter{$pos};
      }
      return $string;
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('pengiriman_barang/create_action'),
	    'id' => set_value('id'),
	    'id_pengiriman' => $this->acak(10),
	    'no_do' => set_value('no_do'),
	    'no_po' => set_value('no_po'),
	    'customer' => set_value('customer'),
	    'tanggal_pengiriman' => set_value('tanggal_pengiriman'),
	    'waktu_pengiriman' => set_value('waktu_pengiriman'),
	    'status' => set_value('status'),
	    'jenis_pengiriman' => set_value('jenis_pengiriman'),
	    'nama_ekspedisi' => set_value('nama_ekspedisi'),
	    'driver' => set_value('driver'),
	    'penerima' => set_value('penerima'),
	);

        $this->load->view('header');
        $this->load->view('pengiriman_barang_form', $data);
        $this->load->view('footer');
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
       
        $where = "WHERE 1=1 ";
        if($_SESSION['level']=="Admin"){
            $where .=" ";
        }else{
            $where .=" And cabang ='{$_SESSION['cabang']}' ";
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
        $fetch = $this->db->query("SELECT * FROM pengiriman_barang $where");
        $fetch2 = $this->db->query("SELECT * FROM pengiriman_barang ");
        foreach($fetch->result() as $rows){
            $button1= "<a href=".base_url('pengiriman_barang/read/'.$rows->id)." class='btn btn-icon icon-left btn-light'><i class='fa fa-eye'></i></a>";
          
            $button2= "<a href=".base_url('pengiriman_barang/update/'.$rows->id)." class='btn btn-icon icon-left btn-warning'><i class='fa fa-pencil-square-o'></i></a>";
           
            $button3 = "<a href=".base_url('pengiriman_barang/delete/'.$rows->id)." class='btn btn-icon icon-left btn-danger' onclick='javasciprt: return confirm(\"Are You Sure ?\")''><i class='fa fa-trash'></i></a>";
          
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
            $sub_array[]=$button1." ".$button2." ".$button3;
           
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

    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'id_pengiriman' => $this->input->post('id_pengiriman',TRUE),
		'no_do' => $this->input->post('no_do',TRUE),
		'no_po' => $this->input->post('no_po',TRUE),
		'customer' => $this->input->post('customer',TRUE),
		'tanggal_pengiriman' => $this->input->post('tanggal_pengiriman',TRUE),
		'waktu_pengiriman' => $this->input->post('waktu_pengiriman',TRUE),
		'status' => $this->input->post('status',TRUE),
		'jenis_pengiriman' => $this->input->post('jenis_pengiriman',TRUE),
		'nama_ekspedisi' => $this->input->post('nama_ekspedisi',TRUE),
		'driver' => $this->input->post('driver',TRUE),
		'penerima' => $this->input->post('penerima',TRUE),
        'cabang'=> $_SESSION['cabang'],
	    );

            $this->Pengiriman_barang_model->insert($data);
            $this->db->insert('history_pengiriman',$data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('pengiriman_barang'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Pengiriman_barang_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('pengiriman_barang/update_action'),
		'id' => set_value('id', $row->id),
		'id_pengiriman' => set_value('id_pengiriman', $row->id_pengiriman),
		'no_do' => set_value('no_do', $row->no_do),
		'no_po' => set_value('no_po', $row->no_po),
		'customer' => set_value('customer', $row->customer),
		'tanggal_pengiriman' => set_value('tanggal_pengiriman', $row->tanggal_pengiriman),
		'waktu_pengiriman' => set_value('waktu_pengiriman', $row->waktu_pengiriman),
		'status' => set_value('status', $row->status),
		'jenis_pengiriman' => set_value('jenis_pengiriman', $row->jenis_pengiriman),
		'driver' => set_value('driver', $row->driver),
		'nama_ekspedisi' => set_value('nama_ekspedisi', $row->nama_ekspedisi),
		'penerima' => set_value('penerima', $row->penerima),
	    );
            $this->load->view('header');
            $this->load->view('pengiriman_barang_form', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('pengiriman_barang'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'id_pengiriman' => $this->input->post('id_pengiriman',TRUE),
		'no_do' => $this->input->post('no_do',TRUE),
		'no_po' => $this->input->post('no_po',TRUE),
		'customer' => $this->input->post('customer',TRUE),
		'tanggal_pengiriman' => $this->input->post('tanggal_pengiriman',TRUE),
		'waktu_pengiriman' => $this->input->post('waktu_pengiriman',TRUE),
		'status' => $this->input->post('status',TRUE),
		'jenis_pengiriman' => $this->input->post('jenis_pengiriman',TRUE),
		'nama_ekspedisi' => $this->input->post('nama_ekspedisi',TRUE),
		'driver' => $this->input->post('driver',TRUE),
		'penerima' => $this->input->post('penerima',TRUE),
        'cabang'=> $_SESSION['cabang'],
	    );

            $this->Pengiriman_barang_model->update($this->input->post('id', TRUE), $data);
            $this->db->insert('history_pengiriman',$data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('pengiriman_barang'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Pengiriman_barang_model->get_by_id($id);

        if ($row) {
            $this->Pengiriman_barang_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('pengiriman_barang'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('pengiriman_barang'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('no_do', 'no do', 'trim|required');
	$this->form_validation->set_rules('no_po', 'no po', 'trim|required');
	$this->form_validation->set_rules('customer', 'customer', 'trim|required');
	$this->form_validation->set_rules('tanggal_pengiriman', 'tanggal pengiriman', 'trim|required');
	$this->form_validation->set_rules('waktu_pengiriman', 'waktu pengiriman', 'trim|required');
	$this->form_validation->set_rules('status', 'status', 'trim|required');
	$this->form_validation->set_rules('jenis_pengiriman', 'jenis pengiriman', 'trim|required');
	$this->form_validation->set_rules('driver', 'driver', 'trim|required');
	$this->form_validation->set_rules('penerima', 'penerima', '');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Pengiriman_barang.php */
/* Location: ./application/controllers/Pengiriman_barang.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-06-08 10:29:30 */
/* http://harviacode.com */