<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pengiriman_barang_model extends CI_Model
{

    public $table = 'pengiriman_barang';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get all
    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    // get data by id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }
    
    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id', $q);
	$this->db->or_like('no_do', $q);
	$this->db->or_like('no_po', $q);
	$this->db->or_like('customer', $q);
	$this->db->or_like('tanggal_pengiriman', $q);
	$this->db->or_like('waktu_pengiriman', $q);
	$this->db->or_like('status', $q);
	$this->db->or_like('jenis_pengiriman', $q);
	$this->db->or_like('driver', $q);
	$this->db->or_like('penerima', $q);
	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
	$this->db->or_like('no_do', $q);
	$this->db->or_like('no_po', $q);
	$this->db->or_like('customer', $q);
	$this->db->or_like('tanggal_pengiriman', $q);
	$this->db->or_like('waktu_pengiriman', $q);
	$this->db->or_like('status', $q);
	$this->db->or_like('jenis_pengiriman', $q);
	$this->db->or_like('driver', $q);
	$this->db->or_like('penerima', $q);
	$this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    // update data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    // delete data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

}

/* End of file Pengiriman_barang_model.php */
/* Location: ./application/models/Pengiriman_barang_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-06-08 10:29:31 */
/* http://harviacode.com */