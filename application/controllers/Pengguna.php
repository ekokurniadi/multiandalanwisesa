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
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'pengguna/index.dart?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'pengguna/index.dart?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'pengguna/index.dart';
            $config['first_url'] = base_url() . 'pengguna/index.dart';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Pengguna_model->total_rows($q);
        $pengguna = $this->Pengguna_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'pengguna_data' => $pengguna,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('header');
        $this->load->view('pengguna_list', $data);
        $this->load->view('footer');
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
            $data = array(
		'user_id' => $this->input->post('user_id',TRUE),
		'nama' => $this->input->post('nama',TRUE),
		'alamat' => $this->input->post('alamat',TRUE),
		'no_hp' => $this->input->post('no_hp',TRUE),
		'photo' => $this->input->post('photo',TRUE),
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
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'user_id' => $this->input->post('user_id',TRUE),
		'nama' => $this->input->post('nama',TRUE),
		'alamat' => $this->input->post('alamat',TRUE),
		'no_hp' => $this->input->post('no_hp',TRUE),
		'photo' => $this->input->post('photo',TRUE),
		'username' => $this->input->post('username',TRUE),
		'password' => $this->input->post('password',TRUE),
		'level' => $this->input->post('level',TRUE),
		'status' => $this->input->post('status',TRUE),
	    );

            $this->Pengguna_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('pengguna'));
        }
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