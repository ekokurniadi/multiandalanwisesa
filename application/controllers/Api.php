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
	public function fetch_data(){
        $starts       = $this->input->post("start");
        $length       = $this->input->post("length");
        $LIMIT        = "LIMIT  $starts, $length ";
        $draw         = $this->input->post("draw");
        $search       = $this->input->post('searching');
        $orders       = isset($_POST['order']) ? $_POST['order'] : ''; 
        
        $where ="WHERE 1=1 ";
        $searchingColumn;
        $result=array();
        if (isset($search)) {
          if ($search != '') {
             $searchingColumn = $search;
                $where .= " AND (kode_customer LIKE '%$search%'
                                OR npwp LIKE '%$search%'
                                OR nama_customer LIKE '%$search%'
                                OR alamat LIKE '%$search%'
                                OR telepon LIKE '%$search%'
                                OR kode_pos LIKE '%$search%'
                                OR passpor LIKE '%$search%'
                                )";
              }
          }

        if (isset($orders)) {
            if ($orders != '') {
              $order = $orders;
              $order_column = ['','kode_customer','npwp','nama_customer','alamat','telepon','kode_pos','passpor'];
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
        $fetch = $this->db->query("SELECT * FROM pelanggan $where");
        $fetch2 = $this->db->query("SELECT * FROM pelanggan");
        foreach($fetch->result() as $rows){
            $sub_array=array();
            $sub_array[]=$index;
            $sub_array[]=$rows->kode_customer;
            $sub_array[]=$rows->npwp;
            $sub_array[]=$rows->nama_customer;
            $result[]      = $sub_array;
			$index++;
        }
        $output = array(
        //   "draw"            =>     intval($this->input->post("draw")),
        //   "recordsFiltered" =>     $fetch2->num_rows(),
          "data"            =>     $result,
         
        );
        echo json_encode($output);

    }

	public function fetch_claim(){
		$starts       = $this->input->post("start");
		$length       = $this->input->post("length");
		$LIMIT        = "LIMIT $starts, $length ";
		$draw         = $this->input->post("draw");
		$search       = $this->input->post("search");
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
			
			$sub_array=array();
			$sub_array[]=$index;
			$sub_array[]=$rows->no_claim;
			$sub_array[]=$rows->tanggal_pengajuan;
			$sub_array[]=$rows->no_do;
			$sub_array[]=$rows->no_po;
			$sub_array[]=$rows->customer;
			$sub_array[]=$rows->barang;
			$sub_array[]=$rows->kuantitas;
			$sub_array[]=$rows->kondisi_barang;
			$sub_array[]=$rows->foto_barang;
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

	public function saveClaim(){
		if($_POST){
			$image = $_POST['image'];
			$name = $_POST['name'];
			$idSales = $_POST['sales_id'];
			$folderPath="./image/".$name;
			$tanggal_pengajuan = $this->input->post('tanggal_pengajuan');
			$no_do = $this->input->post('no_do');
			$no_po = $this->input->post('no_po');
			$customer = $this->input->post('customer');
			$kuantitas = $this->input->post('kuantitas');
			$barang = $this->input->post('barang');
			$kondisi_barang = $this->input->post('kondisi_barang');
			$status = $this->input->post('status');
			$idClaim = $this->acak(11);
			$realImage = base64_decode($image);
			$files = file_put_contents("./image/".$name, $realImage);
			$data = array(
				"no_claim"=>$idClaim,
				"tanggal_pengajuan"=>$tanggal_pengajuan,
				"no_do"=>$no_do,
				"no_po"=>$no_po,
				"customer"=>$customer,
				"barang"=>$barang,
				"kuantitas"=>$kuantitas,
				"kondisi_barang"=>$kondisi_barang,
				"foto_barang"=>$name,
				"status"=>$status,
				"sales_id"=>$idSales,
				"catatan"=>"",
			);
			$save = $this->db->insert('claim',$data);
			
			if($save){
				echo json_encode(array(
					"status"=>200,
					"message"=>"Berhasil melakukan pengajuan Claim",
				));
			}else{
				echo json_encode(array(
					"status"=>500,
					"message"=>"Gagal melakukan pengajuan Claim, mohon coba kembali beberapa saat lagi.",
				));
			}
		}
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

	public function uploadFotoPanel(){
	
		$id = $_POST['id'];
		$image = $_POST['image'];
    	$name = $_POST['name'];
		$folderPath="./image/".$name;
    	$realImage = base64_decode($image);
   		$files = file_put_contents("./image/".$name, $realImage);
		$data = array(
            "photo_panel"=> $name,	
        );
		$this->db->where('task_id',$id);
        $this->db->update('work_order',$data);
		echo json_encode(array(
			"status"=>"1",
			"pesan"=>"Foto berhasil di Upload",  
        ));
    }

	
	public function cek_jarak()
	{
		if ($_POST) {
			$jarak = $this->input->post('jarak');
			$pd = $this->input->post('pd');
			$cek = $this->db->get_where('setting_jarak', array('id'=>1))->row();
			if ($cek->jarak_max > $jarak && $pd == false) {

				//TODO : Fungsi simpan data absen disini
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
					'pesan' => "Radius Absensi tidak boleh melebihi $cek->jarak_max M"
				);
				echo json_encode($result);
			}
		}

	}


	public function sendLocation(){
		if($_POST){
			$lat  = $this->input->post("latitude");
			$long = $this->input->post("longitude");
			$current = $this->input->post("currentloc");
			$id   = $this->input->post("id");
			$update_at = $this->input->post("update_lokasi_at");

			$data = array(
				"latitude" => $lat,
				"longitude" => $long,
				"alamatfromapp" => $current,
				"update_lokasi_at"=>$update_at,
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