<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pelanggan extends MY_Controller {

    // protected $access = array('Admin', 'Pimpinan','Finance');
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('Pelanggan_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'pelanggan/index.dart?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'pelanggan/index.dart?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'pelanggan/index.dart';
            $config['first_url'] = base_url() . 'pelanggan/index.dart';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Pelanggan_model->total_rows($q);
        $pelanggan = $this->Pelanggan_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'pelanggan_data' => $pelanggan,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('header');
        $this->load->view('pelanggan_list', $data);
        $this->load->view('footer');
    }

    public function index_mobile(){
      $this->load->view('header2');
      $this->load->view('pelanggan2');
      $this->load->view('footer2');
  }


    public function fetch_data(){
        $starts       = $this->input->post("start");
        $length       = $this->input->post("length");
        $LIMIT        = "LIMIT  $starts, $length ";
        $draw         = $this->input->post("draw");
        $search       = $this->input->post('search')['value'];
        $orders       = isset($_POST['order']) ? $_POST['order'] : ''; 
        
        $where ="WHERE 1=1";
        $searchingColumn;
        $result=array();
        if (isset($search)) {
          if ($search != '') {
             $searchingColumn = $search;
                $where .= " AND (kode_customer LIKE '%$search%'
                                OR npwp LIKE '%$search%'
                                OR nama_customer LIKE '%$search%'
                                OR alamat LIKE '%$search%'
                                OR telepon LIKE '%$search%'
                                OR kode_pos LIKE '%$search%'
                                OR passpor LIKE '%$search%'
                                )";
              }
          }

        if (isset($orders)) {
            if ($orders != '') {
              $order = $orders;
              $order_column = ['','kode_customer','npwp','nama_customer','alamat','telepon','kode_pos','passpor'];
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
        $fetch = $this->db->query("SELECT * FROM pelanggan $where");
        $fetch2 = $this->db->query("SELECT * FROM pelanggan");
        foreach($fetch->result() as $rows){
            $button1= "<a href=".base_url('pelanggan/read/'.$rows->id)." class='btn btn-icon icon-left btn-light'><i class='fa fa-eye'></i></a>";
          
            $button2= "<a href=".base_url('pelanggan/update/'.$rows->id)." class='btn btn-icon icon-left btn-warning'><i class='fa fa-pencil-square-o'></i></a>";
           
            $button3 = "<a href=".base_url('pelanggan/delete/'.$rows->id)." class='btn btn-icon icon-left btn-danger' onclick='javasciprt: return confirm(\"Are You Sure ?\")''><i class='fa fa-trash'></i></a>";
          
            $sub_array=array();
            $sub_array[]=$index;
            $sub_array[]=$rows->kode_customer;
            $sub_array[]=$rows->npwp;
            $sub_array[]=$rows->nama_customer;
            $sub_array[]=$rows->alamat;
            $sub_array[]=$rows->telepon;
            $sub_array[]=$rows->kode_pos;
            $sub_array[]=$rows->passpor;
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

    public function fetch_data2(){
      $starts       = $this->input->post("start");
      $length       = $this->input->post("length");
      $LIMIT        = "LIMIT  $starts, $length ";
      $draw         = $this->input->post("draw");
      $search       = $this->input->post('search')['value'];
      $orders       = isset($_POST['order']) ? $_POST['order'] : ''; 
      
      $where ="WHERE 1=1";
      $searchingColumn;
      $result=array();
      if (isset($search)) {
        if ($search != '') {
           $searchingColumn = $search;
              $where .= " AND (kode_customer LIKE '%$search%'
                              OR npwp LIKE '%$search%'
                              OR nama_customer LIKE '%$search%'
                              OR alamat LIKE '%$search%'
                              OR telepon LIKE '%$search%'
                              OR kode_pos LIKE '%$search%'
                              OR passpor LIKE '%$search%'
                              )";
            }
        }

      if (isset($orders)) {
          if ($orders != '') {
            $order = $orders;
            $order_column = ['','kode_customer','npwp','nama_customer','alamat','telepon','kode_pos','passpor'];
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
      $fetch = $this->db->query("SELECT * FROM pelanggan $where");
      $fetch2 = $this->db->query("SELECT * FROM pelanggan");
      foreach($fetch->result() as $rows){
          $button1= "<a href=".base_url('pelanggan/read2/'.$rows->id)." class='btn btn-icon icon-left btn-light'><i class='fa fa-eye'></i></a>";
        
          $button2= "<a href=".base_url('pelanggan/update2/'.$rows->id)." class='btn btn-icon icon-left btn-warning'><i class='fa fa-pencil-square-o'></i></a>";
         
          $button3 = "<a href=".base_url('pelanggan/delete2/'.$rows->id)." class='btn btn-icon icon-left btn-danger' onclick='javasciprt: return confirm(\"Are You Sure ?\")''><i class='fa fa-trash'></i></a>";
        
          $sub_array=array();
          $sub_array[]=$index;
          $sub_array[]=$rows->kode_customer;
          $sub_array[]=$rows->npwp;
          $sub_array[]=$rows->nama_customer;
          $sub_array[]=$rows->alamat;
          $sub_array[]=$rows->telepon;
          $sub_array[]=$rows->kode_pos;
          $sub_array[]=$rows->passpor;
          $sub_array[]=$button1." ". $button2." ".$button3;
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

    public function fetch_data_modals(){
      $starts       = $this->input->post("start");
      $length       = $this->input->post("length");
      $LIMIT        = "LIMIT $starts, $length ";
      $draw         = $this->input->post("draw");
      $search       = $this->input->post('search')['value'];
      $orders       = isset($_POST['order']) ? $_POST['order'] : ''; 
      $filter       = $this->input->post('filter');

      
      $where ="WHERE 1=1 ";
      
      $searchingColumn;
      $result=array();
      if (isset($search)) {
        if ($search != '') {
           $searchingColumn = $search;
              $where .= " AND (npwp LIKE '%$search%'
                              OR nama_customer LIKE '%$search%'
                              OR alamat LIKE '%$search%'
                              OR telepon LIKE '%$search%'
                              OR kode_pos LIKE '%$search%'
                             
                              )";
            }
        }

      if (isset($orders)) {
          if ($orders != '') {
            $order = $orders;
            $order_column = ['','npwp','nama_customer','alamat','telepon','kode_pos'];
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
      $fetch = $this->db->query("SELECT * FROM pelanggan $where");
      $fetch2 = $this->db->query("SELECT * FROM pelanggan");
      $status ="";
      foreach($fetch->result() as $rows){
        
          $button1 = "<button onclick='return getCustomer(".$rows->id.")' class='btn btn-flat btn-xs btn-success' data-dismiss='modal' type='button'><i class='fa fa-check'></i></button>";
          $sub_array=array();
          $sub_array[]=$index;
          $sub_array[]=$rows->npwp;
          $sub_array[]=$rows->nama_customer;
          $sub_array[]=$rows->alamat;
          $sub_array[]=$rows->telepon;
          $sub_array[]=$rows->kode_pos;
          $sub_array[]=$button1;
          $result[]   = $sub_array;
          $index++;
      }
      
      $output = array(
        "draw"            =>     intval($this->input->post("draw")),
        "recordsFiltered" =>     $fetch2->num_rows(),
        "data"            =>     $result,
       
      );
      echo json_encode($output);

  }

  public function getById(){
    $id = $this->input->post('id');
    $query = $this->db->query("SELECT * FROM pelanggan where id='$id'");
    if($query->num_rows() > 0){
      $data =array(
        "id"=>$query->row()->id,
        "nama"=>$query->row()->nama_customer,
      );
      $response = array("status"=>"sukses","value"=>$data);
    } else{
      $response = array("status"=>"error","pesan"=>"Data tidak ditemukan");
    }
    echo json_encode($response);
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

    public function upload_data(){
        $this->load->view('header');
        $this->load->view('import_pelanggan');
        $this->load->view('footer');
    }

    public function read($id) 
    {
        $row = $this->Pelanggan_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'kode_customer' => $row->kode_customer,
		'npwp' => $row->npwp,
		'nama_customer' => $row->nama_customer,
		'alamat' => $row->alamat,
		'telepon' => $row->telepon,
		'kode_pos' => $row->kode_pos,
		'passpor' => $row->passpor,
	    );
            $this->load->view('header');
            $this->load->view('pelanggan_read', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('pelanggan'));
        }
    }
    public function read2($id) 
    {
        $row = $this->Pelanggan_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'kode_customer' => $row->kode_customer,
		'npwp' => $row->npwp,
		'nama_customer' => $row->nama_customer,
		'alamat' => $row->alamat,
		'telepon' => $row->telepon,
		'kode_pos' => $row->kode_pos,
		'passpor' => $row->passpor,
	    );
            $this->load->view('header2');
            $this->load->view('pelanggan_read2', $data);
            $this->load->view('footer2');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('pelanggan/index_mobile'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('pelanggan/create_action'),
	    'id' => set_value('id'),
	    'kode_customer' => $this->acak(10),
	    'npwp' => set_value('npwp'),
	    'nama_customer' => set_value('nama_customer'),
	    'alamat' => set_value('alamat'),
	    'telepon' => set_value('telepon'),
	    'kode_pos' => set_value('kode_pos'),
	    'passpor' => set_value('passpor'),
	);

        $this->load->view('header');
        $this->load->view('pelanggan_form', $data);
        $this->load->view('footer');
    }

    public function create_mobile() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('pelanggan/create_action_mobile'),
	    'id' => set_value('id'),
	    'kode_customer' => $this->acak(10),
	    'npwp' => set_value('npwp'),
	    'nama_customer' => set_value('nama_customer'),
	    'alamat' => set_value('alamat'),
	    'telepon' => set_value('telepon'),
	    'kode_pos' => set_value('kode_pos'),
	    'passpor' => set_value('passpor'),
	);

        $this->load->view('header2');
        $this->load->view('pelanggan_form2', $data);
        $this->load->view('footer2');
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'kode_customer' => $this->input->post('kode_customer',TRUE),
		'npwp' => $this->input->post('npwp',TRUE),
		'nama_customer' => $this->input->post('nama_customer',TRUE),
		'alamat' => $this->input->post('alamat',TRUE),
		'telepon' => $this->input->post('telepon',TRUE),
		'kode_pos' => $this->input->post('kode_pos',TRUE),
		'passpor' => $this->input->post('passpor',TRUE),
	    );

            $this->Pelanggan_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('pelanggan'));
        }
    }

    public function create_action_mobile() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'kode_customer' => $this->input->post('kode_customer',TRUE),
		'npwp' => $this->input->post('npwp',TRUE),
		'nama_customer' => $this->input->post('nama_customer',TRUE),
		'alamat' => $this->input->post('alamat',TRUE),
		'telepon' => $this->input->post('telepon',TRUE),
		'kode_pos' => $this->input->post('kode_pos',TRUE),
		'passpor' => $this->input->post('passpor',TRUE),
	    );

            $this->Pelanggan_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('pelanggan/index_mobile'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Pelanggan_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('pelanggan/update_action'),
		'id' => set_value('id', $row->id),
		'kode_customer' => set_value('kode_customer', $row->kode_customer),
		'npwp' => set_value('npwp', $row->npwp),
		'nama_customer' => set_value('nama_customer', $row->nama_customer),
		'alamat' => set_value('alamat', $row->alamat),
		'telepon' => set_value('telepon', $row->telepon),
		'kode_pos' => set_value('kode_pos', $row->kode_pos),
		'passpor' => set_value('passpor', $row->passpor),
	    );
            $this->load->view('header');
            $this->load->view('pelanggan_form', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('pelanggan'));
        }
    }

    public function update2($id) 
    {
        $row = $this->Pelanggan_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('pelanggan/update_action_mobile'),
		'id' => set_value('id', $row->id),
		'kode_customer' => set_value('kode_customer', $row->kode_customer),
		'npwp' => set_value('npwp', $row->npwp),
		'nama_customer' => set_value('nama_customer', $row->nama_customer),
		'alamat' => set_value('alamat', $row->alamat),
		'telepon' => set_value('telepon', $row->telepon),
		'kode_pos' => set_value('kode_pos', $row->kode_pos),
		'passpor' => set_value('passpor', $row->passpor),
	    );
            $this->load->view('header2');
            $this->load->view('pelanggan_form2', $data);
            $this->load->view('footer2');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('pelanggan/index_mobile'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'kode_customer' => $this->input->post('kode_customer',TRUE),
		'npwp' => $this->input->post('npwp',TRUE),
		'nama_customer' => $this->input->post('nama_customer',TRUE),
		'alamat' => $this->input->post('alamat',TRUE),
		'telepon' => $this->input->post('telepon',TRUE),
		'kode_pos' => $this->input->post('kode_pos',TRUE),
		'passpor' => $this->input->post('passpor',TRUE),
	    );

            $this->Pelanggan_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('pelanggan'));
        }
    }

    public function update_action_mobile() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'kode_customer' => $this->input->post('kode_customer',TRUE),
		'npwp' => $this->input->post('npwp',TRUE),
		'nama_customer' => $this->input->post('nama_customer',TRUE),
		'alamat' => $this->input->post('alamat',TRUE),
		'telepon' => $this->input->post('telepon',TRUE),
		'kode_pos' => $this->input->post('kode_pos',TRUE),
		'passpor' => $this->input->post('passpor',TRUE),
	    );

            $this->Pelanggan_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('pelanggan/index_mobile'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Pelanggan_model->get_by_id($id);

        if ($row) {
            $this->Pelanggan_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('pelanggan'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('pelanggan'));
        }
    }

    public function delete_mobile($id) 
    {
        $row = $this->Pelanggan_model->get_by_id($id);

        if ($row) {
            $this->Pelanggan_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('pelanggan/index_mobile'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('pelanggan/index_mobile'));
        }
    }

    function import_data()
    {
    ini_set('display_errors', 0);
    $filename = $_FILES["userfile"]["tmp_name"];
    $name     = $_FILES["userfile"]["name"];
    $size     = $_FILES["userfile"]["size"];
    $name_r   = explode('.', $name);
   
    if ($size > 0 and $name_r[1] == 'csv') {
      $file = fopen($filename, "r");
      $is_header_removed = TRUE;
      $no = 1;
      $ada_id = [];
      while (($rs = fgetcsv($file, 10000, ";")) !== FALSE) {
        $no++;
        $fcm = [
          'npwp' => $rs[0],
          'nama_customer'=>$rs[1]
        ];
        $cek_data =$this->db->query("select * from pelanggan where npwp ='{$rs[0]}' and '{$rs[1]}'");
          $insert_cust[] = [
            'kode_customer'=>$this->acak(10),   
            'npwp' => $rs[0],
            'nama_customer' => $rs[1],
            'alamat' => $rs[2],
            'telepon' => $rs[3],
            'kode_pos' => $rs[4],
            'passpor' => $rs[5],
          ];
        
        
        $no++;
      }
      fclose($file);

      if (count($ada_id) > 0) {
        $html_pesan = 'No. Mesin & KPB Ke- Sudah Ada Dalam Database : <ul>';
        foreach ($ada_id as $key => $er) {
          $html_pesan .= "<li> Line : $key";
          $html_pesan .= "<ol>";
          // send_json($er);
          $html_pesan .= "<li>Kode Barang : {$er['kode_barang']}</li>";
          $html_pesan .= "</ol>";
          $html_pesan .= "</li>";
        }
        $html_pesan .= "</ul>";
        $rsp_error = ['status' => 'error', 'tipe' => 'html', 'pesan' => $html_pesan];
      }

      $tes = [
        'ins_kpb' => isset($insert_cust) ? $insert_cust : NULL,
        'ada_nosin' => isset($ada_id) ? $ada_id : NULL
      ];
      // send_json($tes);

        $this->db->trans_begin();
        if (isset($insert_cust)) {
            $this->db->insert_batch('pelanggan', $insert_cust);
            $rsp = [
                'status' => 'sukses',
                'link' => base_url('pelanggan')
              ];
          }
          if (!$this->db->trans_status()) {
            $this->db->trans_rollback();
            $_SESSION['pesan']   = $pesan;
            $_SESSION['tipe']   = "success";
            $rsp = [
              'status' => 'sukses',
              'link' => base_url('pelanggan')
            ];
          } else {
            $this->db->trans_commit();
            $cins = 0;
            if (isset($insert_cust)) {
              $cins = count($insert_cust);
            }
            $pesan =   $cins . " Data berhasil di upload";
            if (count($ada_id) > 0) {
              $pesan .=  ", " . count($ada_id) . " No. mesin dan KPB sudah ada di dalam database";
            }
            
            $_SESSION['pesan']   = "Success Upload Data";
            $_SESSION['tipe']   = "primary";
            $rsp = [
              'status' => 'sukses',
              'link' => base_url('pelanggan')
            ];
          }
          
        
    }  
    echo json_encode($rsp); 
      
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('kode_customer', 'kode customer', 'trim|required');
	$this->form_validation->set_rules('npwp', 'npwp', 'trim|required');
	$this->form_validation->set_rules('nama_customer', 'nama customer', 'trim|required');
	$this->form_validation->set_rules('alamat', 'alamat', 'trim|required');
	$this->form_validation->set_rules('telepon', 'telepon', 'trim|required');
	$this->form_validation->set_rules('kode_pos', 'kode pos', 'trim|required');
	$this->form_validation->set_rules('passpor', 'passpor', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Pelanggan.php */
/* Location: ./application/controllers/Pelanggan.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-06-01 07:38:10 */
/* http://harviacode.com */