<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pengguna extends MY_Controller {

    // protected $access = array('Admin', 'Pimpinan','Finance');
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('Pengguna_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->load->view('header');
        $this->load->view('pengguna_list');
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
                $where .= " AND (user_id LIKE '%$search%'
                                OR nama LIKE '%$search%'
                                OR alamat LIKE '%$search%'
                                OR no_hp LIKE '%$search%'
                                OR level LIKE '%$search%'
                               
                                )";
              }
          }
    
        if (isset($orders)) {
            if ($orders != '') {
              $order = $orders;
              $order_column = ['user_id','nama','alamat','no_hp','level'];
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
        $fetch = $this->db->query("SELECT * from pengguna $where");
        $fetch2 = $this->db->query("SELECT * from pengguna ");
        foreach($fetch->result() as $rows){
            $button1= "<a href=".base_url('pengguna/read/'.$rows->id)." class='btn btn-icon icon-left btn-light'><i class='fa fa-eye'></i></a>";
            $button2= "<a href=".base_url('pengguna/update/'.$rows->id)." class='btn btn-icon icon-left btn-warning'><i class='fa fa-pencil-square-o'></i></a>";
            $button3 = "<a href=".base_url('pengguna/delete/'.$rows->id)." class='btn btn-icon icon-left btn-danger' onclick='javasciprt: return confirm(\"Are You Sure ?\")''><i class='fa fa-trash'></i></a>";
            $span    = $rows->status == "1" ? "<button class='btn btn-icon btn-success'><span class='fa fa-check-circle'></span></button>" : "<button class='btn btn-icon btn-danger'><span class='fa fa-exclamation-triangle'></span></button>";
            $sub_array=array();
            $sub_array[]=$index;
            $sub_array[]=$rows->user_id;
            $sub_array[]=$rows->nama;
            $sub_array[]=$rows->alamat;
            $sub_array[]=$rows->no_hp;
            $sub_array[]="<img src=".base_url().'image/'.$rows->photo." class='img-fluid' width='80px'>";
            $sub_array[]=$rows->username;
            $sub_array[]=sha1($rows->password);
            $sub_array[]=$rows->level;
            $sub_array[]=$span;
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
        $row = $this->Pengguna_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'user_id' => $row->user_id,
		'nama' => $row->nama,
		'alamat' => $row->alamat,
		'no_hp' => $row->no_hp,
		'photo' => $row->photo,
		'username' => $row->username,
		'password' => $row->password,
		'level' => $row->level,
		'status' => $row->status,
	    );
            $this->load->view('header');
            $this->load->view('pengguna_read', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('pengguna'));
        }
    }

   
    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('pengguna/create_action'),
	    'id' => set_value('id'),
	    'user_id' => set_value('user_id'),
	    'nama' => set_value('nama'),
	    'alamat' => set_value('alamat'),
	    'no_hp' => set_value('no_hp'),
	    'photo' => set_value('photo'),
	    'username' => set_value('username'),
	    'password' => set_value('password'),
	    'level' => set_value('level'),
	    'status' => set_value('status'),
	);

        $this->load->view('header');
        $this->load->view('pengguna_form', $data);
        $this->load->view('footer');
    }
    

    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $this->load->library('upload');
            $nmfile = "user".time();
            $config['upload_path']   = './image/profil_user';
            $config['overwrite']     = true;
            $config['allowed_types'] = 'gif|jpeg|png|jpg|bmp|PNG|JPEG|JPG';
            $config['file_name'] = $nmfile;

            $this->upload->initialize($config);

            if($_FILES['photo']['name'])
                {
                    if($this->upload->do_upload('photo'))
                    {
                    $gbr = $this->upload->data();
                    $data = array(
                        'photo' => $gbr['file_name'],
                        'user_id' => $this->input->post('user_id',TRUE),
                        'nama' => $this->input->post('nama',TRUE),
                        'alamat' => $this->input->post('alamat',TRUE),
                        'no_hp' => $this->input->post('no_hp',TRUE),
                        'username' => $this->input->post('username',TRUE),
                        'password' => $this->input->post('password',TRUE),
                        'level' => $this->input->post('level',TRUE),
                        'status' => $this->input->post('status',TRUE),
                    );

                    $this->Pengguna_model->insert($data);
                    $this->session->set_flashdata('message', 'Create Record Success');
                    redirect(site_url('pengguna'));
                }
            }
        }
    }
    
    public function update($id) 
    {
        $row = $this->Pengguna_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('pengguna/update_action'),
		'id' => set_value('id', $row->id),
		'user_id' => set_value('user_id', $row->user_id),
		'nama' => set_value('nama', $row->nama),
		'alamat' => set_value('alamat', $row->alamat),
		'no_hp' => set_value('no_hp', $row->no_hp),
		'photo' => set_value('photo', $row->photo),
		'username' => set_value('username', $row->username),
		'password' => set_value('password', $row->password),
		'level' => set_value('level', $row->level),
		'status' => set_value('status', $row->status),
	    );
            $this->load->view('header');
            $this->load->view('pengguna_form', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('pengguna'));
        }
    }
    
    public function update_action() 
    {
        $this->load->library('upload');
        $nmfile = "user".time();
        $config['upload_path']   = './image/profil_user';
        $config['overwrite']     = true;
        $config['allowed_types'] = 'gif|jpeg|png|jpg|bmp|PNG|JPEG|JPG';
        $config['file_name'] = $nmfile;

        $this->upload->initialize($config);
        
                if(!empty($_FILES['photo']['name']))
                {  
                        unlink("./image/profil_user/".$this->input->post('photo'));

                    if($_FILES['photo']['name'])
                    {
                        if($this->upload->do_upload('photo'))
                        {
                            $gbr = $this->upload->data();
                            $data = array(
                                'photo' => $gbr['file_name'],
                                'user_id' => $this->input->post('user_id',TRUE),
                                'nama' => $this->input->post('nama',TRUE),
                                'alamat' => $this->input->post('alamat',TRUE),
                                'no_hp' => $this->input->post('no_hp',TRUE),
                                'username' => $this->input->post('username',TRUE),
                                'password' => $this->input->post('password',TRUE),
                                'level' => $this->input->post('level',TRUE),
                                'status' => $this->input->post('status',TRUE),
                            );
                        }
                    }
                  
                    $this->Pengguna_model->update($this->input->post('id', TRUE), $data);
                    $this->session->set_flashdata('message', 'Update Record Success');
                    redirect(site_url('pengguna'));
                }
                    else
                        {
                            $data = array(
                                'user_id' => $this->input->post('user_id',TRUE),
                                'nama' => $this->input->post('nama',TRUE),
                                'alamat' => $this->input->post('alamat',TRUE),
                                'no_hp' => $this->input->post('no_hp',TRUE),
                                'username' => $this->input->post('username',TRUE),
                                'password' => $this->input->post('password',TRUE),
                                'level' => $this->input->post('level',TRUE),
                                'status' => $this->input->post('status',TRUE),
                            );
                        }
                    
                        $this->Pengguna_model->update($this->input->post('id', TRUE), $data);
                        $this->session->set_flashdata('message', 'Update Record Success');
                        redirect(site_url('pengguna'));
    }
    
    public function delete($id) 
    {
        $row = $this->Pengguna_model->get_by_id($id);

        if ($row) {
            $this->Pengguna_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('pengguna'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('pengguna'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('user_id', 'user id', 'trim|required');
	$this->form_validation->set_rules('nama', 'nama', 'trim|required');
	$this->form_validation->set_rules('alamat', 'alamat', 'trim|required');
	$this->form_validation->set_rules('no_hp', 'no hp', 'trim|required');
	$this->form_validation->set_rules('photo', 'photo', 'trim|required');
	$this->form_validation->set_rules('username', 'username', 'trim|required');
	$this->form_validation->set_rules('password', 'password', 'trim|required');
	$this->form_validation->set_rules('level', 'level', 'trim|required');
	$this->form_validation->set_rules('status', 'status', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Pengguna.php */
/* Location: ./application/controllers/Pengguna.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-05-31 04:42:19 */
/* http://harviacode.com */