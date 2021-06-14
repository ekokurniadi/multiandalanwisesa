<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Katalog_product extends MY_Controller {

    // protected $access = array('Admin', 'Pimpinan','Finance');
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('Katalog_product_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'katalog_product/index.dart?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'katalog_product/index.dart?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'katalog_product/index.dart';
            $config['first_url'] = base_url() . 'katalog_product/index.dart';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Katalog_product_model->total_rows($q);
        $katalog_product = $this->Katalog_product_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'katalog_product_data' => $katalog_product,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('header');
        $this->load->view('katalog_product_list', $data);
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
                $where .= " AND (nama_produk LIKE '%$search%')";
              }
          }

        if (isset($orders)) {
            if ($orders != '') {
              $order = $orders;
              $order_column = ['','nama_produk'];
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
        $fetch = $this->db->query("SELECT * FROM katalog_product $where");
        $fetch2 = $this->db->query("SELECT * FROM katalog_product");
        foreach($fetch->result() as $rows){
            $button1= "<a href=".base_url('katalog_product/read/'.$rows->id)." class='btn btn-icon icon-left btn-light'><i class='fa fa-eye'></i></a>";
          
            $button2= "<a href=".base_url('katalog_product/update/'.$rows->id)." class='btn btn-icon icon-left btn-warning'><i class='fa fa-pencil-square-o'></i></a>";
           
            $button3 = "<a href=".base_url('katalog_product/delete/'.$rows->id)." class='btn btn-icon icon-left btn-danger' onclick='javasciprt: return confirm(\"Are You Sure ?\")''><i class='fa fa-trash'></i></a>";
            $button4 = "<a href=".base_url('image/'.$rows->brosur)." class='btn btn-icon icon-left btn-danger' target='_blank'><i class='fa fa-file-pdf-o'></i> $rows->brosur</a>";
          
            $sub_array=array();
            $sub_array[]=$index;
            $sub_array[]=$rows->nama_produk;
            $sub_array[]= $button4;
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
        $row = $this->Katalog_product_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'nama_produk' => $row->nama_produk,
		'brosur' => $row->brosur,
	    );
            $this->load->view('header');
            $this->load->view('katalog_product_read', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('katalog_product'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('katalog_product/create_action'),
	    'id' => set_value('id'),
	    'nama_produk' => set_value('nama_produk'),
	    'brosur' => set_value('brosur'),
	);

        $this->load->view('header');
        $this->load->view('katalog_product_form', $data);
        $this->load->view('footer');
    }
    
    // public function create_action() 
    // {
    //     $this->_rules();

    //     if ($this->form_validation->run() == FALSE) {
    //         $this->create();
    //     } else {
    //         $data = array(
	// 	'nama_produk' => $this->input->post('nama_produk',TRUE),
	// 	'brosur' => $this->input->post('brosur',TRUE),
	//     );

    //         $this->Katalog_product_model->insert($data);
    //         $this->session->set_flashdata('message', 'Create Record Success');
    //         redirect(site_url('katalog_product'));
    //     }
    // }

    public function create_action() 
    {
        $this->load->library('upload');
            $nmfile = "katalog".time();
            $config['upload_path']   = './image/';
            $config['overwrite']     = true;
            $config['allowed_types'] = 'gif|jpeg|png|jpg|bmp|PNG|JPEG|JPG|pdf|PDF';
            $config['file_name'] = $_FILES["brosur"]["name"];

            $this->upload->initialize($config);

            if($_FILES['brosur']['name'])
            {
                if($this->upload->do_upload('brosur'))
                {
                $gbr = $this->upload->data();
                $data = array(
                    'brosur' =>  $gbr['file_name'],
                    'nama_produk' => $this->input->post('nama_produk',TRUE),
                   
                );

                $this->Katalog_product_model->insert($data);
                $this->session->set_flashdata('message', 'Create Record Success');
                redirect(site_url('katalog_product'));
            }
        }
    }
    
    public function update($id) 
    {
        $row = $this->Katalog_product_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('katalog_product/update_action'),
		'id' => set_value('id', $row->id),
		'nama_produk' => set_value('nama_produk', $row->nama_produk),
		'brosur' => set_value('brosur', $row->brosur),
	    );
            $this->load->view('header');
            $this->load->view('katalog_product_form', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('katalog_product'));
        }
    }
    
    // public function update_action() 
    // {
    //     $this->_rules();

    //     if ($this->form_validation->run() == FALSE) {
    //         $this->update($this->input->post('id', TRUE));
    //     } else {
    //         $data = array(
	// 	'nama_produk' => $this->input->post('nama_produk',TRUE),
	// 	'brosur' => $this->input->post('brosur',TRUE),
	//     );

    //         $this->Katalog_product_model->update($this->input->post('id', TRUE), $data);
    //         $this->session->set_flashdata('message', 'Update Record Success');
    //         redirect(site_url('katalog_product'));
    //     }
    // }
    
    public function update_action() 
    {
        $this->load->library('upload');
        $nmfile = "user".time();
        $config['upload_path']   = './image/';
        $config['overwrite']     = true;
        $config['allowed_types'] = 'gif|jpeg|png|jpg|bmp|PNG|JPEG|JPG|pdf|PDF';
        $config['file_name'] = $_FILES["brosur"]["name"];

        $this->upload->initialize($config);
        
                if(!empty($_FILES['brosur']['name']))
                {  
                        unlink("./image/".$_FILES["brosur"]["name"]);
                        

                    if($_FILES['brosur']['name'])
                    {
                        if($this->upload->do_upload('brosur'))
                        {
                            $gbr = $this->upload->data();
                            $data = array(
                                'brosur' =>  $gbr['file_name'],
                                'nama_produk' => $this->input->post('nama_produk',TRUE),
                            );
                        }
                    }
                  
                    $this->Katalog_product_model->update($this->input->post('id', TRUE), $data);
                    $this->session->set_flashdata('message', 'Update Record Success');
                    redirect(site_url('katalog_product'));
                }
                    else
                        {
                            $data = array(
                                'nama_produk' => $this->input->post('nama_produk',TRUE),
                            );
                        }
                    
                        $this->Katalog_product_model->update($this->input->post('id', TRUE), $data);
                        $this->session->set_flashdata('message', 'Update Record Success');
                        redirect(site_url('katalog_product'));
    }

    public function delete($id) 
    {
        $row = $this->Katalog_product_model->get_by_id($id);

        if ($row) {
             unlink('image/'.$row->brosur);
            $this->Katalog_product_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('katalog_product'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('katalog_product'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('nama_produk', 'nama produk', 'trim|required');
	$this->form_validation->set_rules('brosur', 'brosur', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Katalog_product.php */
/* Location: ./application/controllers/Katalog_product.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-06-01 06:25:56 */
/* http://harviacode.com */