<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Dashboard extends MY_Controller {

    // protected $access=array('Admin');
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }
    
	public function index()
	{	
        $data=array(
            'user'=>$this->db->get('pengguna'),  
        );
       
        $this->load->view('header');
        $this->load->view('index',$data);
        $this->load->view('footer');
    }  

    // public function cek_jadwal(){
    //     date_default_timezone_set('Asia/Jakarta');
    //     $tanggal_sekarang = date('Y-m-d');
    //     $jadwal = $this->db->query("SELECT * from jadwal where status ='0' and tanggal <= $date");
    //     if($jadwal)
    // }
    
}
?>