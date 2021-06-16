<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Claim extends MY_Controller {

    // protected $access = array('Admin', 'Pimpinan','Finance');
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('Claim_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
       
        $this->load->view('header');
        $this->load->view('claim_list');
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
            $where .= " AND (no_claim LIKE '%$search%'
                            OR tanggal_pengajuan LIKE '%$search%'
                            OR no_do LIKE '%$search%'
                            OR no_po LIKE '%$search%'
                            OR customer LIKE '%$search%'
                            OR barang LIKE '%$search%'
                            OR kuantitas LIKE '%$search%'
                            OR kondisi_barang LIKE '%$search%'
                            OR foto_barang LIKE '%$search%'
                            OR status LIKE '%$search%'
                            OR sales_id LIKE '%$search%'
                            )";
          }
      }

    if (isset($orders)) {
        if ($orders != '') {
          $order = $orders;
          $order_column = ['no_claim','tanggal_pengajuan','no_do','no_po','customer','barang','kuantitas','kondisi_barang','foto_barang','status','sales_id'];
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
    $fetch = $this->db->query("SELECT * from claim $where");
    $fetch2 = $this->db->query("SELECT * from claim ");
    foreach($fetch->result() as $rows){
        $button1= "<a href=".base_url('claim/read/'.$rows->id)." class='btn btn-icon icon-left btn-light'><i class='fa fa-eye'></i></a>";
        $button2= "<a href=".base_url('claim/update/'.$rows->id)." class='btn btn-icon icon-left btn-warning'><i class='fa fa-pencil-square-o'></i></a>";
        $button3 = "<a href=".base_url('claim/delete/'.$rows->id)." class='btn btn-icon icon-left btn-danger' onclick='javasciprt: return confirm(\"Are You Sure ?\")''><i class='fa fa-trash'></i></a>";
        $sub_array=array();
        $sub_array[]=$index;
        $sub_array[]=$rows->no_claim;
        $sub_array[]=formatTanggal($rows->tanggal_pengajuan);
        $sub_array[]=$rows->no_do;
        $sub_array[]=$rows->no_po;
        $sub_array[]=$rows->customer;
        $sub_array[]=$rows->barang;
        $sub_array[]=$rows->kuantitas;
        $sub_array[]=$rows->kondisi_barang;
        $sub_array[]="<img src=".base_url().'image/'.$rows->foto_barang." class='img-fluid' width='80px'>";
        $sub_array[]=$rows->status;
        $sub_array[]=$rows->sales_id;
        $sub_array[]=$rows->catatan;
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

    public function read($id) 
    {
        $row = $this->Claim_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'no_claim' => $row->no_claim,
		'tanggal_pengajuan' => $row->tanggal_pengajuan,
		'no_do' => $row->no_do,
		'no_po' => $row->no_po,
		'customer' => $row->customer,
		'barang' => $row->barang,
		'kuantitas' => $row->kuantitas,
		'kondisi_barang' => $row->kondisi_barang,
		'foto_barang' => $row->foto_barang,
		'status' => $row->status,
		'catatan' => $row->catatan,
		'sales_id' => $row->sales_id,
	    );
            $this->load->view('header');
            $this->load->view('claim_read', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('claim'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('claim/create_action'),
	    'id' => set_value('id'),
	    'no_claim' => $this->acak(10),
	    'tanggal_pengajuan' => set_value('tanggal_pengajuan'),
	    'no_do' => set_value('no_do'),
	    'no_po' => set_value('no_po'),
	    'customer' => set_value('customer'),
	    'barang' => set_value('barang'),
	    'kuantitas' => set_value('kuantitas'),
	    'kondisi_barang' => set_value('kondisi_barang'),
	    'foto_barang' => set_value('foto_barang'),
	    'status' => set_value('status'),
	    'catatan' => set_value('catatan'),
	    'sales_id' => set_value('sales_id'),
	);

        $this->load->view('header');
        $this->load->view('claim_form', $data);
        $this->load->view('footer');
    }
    
    // public function create_action() 
    // {
    //     $this->_rules();

    //     if ($this->form_validation->run() == FALSE) {
    //         $this->create();
    //     } else {
    //         $data = array(
    //             'foto_barang' => $this->input->post('foto_barang',TRUE),
	// 	'no_claim' => $this->input->post('no_claim',TRUE),
	// 	'tanggal_pengajuan' => $this->input->post('tanggal_pengajuan',TRUE),
	// 	'no_do' => $this->input->post('no_do',TRUE),
	// 	'no_po' => $this->input->post('no_po',TRUE),
	// 	'customer' => $this->input->post('customer',TRUE),
	// 	'barang' => $this->input->post('barang',TRUE),
	// 	'kuantitas' => $this->input->post('kuantitas',TRUE),
	// 	'kondisi_barang' => $this->input->post('kondisi_barang',TRUE),
	// 	'status' => $this->input->post('status',TRUE),
	// 	'catatan' => $this->input->post('catatan',TRUE),
	// 	'sales_id' => $this->input->post('sales_id',TRUE),
	//     );

    //         $this->Claim_model->insert($data);
    //         $this->session->set_flashdata('message', 'Create Record Success');
    //         redirect(site_url('claim'));
    //     }
    // }

    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $this->load->library('upload');
            $nmfile = "user".time();
            $config['upload_path']   = './image/';
            $config['overwrite']     = true;
            $config['allowed_types'] = 'gif|jpeg|png|jpg|bmp|PNG|JPEG|JPG';
            $config['file_name'] = $nmfile;

            $this->upload->initialize($config);

            if($_FILES['foto_barang']['name'])
                {
                    if($this->upload->do_upload('foto_barang'))
                    {
                    $gbr = $this->upload->data();
                    $data = array(
                        'foto_barang' => $gbr['file_name'],
                        'no_claim' => $this->input->post('no_claim',TRUE),
                        'tanggal_pengajuan' => $this->input->post('tanggal_pengajuan',TRUE),
                        'no_do' => $this->input->post('no_do',TRUE),
                        'no_po' => $this->input->post('no_po',TRUE),
                        'customer' => $this->input->post('customer',TRUE),
                        'barang' => $this->input->post('barang',TRUE),
                        'kuantitas' => $this->input->post('kuantitas',TRUE),
                        'kondisi_barang' => $this->input->post('kondisi_barang',TRUE),
                        'status' => $this->input->post('status',TRUE),
                        'catatan' => $this->input->post('catatan',TRUE),
                        'sales_id' => $this->input->post('sales_id',TRUE),
                    );

                    $this->Claim_model->insert($data);
                    $this->session->set_flashdata('message', 'Create Record Success');
                    redirect(site_url('claim'));
                }
            }
        }
    }
    
    public function update($id) 
    {
        $row = $this->Claim_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('claim/update_action'),
		'id' => set_value('id', $row->id),
		'no_claim' => set_value('no_claim', $row->no_claim),
		'tanggal_pengajuan' => set_value('tanggal_pengajuan', $row->tanggal_pengajuan),
		'no_do' => set_value('no_do', $row->no_do),
		'no_po' => set_value('no_po', $row->no_po),
		'customer' => set_value('customer', $row->customer),
		'barang' => set_value('barang', $row->barang),
		'kuantitas' => set_value('kuantitas', $row->kuantitas),
		'kondisi_barang' => set_value('kondisi_barang', $row->kondisi_barang),
		'foto_barang' => set_value('foto_barang', $row->foto_barang),
		'status' => set_value('status', $row->status),
		'catatan' => set_value('catatan', $row->catatan),
		'sales_id' => set_value('sales_id', $row->sales_id),
	    );
            $this->load->view('header');
            $this->load->view('claim_form', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('claim'));
        }
    }
    
    // public function update_action() 
    // {
    //     $this->_rules();

    //     if ($this->form_validation->run() == FALSE) {
    //         $this->update($this->input->post('id', TRUE));
    //     } else {
    //         $data = array(
	// 	'no_claim' => $this->input->post('no_claim',TRUE),
	// 	'tanggal_pengajuan' => $this->input->post('tanggal_pengajuan',TRUE),
	// 	'no_do' => $this->input->post('no_do',TRUE),
	// 	'no_po' => $this->input->post('no_po',TRUE),
	// 	'customer' => $this->input->post('customer',TRUE),
	// 	'barang' => $this->input->post('barang',TRUE),
	// 	'kuantitas' => $this->input->post('kuantitas',TRUE),
	// 	'kondisi_barang' => $this->input->post('kondisi_barang',TRUE),
	// 	'foto_barang' => $this->input->post('foto_barang',TRUE),
	// 	'status' => $this->input->post('status',TRUE),
	// 	'catatan' => $this->input->post('catatan',TRUE),
	// 	'sales_id' => $this->input->post('sales_id',TRUE),
	//     );

    //         $this->Claim_model->update($this->input->post('id', TRUE), $data);
    //         $this->session->set_flashdata('message', 'Update Record Success');
    //         redirect(site_url('claim'));
    //     }
    // }
    public function update_action() 
    {
        $this->load->library('upload');
        $nmfile = "user".time();
        $config['upload_path']   = './image/';
        $config['overwrite']     = true;
        $config['allowed_types'] = 'gif|jpeg|png|jpg|bmp|PNG|JPEG|JPG';
        $config['file_name'] = $nmfile;

        $this->upload->initialize($config);
        
                if(!empty($_FILES['foto_barang']['name']))
                {  
                        unlink("./image//".$this->input->post('foto_barang'));

                    if($_FILES['foto_barang']['name'])
                    {
                        if($this->upload->do_upload('foto_barang'))
                        {
                            $gbr = $this->upload->data();
                            $data = array(
                                'foto_barang' => $gbr['file_name'],
                        'no_claim' => $this->input->post('no_claim',TRUE),
                        'tanggal_pengajuan' => $this->input->post('tanggal_pengajuan',TRUE),
                        'no_do' => $this->input->post('no_do',TRUE),
                        'no_po' => $this->input->post('no_po',TRUE),
                        'customer' => $this->input->post('customer',TRUE),
                        'barang' => $this->input->post('barang',TRUE),
                        'kuantitas' => $this->input->post('kuantitas',TRUE),
                        'kondisi_barang' => $this->input->post('kondisi_barang',TRUE),
                        'status' => $this->input->post('status',TRUE),
                        'catatan' => $this->input->post('catatan',TRUE),
                        'sales_id' => $this->input->post('sales_id',TRUE),
                            );
                        }
                    }
                  
                    
                    $this->Claim_model->update($this->input->post('id', TRUE), $data);
                    $this->session->set_flashdata('message', 'Update Record Success');
                    redirect(site_url('claim'));
                }
                    else
                        {
                            $data = array(
                               
                                'no_claim' => $this->input->post('no_claim',TRUE),
                                'tanggal_pengajuan' => $this->input->post('tanggal_pengajuan',TRUE),
                                'no_do' => $this->input->post('no_do',TRUE),
                                'no_po' => $this->input->post('no_po',TRUE),
                                'customer' => $this->input->post('customer',TRUE),
                                'barang' => $this->input->post('barang',TRUE),
                                'kuantitas' => $this->input->post('kuantitas',TRUE),
                                'kondisi_barang' => $this->input->post('kondisi_barang',TRUE),
                                'status' => $this->input->post('status',TRUE),
                                'catatan' => $this->input->post('catatan',TRUE),
                                'sales_id' => $this->input->post('sales_id',TRUE),
                            );
                        }
                    
                        
                    $this->Claim_model->update($this->input->post('id', TRUE), $data);
                    $this->session->set_flashdata('message', 'Update Record Success');
                    redirect(site_url('claim'));
    }

    public function delete($id) 
    {
        $row = $this->Claim_model->get_by_id($id);

        if ($row) {
            $this->Claim_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('claim'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('claim'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('no_claim', 'no claim', 'trim|required');
	$this->form_validation->set_rules('tanggal_pengajuan', 'tanggal pengajuan', 'trim|required');
	$this->form_validation->set_rules('no_do', 'no do', 'trim|required');
	$this->form_validation->set_rules('no_po', 'no po', 'trim|required');
	$this->form_validation->set_rules('customer', 'customer', 'trim|required');
	$this->form_validation->set_rules('barang', 'barang', 'trim|required');
	$this->form_validation->set_rules('kuantitas', 'kuantitas', 'trim|required');
	$this->form_validation->set_rules('kondisi_barang', 'kondisi barang', 'trim|required');
	$this->form_validation->set_rules('foto_barang', 'foto barang', '');
	$this->form_validation->set_rules('status', 'status', 'trim|required');
	$this->form_validation->set_rules('sales_id', 'sales id', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Claim.php */
/* Location: ./application/controllers/Claim.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-06-16 05:30:40 */
/* http://harviacode.com */