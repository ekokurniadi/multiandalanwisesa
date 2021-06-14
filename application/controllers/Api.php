<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
  class Api extends MY_Controller {
    public function login()
	{
		if ($_POST) {
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$token_fcm = $this->input->post('token');
            
			$cek = $this->db->get_where('pengguna', array('username' => $username, 'password' => $password));

			if ($cek->num_rows() == 1) {
				$data = $cek->row();

				//cek driver aktif
				if ($data->status == "0") {
						$result = array(
							'status' => "0",
							'pesan' => 'Akun kamu belum aktif, silahkan hubungi admin'
						);
						echo json_encode($result);
						exit();
				}

				// update fcm token
				$this->db->where('id', $data->id);
				$this->db->update('pengguna', array('token'=>$token_fcm,'status_online'=>'1'));
				$result = array(
                    'status'=>"1",
					'id' => $data->id,
					'nama' => $data->nama,
					'user_id' => $data->user_id,
					'alamat' => $data->alamat,
					'noHp' => $data->no_hp,
					'level' => $data->level,
					'pesan' => "Selamat datang dan selamat beraktifitas $data->nama"
				);
				echo json_encode($result);
			} else {
				$result = array(
					'status' => "0",
					'pesan' => 'Gagal, Username atau Password tidak cocok'
				);
				echo json_encode($result);
			}
		}
	}
    public function logOut(){
		if ($_GET) {
			$id = $this->input->get('id');
			$update = $this->db->query("UPDATE pengguna SET status_online='0' WHERE id='$id'");
			if ($update) {
				echo "berhasil update $id";
			}
			exit();
		}
	}

	public function all_lokasi()
	{	
			$data = $this->db->query("SELECT * from pengguna");
		$attribs=array('id','nama','latitude','longitude','alamatfromapp','photo');


	    $dom=new DOMDocument('1.0','utf-8');
	    $dom->formatOutput=true;
	    $dom->standalone=true;
	    $dom->recover=true;

	    $root=$dom->createElement('markers');
	    $dom->appendChild( $root );


	    foreach ($data->result() as $rs) {
	    	$node=$dom->createElement('marker');
	        $root->appendChild( $node );

	        foreach( $attribs as $attrib ){
	            $attr = $dom->createAttribute( $attrib );
	            $value= $dom->createTextNode( $rs->$attrib );
	            $attr->appendChild( $value );
	            $node->appendChild( $attr );
	        }
	    }

	    header("Content-Type: application/xml");
	    echo $dom->saveXML();


	}



	public function sendLocation(){
		if($_POST){
			$lat  = $this->input->post("latitude");
			$long = $this->input->post("longitude");
			$current = $this->input->post("currentloc");
			$id   = $this->input->post("id");

			$data = array(
				"latitude" => $lat,
				"longitude" => $long,
				"alamatfromapp" => $current,
			);
			if($lat != "" and $long != ""){
				$this->db->where('id',$id);
				$this->db->update('pengguna',$data);
			}
			echo json_encode(array(
				"status"=>200,
				"message"=>"Success",
			));	
		}else{
			echo json_encode(array(
				"status"=>"error",
				"message"=>"Gagal Mendapatkan lokasi",
			));	
		}
	}

	public function getKatalog(){
		$filter = isset($_POST['filter']) ? $_POST['filter'] :'';
		$where = "WHERE 1=1 ";
		if($filter== ""){
			$where.= "";
		}else{
			$where .= "AND (nama_produk LIKE '%$filter%'
							OR brosur LIKE '%$filter%')";
		}
		$where .=" ORDER BY id DESC";
		$query = $this->db->query("SELECT * FROM katalog_product $where");
		$result=array();
		if($query->num_rows() > 0){
			foreach($query->result() as $rows){
				$sub_array=array();
				$sub_array[]=$rows->id;
				$sub_array[]=$rows->nama_produk;
				$sub_array[]=$rows->brosur;
				$result[]=$sub_array;
			}
			echo json_encode(array(
				"status"=>200,
				"message"=>"Success",
				"data"=>$result
			));
		}else{
			echo json_encode(array(
				"status"=>"error",
				"message"=>"Data tidak ditemukan",
				
			));
		}
	}
	public function cek_jarak()
	{
		if ($_POST) {
			$jarak = $this->input->post('jarak');
			$id_jenis = $this->input->post('id_jenis');
			$cek = $this->db->get_where('setting_layanan', array('id_jenis'=>$id_jenis))->row();
			if ($cek->max_km > $jarak) {
				if ($jarak > $cek->standar_km) {
					$jarak = ceil($jarak);
					$sisa = $jarak - $cek->standar_km;
					$n_sisa = $sisa * $cek->per_km;
					$total_harga = $cek->standar_harga + $n_sisa;
					$result = array(
						'status' => "1",
						'pesan' => "success",
						'total_harga' => "$total_harga",
					);
					echo json_encode($result);
				} else {
					$result = array(
						'status' => "1",
						'pesan' => "success",
						'total_harga' => "$cek->standar_harga",
					);
					echo json_encode($result);
				}
				
			} else {
				$result = array(
					'status' => "0",
					'pesan' => "Jarak tidak boleh melebihi $cek->max_km KM"
				);
				echo json_encode($result);
			}
		}

	}

	public function getCurrentUser(){
		$id = $this->input->post('id');
		$data = $this->db->query("SELECT * from pengguna where id='$id'");
		$result=array();
		if($data->num_rows() > 0){
			$result=array(
				"status"=>200,
				"id"=>$data->row()->id,
				"nama"=>$data->row()->nama,
				"alamat"=>$data->row()->alamat,
				"no_hp"=>$data->row()->no_hp,
				"level"=>$data->row()->level,
				"username"=>$data->row()->username,
				"password"=>$data->row()->password,
				"foto"=>$data->row()->photo
			);
			echo json_encode($result);
		}
	}

	public function getNotif(){
		if($_POST){
			$id= $this->input->post('id');
			$deleted = $this->input->post('deleted');
			$result=array();
			$data = $this->db->query("SELECT a.*,b.nama FROM notifikasi a join pengguna b on a.user_id=b.id where a.user_id = '$id' and a.deleted ='$deleted' order by a.id DESC");
			
			$response = array();
			if($data->num_rows() <= 0){
				$result=array(
					"status"=>"0",
					"pesan"=>"Tidak ada notifikasi"
				);
				echo json_encode($result);
			}else{
				foreach($data->result() as $rows){
					$sub_array=array();
					$sub_array[]="Hallo ".$rows->nama.", ".$rows->pesan;
					$sub_array[]=$rows->status;
					$sub_array[]=formatTanggal(substr($rows->created,0,10));
					$sub_array[]=substr($rows->created,11,19);
					$sub_array[]=$rows->id;
					$response[]=$sub_array;
				}
				
				$result=array(
					"status"=>"1",
					"pesan"=>"Success",
					"values"=>$response
				);
				echo json_encode($result);
			}
		}
	}

	public function updateStatusNotif(){
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		$deleted = $this->input->post('deleted');

		$update = $this->db->query("UPDATE notifikasi set status = '$status',deleted ='$deleted' where id='$id'");
		if($update){
			echo json_encode(array(
				"status"=>200,
				"message"=>"Berhasil melakukan perubahan data."
			));
		}else{
			echo json_encode(array(
				"status"=>"error",
				"message"=>"Gagal"
			));
		}
	} 

  }  
?>