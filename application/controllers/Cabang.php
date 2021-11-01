<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cabang extends MY_Controller {

    // protected $access = array('Admin', 'Pimpinan','Finance');
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('Cabang_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $cabang = $this->Cabang_model->get_all();

        $data = array(
            'cabang_data' => $cabang
        );
        $this->load->view('header');
        $this->load->view('cabang_list', $data);
        $this->load->view('footer');
    }

    public function fetch_data(){
    $starts       = $this->input->post("start");
    $length       = $this->input->post("length");
    $LIMIT        = "LIMIT $starts, $length ";
    $draw         = $this->input->post("draw");
    $search       = $this->input->post("search")["value"];
    $orders       = isset($_POST["order"]) ? $_POST["order"] : ''; 
    
    $where ="WHERE 1=1";
    $searchingColumn;
    $result=array();
    if (isset($search)) {
      if ($search != '') {
         $searchingColumn = $search;
            $where .= " AND (nama_cabang LIKE '%$search%'
                         
                            )";
          }
      }

    if (isset($orders)) {
        if ($orders != '') {
          $order = $orders;
          $order_column = [];
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
    $fetch = $this->db->query("SELECT * from cabang $where");
    $fetch2 = $this->db->query("SELECT * from cabang ");
    foreach($fetch->result() as $rows){
        $button1= "<a href=".base_url('cabang/read/'.$rows->id)." class='btn btn-icon icon-left btn-light'><i class='fa fa-eye'></i></a>";
        $button2= "<a href=".base_url('cabang/update/'.$rows->id)." class='btn btn-icon icon-left btn-warning'><i class='fa fa-pencil-square-o'></i></a>";
        $button3 = "<a href=".base_url('cabang/delete/'.$rows->id)." class='btn btn-icon icon-left btn-danger' onclick='javasciprt: return confirm(\"Are You Sure ?\")''><i class='fa fa-trash'></i></a>";
        $sub_array=array();
        $sub_array[]=$index;
        $sub_array[]=$rows->kode_cabang;
        $sub_array[]=$rows->nama_cabang;
 
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

    public function read($id) 
    {
        $row = $this->Cabang_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'kode_cabang' => $row->kode_cabang,
		'nama_cabang' => $row->nama_cabang,
	    );
            $this->load->view('header');
            $this->load->view('cabang_read', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('cabang'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('cabang/create_action'),
	    'id' => set_value('id'),
	    'kode_cabang' => set_value('kode_cabang'),
	    'nama_cabang' => set_value('nama_cabang'),
	);

        $this->load->view('header');
        $this->load->view('cabang_form', $data);
        $this->load->view('footer');
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'kode_cabang' => $this->input->post('kode_cabang',TRUE),
		'nama_cabang' => $this->input->post('nama_cabang',TRUE),
	    );

            $this->Cabang_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('cabang'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Cabang_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('cabang/update_action'),
		'id' => set_value('id', $row->id),
		'kode_cabang' => set_value('kode_cabang', $row->kode_cabang),
		'nama_cabang' => set_value('nama_cabang', $row->nama_cabang),
	    );
            $this->load->view('header');
            $this->load->view('cabang_form', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('cabang'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'kode_cabang' => $this->input->post('kode_cabang',TRUE),
		'nama_cabang' => $this->input->post('nama_cabang',TRUE),
	    );

            $this->Cabang_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('cabang'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Cabang_model->get_by_id($id);

        if ($row) {
            $this->Cabang_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('cabang'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('cabang'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('kode_cabang', 'kode cabang', 'trim|required');
	$this->form_validation->set_rules('nama_cabang', 'nama cabang', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Cabang.php */
/* Location: ./application/controllers/Cabang.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-11-01 02:50:52 */
/* http://harviacode.com */