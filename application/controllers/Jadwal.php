<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jadwal extends MY_Controller
{

    // protected $access = array('Admin', 'Pimpinan','Finance');

    function __construct()
    {
        parent::__construct();
        $this->load->model('Jadwal_model');
        $this->load->model('Notif_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['session'] = $_GET['session'];
        $_SESSION['id'] = $_GET['session'];
        $this->load->view('header2');
        $this->load->view('jadwal_list', $data);
        $this->load->view('footer2');
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


        $where = "WHERE 1=1 AND id_sales='$session'";
        $where2 = "WHERE 1=1 AND id_sales='$session'";
        // $searchingColumn;
        $result = array();
        if (isset($search)) {
            if ($search != '') {
                $searchingColumn = $search;
                $where .= " AND (customer LIKE '%$search%'
                            )";
                $where2 .= " AND (customer LIKE '%$search%'
                            )";
            }
        }

        if (isset($orders)) {
            if ($orders != '') {
                $order = $orders;
                $order_column = ['id_sales', 'customer'];
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
        $fetch = $this->db->query("SELECT * from jadwal $where");
        $fetch2 = $this->db->query("SELECT * from jadwal $where2");
        foreach ($fetch->result() as $rows) {
            $button1 = "<a href=" . base_url('jadwal/read/' . $rows->id) . " class='btn btn-icon icon-left btn-light'><i class='fa fa-eye'></i></a>";
            $button2 = "<a href=" . base_url('jadwal/update/' . $rows->id) . " class='btn btn-icon icon-left btn-warning'><i class='fa fa-pencil-square-o'></i></a>";
            $button3 = "<a href=" . base_url('jadwal/delete/' . $rows->id) . " class='btn btn-icon icon-left btn-danger' onclick='javasciprt: return confirm(\"Are You Sure ?\")''><i class='fa fa-trash'></i></a>";
            $sub_array = array();
            $sub_array[] = $index;
            $sub_array[] = $rows->customer;
            $sub_array[] = $button1 . " " . $button2 . " " . $button3;
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
        $row = $this->Jadwal_model->get_by_id($id);
        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('jadwal/update_action'),
                'mode' => "read",
                'id' => set_value('id', $row->id),
                'id_jadwal' => set_value('id_jadwal', $row->id_jadwal),
                'id_sales' => set_value('id_sales', $row->id_sales),
                'customer' => set_value('customer', $row->customer),
            );
            $this->load->view('header2');
            $this->load->view('jadwal_form', $data);
            $this->load->view('footer2');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jadwal'));
        }
    }

    public function kode()
    {
        date_default_timezone_set('Asia/Jakarta');
        $day = date('d');
        $month = date('m');
        $year = substr(date('Y'), -2);
        $get_data = $this->db
            ->from('jadwal')
            ->limit(1)
            ->order_by('id', 'desc')
            ->get();

        if ($get_data->num_rows() > 0) {
            $row        = $get_data->row();
            $kode_jasa = substr($row->id_jadwal, -6);
            $new_kode = $day . $month . "SPA" . $year . sprintf("%'.06d", $kode_jasa + 1);
        } else {
            $new_kode   = $day . $month . "SPA" . $year . "000001";
        }
        return strtoupper($new_kode);
    }

    public function create()
    {

        $data = array(
            'button' => 'Create',
            'action' => site_url('jadwal/create_action'),
            'mode' => "create",
            'id' => set_value('id'),
            'id_jadwal' => $this->kode(),
            'id_sales' => set_value('id_sales', $_SESSION['id']),
            'customer' => set_value('customer'),
        );

        $this->load->view('header2');
        $this->load->view('jadwal_form', $data);
        $this->load->view('footer2');
    }

    public function create_action()
    {
        date_default_timezone_set('Asia/Bangkok');
        $header = [
            'id_jadwal' => $_POST['id_jadwal'],
            'id_sales' => $_POST['id_sales'],
            'customer' => $_POST['customer'],
            'status' => "New",

        ];
        $detail = $_POST['detail'];
        foreach ($detail as $dj) {
            $ins_[] = [
                'id_jadwal' => $_POST['id_jadwal'],
                'tanggal' => $dj['tanggal'],
                'jam' => $dj['jam'],
                'status' => "New",
                'jenis_kunjungan' => $dj['jenis_kunjungan'],
                'perjalanan_dinas' => $dj['perjalanan_dinas'],
                'hasil' => $dj['hasil'] == "" ? "" : $dj['hasil'],
            ];
        }

        $this->db->trans_begin();
        $cek = $this->db->get_where('jadwal', array('id_jadwal' => $_POST['id_jadwal']));
        if ($cek->num_rows() <= 0) {
            $this->db->insert('jadwal', $header);
        }
        $this->db->where('id_jadwal', $_POST['id_jadwal']);
        $this->db->delete('jadwal_detail');
        $this->db->insert_batch('jadwal_detail', $ins_);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                'status' => "ERROR",
                "pesan" => "Terjadi kesalahan",
            ];
        } else {
            $this->db->trans_commit();
            $sales = $this->db->get_where('pengguna', array('id' => $_POST['id_sales']));
            $title = "Notifikasi Jadwal";
            $body = "" . $sales->row()->nama . " sudah membuat jadwal kunjungan";
            $screen = "list_trx";
            $server_key = get_setting('server_fcm_app');
            $owner = $this->db->get_where('pengguna', array('level' => 'Owner'));
            foreach ($owner->result() as $rows) {
                $this->Notif_model->send_notif($server_key, $rows->token, $title, $body, $screen);
                $insert_notif = array(
                    "user_id" => $rows->id,
                    "pesan" => $body,
                    "status" => "0",
                    "created" => date('Y-m-d H:i:s'),
                    "deleted" => "0"
                );
                $ins = $this->db->insert("notifikasi", $insert_notif);
            }
            $response = [
                'status' => "sukses",
                'link' => base_url('jadwal?session=' . $_POST['id_sales'])
            ];
            $this->session->set_flashdata('message', 'Create Record Success');
        }
        echo json_encode($response);
    }

    public function update($id)
    {
        $row = $this->Jadwal_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('jadwal/update_action'),
                'mode' => "edit",
                'id' => set_value('id', $row->id),
                'id_jadwal' => set_value('id_jadwal', $row->id_jadwal),
                'id_sales' => set_value('id_sales', $row->id_sales),
                'customer' => set_value('customer', $row->customer),
            );
            $this->load->view('header2');
            $this->load->view('jadwal_form', $data);
            $this->load->view('footer2');
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
                'id_sales' => $this->input->post('id_sales', TRUE),
                'customer' => $this->input->post('customer', TRUE),
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

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}

/* End of file Jadwal.php */
/* Location: ./application/controllers/Jadwal.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-08-06 17:48:45 */
/* http://harviacode.com */