<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jadwal extends MY_Controller {

    protected $access = array('Admin', 'Pimpinan','Finance');
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('Jadwal_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'jadwal/index.dart?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'jadwal/index.dart?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'jadwal/index.dart';
            $config['first_url'] = base_url() . 'jadwal/index.dart';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Jadwal_model->total_rows($q);
        $jadwal = $this->Jadwal_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'jadwal_data' => $jadwal,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('header');
        $this->load->view('jadwal_list', $data);
        $this->load->view('footer');
    }

    public function read($id) 
    {
        $row = $this->Jadwal_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'id_sales' => $row->id_sales,
		'customer' => $row->customer,
		'tanggal' => $row->tanggal,
		'jam' => $row->jam,
		'jenis_kunjungan' => $row->jenis_kunjungan,
		'status' => $row->status,
		'catatan' => $row->catatan,
	    );
            $this->load->view('header');
            $this->load->view('jadwal_read', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jadwal'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('jadwal/create_action'),
	    'id' => set_value('id'),
	    'id_sales' => set_value('id_sales'),
	    'customer' => set_value('customer'),
	    'tanggal' => set_value('tanggal'),
	    'jam' => set_value('jam'),
	    'jenis_kunjungan' => set_value('jenis_kunjungan'),
	    'status' => set_value('status'),
	    'catatan' => set_value('catatan'),
	);

        $this->load->view('header');
        $this->load->view('jadwal_form', $data);
        $this->load->view('footer');
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'id_sales' => $this->input->post('id_sales',TRUE),
		'customer' => $this->input->post('customer',TRUE),
		'tanggal' => $this->input->post('tanggal',TRUE),
		'jam' => $this->input->post('jam',TRUE),
		'jenis_kunjungan' => $this->input->post('jenis_kunjungan',TRUE),
		'status' => $this->input->post('status',TRUE),
		'catatan' => $this->input->post('catatan',TRUE),
	    );

            $this->Jadwal_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('jadwal'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Jadwal_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('jadwal/update_action'),
		'id' => set_value('id', $row->id),
		'id_sales' => set_value('id_sales', $row->id_sales),
		'customer' => set_value('customer', $row->customer),
		'tanggal' => set_value('tanggal', $row->tanggal),
		'jam' => set_value('jam', $row->jam),
		'jenis_kunjungan' => set_value('jenis_kunjungan', $row->jenis_kunjungan),
		'status' => set_value('status', $row->status),
		'catatan' => set_value('catatan', $row->catatan),
	    );
            $this->load->view('header');
            $this->load->view('jadwal_form', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jadwal'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'id_sales' => $this->input->post('id_sales',TRUE),
		'customer' => $this->input->post('customer',TRUE),
		'tanggal' => $this->input->post('tanggal',TRUE),
		'jam' => $this->input->post('jam',TRUE),
		'jenis_kunjungan' => $this->input->post('jenis_kunjungan',TRUE),
		'status' => $this->input->post('status',TRUE),
		'catatan' => $this->input->post('catatan',TRUE),
	    );

            $this->Jadwal_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('jadwal'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Jadwal_model->get_by_id($id);

        if ($row) {
            $this->Jadwal_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('jadwal'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jadwal'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('id_sales', 'id sales', 'trim|required');
	$this->form_validation->set_rules('customer', 'customer', 'trim|required');
	$this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
	$this->form_validation->set_rules('jam', 'jam', 'trim|required');
	$this->form_validation->set_rules('jenis_kunjungan', 'jenis kunjungan', 'trim|required');
	$this->form_validation->set_rules('status', 'status', 'trim|required');
	$this->form_validation->set_rules('catatan', 'catatan', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Jadwal.php */
/* Location: ./application/controllers/Jadwal.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-06-01 09:13:30 */
/* http://harviacode.com */