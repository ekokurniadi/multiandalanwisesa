<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Prospek extends MY_Controller
{

    // protected $access = array('Admin', 'Pimpinan', 'Finance');

    function __construct()
    {
        parent::__construct();
        $this->load->model('Prospek_model');
        $this->load->model('Notif_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['session'] = $_GET['session'];
        $_SESSION['id'] = $_GET['session'];
        $this->load->view('header2');
        $this->load->view('prospek_list', $data);
        $this->load->view('footer2');
    }

    function acak($panjang)
    {
        $karakter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';
        $string = '';
        for ($i = 0; $i < $panjang; $i++) {
            $pos = rand(0, strlen($karakter) - 1);
            $string .= $karakter{
                $pos};
        }
        return $string;
    }

    public function fetch_data()
    {
        $starts       = $this->input->post("start");
        $length       = $this->input->post("length");
        $LIMIT        = "LIMIT $starts, $length ";
        $draw         = $this->input->post("draw");
        $search       = $this->input->post("search")["value"];
        $orders       = isset($_POST["order"]) ? $_POST["order"] : '';
        $session      = $_POST['session'];

        $where = "WHERE 1=1 and sales_id='$session' ";
        $where2 = "WHERE 1=1 and sales_id='$session' ";
        // $searchingColumn;
        $result = array();
        if (isset($search)) {
            if ($search != '') {
                $searchingColumn = $search;
                $where .= " AND (kode_prospek LIKE '%$search%'
                            OR tanggal LIKE '%$search%'
                            OR customer LIKE '%$search%'
                           
                            )";
                $where2 .= " AND (kode_prospek LIKE '%$search%'
                            OR tanggal LIKE '%$search%'
                            OR customer LIKE '%$search%'
                           
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
                $where2 .= " ORDER BY $order_clm $order_by ";
            } else {
                $where .= " ORDER BY id ASC ";
                $where2 .= " ORDER BY id ASC ";
            }
        } else {
            $where .= " ORDER BY id ASC ";
            $where2 .= " ORDER BY id ASC ";
        }
        if (isset($LIMIT)) {
            if ($LIMIT != '') {
                $where .= ' ' . $LIMIT;
                $where2 .= ' ' . $LIMIT;
            }
        }
        $index = 1;
        $button = "";
        $fetch = $this->db->query("SELECT * from prospek $where");
        $fetch2 = $this->db->query("SELECT * from prospek $where2");
        foreach ($fetch->result() as $rows) {
            $button1 = "<a href=" . base_url('prospek/read/' . $rows->id) . " class='btn btn-icon icon-left btn-info'><i class='fa fa-eye'></i> Lihat</a>";
            $button2 = "<a href=" . base_url('prospek/update/' . $rows->id) . " class='btn btn-icon icon-left btn-warning'><i class='fa fa-pencil-square-o'></i> Edit</a>";
            // $button3 = "<a href=" . base_url('prospek/delete/' . $rows->id) . " class='btn btn-icon icon-left btn-danger' onclick='javasciprt: return confirm(\"Are You Sure ?\")''><i class='fa fa-trash'></i> Hapus</a>";
            $sub_array = array();
            $button =$button1." ".$button2;
            $sub_array[] = $index;
            $sub_array[] = "<div class='row' style='text-align:left  !important;font-size:10pt'>
                <div class='col-md-12'>
                    <i class='fa fa-book'></i> Kode Prospek : " . $rows->kode_prospek . "
                </div>
                <div class='col-md-12'>
                    <i class='fa fa-calendar'></i> Tanggal Kunjungan : " . $rows->tanggal . "
                </div>
                <div class='col-md-12'>
                    <i class='fa fa-clock'></i> Jam Kunjungan : " . $rows->jam . "
                </div>
                <div class='col-md-12'>
                    <i class='fa fa-users'></i> Customer : " . $rows->customer . "
                </div>
                <div class='col-md-12'>
                    <i class='fa fa-info-circle'></i> Status : <span class='badge badge-danger'>" . $rows->status . "</span>
                </div>
                <br>
                <div class='col-md-12 mt-2' >
                    ".$button."
                </div>
                
            </div>";
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
        $row = $this->Prospek_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'kode_prospek' => $row->kode_prospek,
                'tanggal' => $row->tanggal,
                'jam' => $row->jam,
                'customer' => $row->customer,
                'status' => $row->status,
                'catatan' => $row->catatan,
            );
            $this->load->view('header2');
            $this->load->view('prospek_read', $data);
            $this->load->view('footer2');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('prospek'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('prospek/create_action'),
            'id' => set_value('id'),
            'kode_prospek' => set_value('kode_prospek', $this->acak(10)),
            'tanggal' => set_value('tanggal'),
            'jam' => set_value('jam'),
            'customer' => set_value('customer'),
            'status' => set_value('status'),
            'catatan' => set_value('catatan'),
            'id_sales' => set_value('id_sales', $_SESSION['id']),
        );

        $this->load->view('header2');
        $this->load->view('prospek_form', $data);
        $this->load->view('footer2');
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'kode_prospek' => $this->input->post('kode_prospek', TRUE),
                'tanggal' => $this->input->post('tanggal', TRUE),
                'jam' => $this->input->post('jam', TRUE),
                'customer' => $this->input->post('customer', TRUE),
                'status' => $this->input->post('status', TRUE),
                'catatan' => $this->input->post('catatan', TRUE),
                'sales_id' => $this->input->post('id_sales', TRUE),
            );

            $this->Prospek_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('prospek?session='.$this->input->post('id_sales')));
        }
    }

    public function update($id)
    {
        $row = $this->Prospek_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('prospek/update_action'),
                'id' => set_value('id', $row->id),
                'kode_prospek' => set_value('kode_prospek', $row->kode_prospek),
                'tanggal' => set_value('tanggal', $row->tanggal),
                'jam' => set_value('jam', $row->jam),
                'customer' => set_value('customer', $row->customer),
                'status' => set_value('status', $row->status),
                'catatan' => set_value('catatan', $row->catatan),
                'id_sales' => set_value('id_sales', $row->sales_id),
            );
            $this->load->view('header2');
            $this->load->view('prospek_form', $data);
            $this->load->view('footer2');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('prospek'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'kode_prospek' => $this->input->post('kode_prospek', TRUE),
                'tanggal' => $this->input->post('tanggal', TRUE),
                'jam' => $this->input->post('jam', TRUE),
                'customer' => $this->input->post('customer', TRUE),
                'status' => $this->input->post('status', TRUE),
                'catatan' => $this->input->post('catatan', TRUE),
                'sales_id' => $this->input->post('id_sales', TRUE),
            );

            $this->Prospek_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('prospek?session='.$this->input->post('id_sales')));
        }
    }

    public function delete($id)
    {
        $row = $this->Prospek_model->get_by_id($id);

        if ($row) {
            $this->Prospek_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('prospek'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('prospek'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('kode_prospek', 'kode prospek', 'trim|required');
        $this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
        $this->form_validation->set_rules('jam', 'jam', 'trim|required');
        $this->form_validation->set_rules('customer', 'customer', 'trim|required');
        $this->form_validation->set_rules('status', 'status', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}

/* End of file Prospek.php */
/* Location: ./application/controllers/Prospek.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-10-01 21:44:50 */
/* http://harviacode.com */