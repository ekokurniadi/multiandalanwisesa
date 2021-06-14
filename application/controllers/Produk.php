<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Produk extends MY_Controller {

    // protected $access = array('Admin', 'Pimpinan','Finance');
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('Produk_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'produk/index.dart?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'produk/index.dart?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'produk/index.dart';
            $config['first_url'] = base_url() . 'produk/index.dart';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Produk_model->total_rows($q);
        $produk = $this->Produk_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'produk_data' => $produk,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('header');
        $this->load->view('produk_list', $data);
        $this->load->view('footer');
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
                $where .= " AND (kode_barang LIKE '%$search%'
                                OR nama_barang LIKE '%$search%'
                                OR harga_satuan LIKE '%$search'
                                )";
              }
          }

        if (isset($orders)) {
            if ($orders != '') {
              $order = $orders;
              $order_column = ['','kode_barang','nama_barang','harga_satuan'];
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
        $fetch = $this->db->query("SELECT * FROM produk $where");
        $fetch2 = $this->db->query("SELECT * FROM produk");
        foreach($fetch->result() as $rows){
            $button1= "<a href=".base_url('produk/read/'.$rows->id)." class='btn btn-icon icon-left btn-light'><i class='fa fa-eye'></i></a>";
          
            $button2= "<a href=".base_url('produk/update/'.$rows->id)." class='btn btn-icon icon-left btn-warning'><i class='fa fa-pencil-square-o'></i></a>";
           
            $button3 = "<a href=".base_url('produk/delete/'.$rows->id)." class='btn btn-icon icon-left btn-danger' onclick='javasciprt: return confirm(\"Are You Sure ?\")''><i class='fa fa-trash'></i></a>";
          
            $sub_array=array();
            $sub_array[]=$index;
            $sub_array[]=$rows->kode_barang;
            $sub_array[]=$rows->nama_barang;
            $sub_array[]=number_format($rows->harga_satuan,0,'.',',');
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
              $where .= " AND (kode_barang LIKE '%$search%'
                              OR nama_barang LIKE '%$search%'
                              OR harga_satuan LIKE '%$search'
                              )";
            }
        }

      if (isset($orders)) {
          if ($orders != '') {
            $order = $orders;
            $order_column = ['','kode_barang','nama_barang','harga_satuan'];
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
      $fetch = $this->db->query("SELECT * FROM produk $where");
      $fetch2 = $this->db->query("SELECT * FROM produk");
      foreach($fetch->result() as $rows){
          $button1= "<a href=".base_url('produk/read/'.$rows->id)." class='btn btn-icon icon-left btn-light'><i class='fa fa-eye'></i></a>";
        
          $button2= "<a href=".base_url('produk/update/'.$rows->id)." class='btn btn-icon icon-left btn-warning'><i class='fa fa-pencil-square-o'></i></a>";
         
          $button3 = "<a href=".base_url('produk/delete/'.$rows->id)." class='btn btn-icon icon-left btn-danger' onclick='javasciprt: return confirm(\"Are You Sure ?\")''><i class='fa fa-trash'></i></a>";
        
          $sub_array=array();
          $sub_array[]=$index;
          $sub_array[]=$rows->kode_barang;
          $sub_array[]=$rows->nama_barang;
          $sub_array[]=number_format($rows->harga_satuan,0,'.',',');
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

    public function index_mobile(){
      $this->load->view('header2');
      $this->load->view('produk_list2');
      $this->load->view('footer2');
    }

    public function upload_data(){
        $this->load->view('header');
        $this->load->view('import_produk');
        $this->load->view('footer');
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
      $is_header_removed = FALSE;
      $no = 1;
      $ada_id = [];
      while (($rs = fgetcsv($file, 10000, ";")) !== FALSE) {
        $no++;
        $fcm = [
          'kode_barang' => $rs[0],
        ];
        $cek_data =$this->db->query("select * from produk where kode_barang ='{$rs[0]}'");

        if ($cek_data->num_rows() > 0) {
          $cek_data2 = $cek_data->row();
          $ada_id[$no] = [
            'kode_barang' => $cek_data2->kode_barang,
          ];
          $update_cust[]=[
            'kode_barang' => $rs[0],
            'nama_barang' => $rs[1],
            'harga_satuan' => $rs[2],
          ];
        } else {
          $insert_cust[] = [   
            'kode_barang' => $rs[0],
            'nama_barang' => $rs[1],
            'harga_satuan' => $rs[2],
          ];
        
        }
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
      if($cek_data->num_rows() > 0){
        if (isset($update_cust)) {
            $this->db->update_batch('produk', $update_cust,'kode_barang');
            $rsp = [
                'status' => 'sukses',
                'link' => base_url('produk')
              ];
          }
          if (!$this->db->trans_status()) {
            $this->db->trans_rollback();
           
            $rsp = [
              'status' => 'sukses',
              'link' => base_url('produk')
            ];
          } else {
            $this->db->trans_commit();
            $cins = 0;
            if (isset($update_cust)) {
              $cins = count($update_cust);
            }
            $pesan =   $cins . " Data berhasil di upload";
            if (count($ada_id) > 0) {
              $pesan .=  ", " . count($ada_id) . " Kode Barang sudah ada di dalam database, dan berhasil di update";
            }
            $this->session->set_flashdata('Success', 'data Berhasil di Upload');
            $_SESSION['pesan']   = $pesan;
            $_SESSION['tipe']   = "warning";
            $rsp = [
              'status' => 'sukses',
              'link' => base_url('produk')
            ];
          }
        
      }else{
        if (isset($insert_cust)) {
            $this->db->insert_batch('produk', $insert_cust);
            $rsp = [
                'status' => 'sukses',
                'link' => base_url('produk')
              ];
          }
          if (!$this->db->trans_status()) {
            $this->db->trans_rollback();
            $_SESSION['pesan']   = $pesan;
            $_SESSION['tipe']   = "success";
            $rsp = [
              'status' => 'sukses',
              'link' => base_url('produk')
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
              'link' => base_url('produk')
            ];
          }
          
        }
    }  
    echo json_encode($rsp); 
      
    }

    public function read($id) 
    {
        $row = $this->Produk_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'kode_barang' => $row->kode_barang,
		'nama_barang' => $row->nama_barang,
		'harga_satuan' => $row->harga_satuan,
	    );
            $this->load->view('header');
            $this->load->view('produk_read', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('produk'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('produk/create_action'),
	    'id' => set_value('id'),
	    'kode_barang' => set_value('kode_barang'),
	    'nama_barang' => set_value('nama_barang'),
	    'harga_satuan' => set_value('harga_satuan'),
	);

        $this->load->view('header');
        $this->load->view('produk_form', $data);
        $this->load->view('footer');
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'kode_barang' => $this->input->post('kode_barang',TRUE),
		'nama_barang' => $this->input->post('nama_barang',TRUE),
		'harga_satuan' => $this->input->post('harga_satuan',TRUE),
	    );

            $this->Produk_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('produk'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Produk_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('produk/update_action'),
		'id' => set_value('id', $row->id),
		'kode_barang' => set_value('kode_barang', $row->kode_barang),
		'nama_barang' => set_value('nama_barang', $row->nama_barang),
		'harga_satuan' => set_value('harga_satuan', $row->harga_satuan),
	    );
            $this->load->view('header');
            $this->load->view('produk_form', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('produk'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'kode_barang' => $this->input->post('kode_barang',TRUE),
		'nama_barang' => $this->input->post('nama_barang',TRUE),
		'harga_satuan' => $this->input->post('harga_satuan',TRUE),
	    );

            $this->Produk_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('produk'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Produk_model->get_by_id($id);

        if ($row) {
            $this->Produk_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('produk'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('produk'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('kode_barang', 'kode barang', 'trim|required');
	$this->form_validation->set_rules('nama_barang', 'nama barang', 'trim|required');
	$this->form_validation->set_rules('harga_satuan', 'harga satuan', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Produk.php */
/* Location: ./application/controllers/Produk.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-06-01 06:53:16 */
/* http://harviacode.com */