<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Api extends REST_Controller
{	
	
	protected $builtInMethods;
	
	public function __construct()
	{
		parent::__construct();
		$this->__getMyMethods();
		$this->load->model('mapi');
	}

	function setor_get(){
    	$tgl = $this->get('tgl');
    	if($tgl==''){
			$this->response(array('error' => 'Data parameter kosong'), 400); 
		}
		else {
			
		$tg1 = substr($tgl,0,2);
		$tg2 = substr($tgl,2,2);
		$tg3 = substr($tgl,4,8);
		
		$tanggal =  $tg3.'-'.$tg2.'-'.$tg1;
		$query1 = $this->db->query(" SELECT kd_skpd,nm_skpd,tgl_terima,no_terima,kd_rek6,nm_rek6,nilai as nilai_penerimaan, tgl_sts,no_sts,sumber,keterangan,total nilai_setor FROM (
SELECT a.kd_skpd,(select nm_skpd from ms_skpd where a.kd_skpd=kd_skpd)nm_skpd,a.tgl_terima tgl, a.no_terima no, 
case when a.tgl_terima >= '$tanggal' and a.tgl_terima <= '$tanggal' then cast (a.tgl_terima as varchar(25)) else '' end tgl_terima, 
case when a.tgl_terima >= '$tanggal' and a.tgl_terima <= '$tanggal' then a.no_terima else '' end no_terima, a.kd_rek6,b.nm_rek6, 
case when a.tgl_terima >= '$tanggal' and a.tgl_terima <= '$tanggal' then a.nilai else 0 end nilai, 
case when c.tgl_sts >= '$tanggal' and c.tgl_sts <= '$tanggal' then cast (c.tgl_sts as varchar(25)) else '' end tgl_sts, 
case when c.tgl_sts >= '$tanggal' and c.tgl_sts <= '$tanggal' then c.no_sts else '' end no_sts, 
case when c.tgl_sts >= '$tanggal' and c.tgl_sts <= '$tanggal' then c.rupiah else 0 end total, a.keterangan, c.sumber FROM tr_terima a 
INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 
LEFT JOIN (
SELECT x.tgl_sts,x.no_sts,x.kd_skpd,y.no_terima,SUM(y.rupiah) as rupiah, y.sumber FROM trhkasin_pkd x 
INNER JOIN trdkasin_pkd y ON x.no_sts=y.no_sts AND x.kd_skpd=y.kd_skpd AND x.kd_sub_kegiatan=y.kd_sub_kegiatan 
GROUP BY x.tgl_sts,x.no_sts,x.kd_skpd,y.no_terima, y.sumber) c ON a.no_terima=c.no_terima AND a.kd_skpd=c.kd_skpd 
where ((a.tgl_terima >= '$tanggal' and a.tgl_terima <= '$tanggal') or (c.tgl_sts >= '$tanggal' and c.tgl_sts <= '$tanggal')) 
and left(a.kd_skpd,len('5.02.0.00.0.00.01')) = '5.02.0.00.0.00.01' 
union all 
select x.kd_skpd,''nm_skpd,x.tgl_sts tgl, x.no_sts no, '' tgl_terima, '' no_terima, kd_rek6, (
select nm_rek6 from ms_rek6 
where kd_rek6=y.kd_rek6) nm_rek6, 0 nilai, cast (x.tgl_sts as varchar(25)) tgl_sts, x.no_sts, y.rupiah total, x.keterangan, y.sumber FROM trhkasin_pkd x 
INNER JOIN trdkasin_pkd y ON x.no_sts=y.no_sts AND x.kd_skpd=y.kd_skpd AND x.kd_sub_kegiatan=y.kd_sub_kegiatan 
where x.tgl_sts >= '$tanggal' and x.tgl_sts <= '$tanggal' and 
left(x.kd_skpd,len('5.02.0.00.0.00.01')) = '5.02.0.00.0.00.01' and jns_trans='2' 
union all 
select x.kd_skpd,''nm_skpd,x.tgl_sts tgl, x.no_sts no, cast(x.tgl_sts as varchar(25)) tgl_terima, x.no_sts no_terima, kd_rek6, (
select nm_rek6 from ms_rek6 
where kd_rek6=y.kd_rek6) nm_rek6, y.rupiah*-1 nilai, cast(x.tgl_sts as varchar(25)) tgl_sts, x.no_sts, y.rupiah*-1 total, x.keterangan, y.sumber FROM trhkasin_pkd x 
INNER JOIN trdkasin_pkd y ON x.no_sts=y.no_sts AND x.kd_skpd=y.kd_skpd AND x.kd_sub_kegiatan=y.kd_sub_kegiatan 
where x.tgl_sts >= '$tanggal' and x.tgl_sts <= '$tanggal' 
and left(x.kd_skpd,len('5.02.0.00.0.00.01')) = '5.02.0.00.0.00.01' and jns_trans='3'
) Z order by tgl, no

 ");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii, 
                        'kd_skpd' =>$resulte['kd_skpd'],
                        'nm_skpd' =>$resulte['nm_skpd'],
                        'tgl_terima' =>$resulte['tgl_terima'],
                        'no_terima' =>$resulte['no_terima'],
                        'kd_rek6' =>$resulte['kd_rek6'],
                        'nm_rek6' =>$resulte['nm_rek6'],
                        'nilai_penerimaan' => $resulte['nilai_penerimaan'],
                        'tgl_terima' =>$resulte['tgl_terima'],
                        'tgl_sts' =>$resulte['tgl_sts'],
                        'no_sts' =>$resulte['no_sts'],
                        'sumber' =>$resulte['sumber'],
                        'keterangan' =>$resulte['keterangan'],
                        'nilai_setor' => $resulte['nilai_setor']

                        );
                        $ii++;
        }

      }
           
           //return $result;
		   echo json_encode($result);

/*
			$query = $this->mapi->get_kasda('trhkasin_ppkd');
			if($query) {
				$this->response(array('data' => $query), 200);                
			} else {
				$this->response(array('error' => 'Data tidak ditemukan'), 404);
			}*/


		
	}
    
    function kasda_get(){
    	$tgl = $this->get('tgl');
    	if($tgl==''){
			$this->response(array('error' => 'Data parameter kosong'), 400); 
		}
		else {
			
		$tg1 = substr($tgl,0,2);
		$tg2 = substr($tgl,2,2);
		$tg3 = substr($tgl,4,8);
		
		$tanggal =  $tg3.'-'.$tg2.'-'.$tg1;
		$query1 = $this->db->query(" SELECT a.kd_skpd,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd,a.kd_sub_kegiatan kd_sub_kegiatan,b.kd_rek6 as kd_rek6,a.no_sts as no_sts,
tgl_sts,jns_trans,a.no_kas,tgl_kas,keterangan,b.sumber as sumber,rupiah from trhkasin_ppkd a
inner join trdkasin_ppkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
WHERE a.no_kas NOT in (SELECT TOP 0 no_kas FROM trhkasin_ppkd a order by tgl_kas,no_kas) and left(a.kd_skpd,17)='5.02.0.00.0.00.01' and tgl_sts='$tanggal'
order by a.kd_skpd,a.tgl_kas,a.no_kas
 ");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii, 
                        'kd_skpd' =>$resulte['kd_skpd'],
                        'nm_skpd' =>$resulte['nm_skpd'],
                        'kd_sub_kegiatan' =>$resulte['kd_sub_kegiatan'],
                        'kd_rek6' =>$resulte['kd_rek6'],
                        'no_sts' =>$resulte['no_sts'],
                        'tgl_sts' =>$resulte['tgl_sts'],
                        'jns_trans' =>$resulte['jns_trans'],
                        'no_kas' =>$resulte['no_kas'],
                        'tgl_kas' =>$resulte['tgl_kas'],
                        'keterangan' =>$resulte['keterangan'],
                        'sumber' =>$resulte['sumber'],
                        'rupiah' => $resulte['rupiah']

                        );
                        $ii++;
        }

      }
           
           //return $result;
		   echo json_encode($result);

/*
			$query = $this->mapi->get_kasda('trhkasin_ppkd');
			if($query) {
				$this->response(array('data' => $query), 200);                
			} else {
				$this->response(array('error' => 'Data tidak ditemukan'), 404);
			}*/


		
	}
    
    function view_get(){
		ini_set('max_execution_time', -1); 
		ini_set('memory_limit','2048M');
		$tgl = $this->get('tgl');
        if($tgl==''){
			$this->response(array('error' => 'Data parameter kosong'), 400); 
		}
		else {
			
		$tg1 = substr($tgl,0,2);
		$tg2 = substr($tgl,2,2);
		$tg3 = substr($tgl,4,8);
		
		$tanggal =  $tg3.'-'.$tg2.'-'.$tg1;
			
			$query = $this->mapi->get_tgl($tanggal,'tsamsat','tgl_samsat');
			if($query) {
				$this->response(array('data' => $query), 200);                
			} else {
				$this->response(array('error' => 'Data tidak ditemukan'), 404);
			}
		}
	}
	
	function savelama_get(){
		ini_set('max_execution_time', -1); 
		ini_set('memory_limit','2048M');
        $tgl1 = $this->get('tgl');
		$dati = $this->get('dati');

		
		if($tgl1==''){
			$this->response(array('error' => 'Data parameter kosong'), 400); 
		}
		
		if($dati==''){
			$dati='01';
		}
		
        //$url = "http://36.66.239.162:8181/simakda/smdp3.php?username=simakda&password=5a24e942bcffd&tgl=15022020&kddati2=01";
        $url = "http://http://36.66.239.162:8585/simakda?username=simakda&password=5a24e942bcffd&tgl=".$tgl1;
        
		$tg1 = substr($tgl1,0,2);
		$tg2 = substr($tgl1,2,2);
		$tg3 = substr($tgl1,4,8);
		
		$tanggal =  $tg3.'-'.$tg2.'-'.$tg1;
		date_default_timezone_set('Asia/Jakarta');
		$now 	 = date('Y-m-d H:i:s');
		
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $array = json_decode(trim($data), TRUE);
		
        $content =  $array;
        
		$row_num = count($content);
        $dsql='';
        $ii = 0;
         if($row_num>0){
            foreach($content as $resulte){
            
				if($ii==0){
                    $dsql = $dsql."('".$tanggal."','".$resulte['no_rek']."','".$resulte['kode']."',".$resulte['jml_pener'].",'".$resulte['kd_uptbyr']."','".$resulte['kd_upt']."','".$now."')";    
                }else{
                    $dsql = $dsql.",('".$tanggal."','".$resulte['no_rek']."','".$resulte['kode']."',".$resulte['jml_pener'].",'".$resulte['kd_uptbyr']."','".$resulte['kd_upt']."','".$now."')";  
                }
            $ii++;    
            }
            
            $insert =  $this->mapi->save_tgl('tsamsat',$dsql,$tanggal);
			
            if ($insert) {
				$this->response(array('status' => 'berhasil', 200));
			} else {
				$this->response(array('status' => 'gagal', 502));
			}
		
		 }
    }
	
	function save_get(){
		ini_set('max_execution_time', -1); 
		ini_set('memory_limit','2048M');
        $tgl1 = $this->get('tgl');
		date_default_timezone_set('Asia/Jakarta');
		$now 	 = date('Y-m-d H:i:s');
		
		$tg1 = substr($tgl1,0,2);
		$tg2 = substr($tgl1,2,2);
		$tg3 = substr($tgl1,4,8);
		
		$tanggal =  $tg3.'-'.$tg2.'-'.$tg1;
		
		$url = "http://36.66.239.162:8585/simakda?username=simakda&password=5a24e942bcffd&tgl=".$tgl1;
		
		$data = file_get_contents($url);

		// print_r($data);
		// return;
		
		$content = json_decode($data, TRUE);
		
		
		$row_num = count($content);
        $dsql='';
        $ii = 0;

        if($row_num>0){
            foreach($content as $resulte){

            	$cek_upt = $content[$ii]['kd_upt'];
		        $skpdsamsat=$this->db->query("SELECT isnull(kd_skpd,0) kd_skpd from map_samsat where kd_samsat='$cek_upt'")->row();
        		$skpd_samsat=$skpdsamsat->kd_skpd;
        		

            	if($resulte['no_rek']==4110601){
                   $content[$ii]['no_rek']=4110201;
                } else if ($resulte['no_rek']==4110602){
                   $content[$ii]['no_rek']=4110202;
                } else if ($resulte['no_rek']==4110603){
                   $content[$ii]['no_rek']=4110203;
                } else if ($resulte['no_rek']==4110604){
                   $content[$ii]['no_rek']=4110204;
                } else if ($resulte['no_rek']==4110605){
                   $content[$ii]['no_rek']=4110205;
                } else if ($resulte['no_rek']==4110606){
                   $content[$ii]['no_rek']=4110206;
                } else if ($resulte['no_rek']==4110607){
                   $content[$ii]['no_rek']=4110207;
                } else if ($resulte['no_rek']==4110608){
                   $content[$ii]['no_rek']=4110208;
                } else if ($resulte['no_rek']==4110609){
                   $content[$ii]['no_rek']=4110209;
                } else if ($resulte['no_rek']==4110610){
                   $content[$ii]['no_rek']=4110210;
                } else if ($resulte['no_rek']==4110611){
                   $content[$ii]['no_rek']=4110211;
                } else if ($resulte['no_rek']==4110612){
                   $content[$ii]['no_rek']=4110212;
                } else if ($resulte['no_rek']==4110613){
                   $content[$ii]['no_rek']=4110213;
                } else {
                   $resulte['no_rek'];
                } 

                //cek
					if($content[$ii]['kd_dati2']==''){
						$this->response(array('status' => 'kd_dati2 ada yang kosong', 502));
					}
					
					if($content[$ii]['no_rek']==''){
						$this->response(array('status' => 'no_rek ada yang kosong', 502));
					}
					
					if($content[$ii]['kode']==''){
						$this->response(array('status' => 'kode ada yang kosong', 502));
					}
					
					if($content[$ii]['jml_pener']==''){
						$this->response(array('status' => 'jml_pener ada yang kosong', 502));
					}
					
					if($content[$ii]['kd_upt']==''){
						$this->response(array('status' => 'kd_upt ada yang kosong', 502));
					}
					
					if($content[$ii]['kd_uptbyr']==''){
						$this->response(array('status' => 'kd_uptbyr ada yang kosong', 502));
					}
					
					if($content[$ii]['kd_lokasi']==''){
						$this->response(array('status' => 'kd_lokasi ada yang kosong', 502));
					}

				if($ii==0){
					
					//insert
                    $dsql = $dsql."('".$tanggal."','".$content[$ii]['no_rek']."','".$content[$ii]['kode']."',".$content[$ii]['jml_pener'].",'".$content[$ii]['kd_uptbyr']."','".$skpd_samsat."','".$now."','".$content[$ii]['kanal']."')";    
                }else{
					
					//insert
                    $dsql = $dsql.",('".$tanggal."','".$content[$ii]['no_rek']."','".$content[$ii]['kode']."',".$content[$ii]['jml_pener'].",'".$content[$ii]['kd_uptbyr']."','".$skpd_samsat."','".$now."','".$content[$ii]['kanal']."')";  
                }
            $ii++;    
            }
            
            
			$insert_ =  $this->mapi->save_tgl('tsamsat',$dsql,$tanggal);

			if($insert_){
				$insert_ = $this->db->query("insert into tr_tetap select * from tr_tetap_api where no_tetap+kanal not in (select no_tetap+kanal from tr_tetap where tgl_tetap='$tanggal') and tgl_tetap='$tanggal'");
            	$insert_ = $this->db->query("insert into tr_terima select * from tr_terima_api where no_terima+kanal not in (select no_terima+kanal from tr_terima where kunci=1 and tgl_terima='$tanggal') and tgl_terima='$tanggal'");

            	if ($insert_) {
					$this->response(array('status' => 'berhasil', 200));
				} else {
					$this->response(array('status' => 'gagal', 502));
				}

			}else{
				$this->response(array('status' => 'gagal', 502));
			} 


            
			
            
			

			}					
						
		
    }

	function savecustom_get(){
		ini_set('max_execution_time', -1); 
		ini_set('memory_limit','2048M');
        $tgl1 = $this->get('tgl');
		date_default_timezone_set('Asia/Jakarta');
		$now 	 = date('Y-m-d H:i:s');
		
		$tg1 = substr($tgl1,0,2);
		$tg2 = substr($tgl1,2,2);
		$tg3 = substr($tgl1,4,8);
		
		$tanggal =  $tg3.'-'.$tg2.'-'.$tg1;
		
		$url = "http://36.66.239.162:8585/simakda?username=simakda&password=5a24e942bcffd&tgl=".$tgl1;
		
		$data = file_get_contents($url);

		// print_r($data);
		// return;
		
		$content = json_decode($data, TRUE);
		
		
		$row_num = count($content);
        $dsql='';
        $ii = 0;

        if($row_num>0){
            foreach($content as $resulte){

            	$cek_upt = $content[$ii]['kd_upt'];
		        $skpdsamsat=$this->db->query("SELECT isnull(kd_skpd,0) kd_skpd from map_samsat where kd_samsat='$cek_upt'")->row();
        		$skpd_samsat=$skpdsamsat->kd_skpd;
        		

            	if($resulte['no_rek']==4110601){
                   $content[$ii]['no_rek']=4110201;
                } else if ($resulte['no_rek']==4110602){
                   $content[$ii]['no_rek']=4110202;
                } else if ($resulte['no_rek']==4110603){
                   $content[$ii]['no_rek']=4110203;
                } else if ($resulte['no_rek']==4110604){
                   $content[$ii]['no_rek']=4110204;
                } else if ($resulte['no_rek']==4110605){
                   $content[$ii]['no_rek']=4110205;
                } else if ($resulte['no_rek']==4110606){
                   $content[$ii]['no_rek']=4110206;
                } else if ($resulte['no_rek']==4110607){
                   $content[$ii]['no_rek']=4110207;
                } else if ($resulte['no_rek']==4110608){
                   $content[$ii]['no_rek']=4110208;
                } else if ($resulte['no_rek']==4110609){
                   $content[$ii]['no_rek']=4110209;
                } else if ($resulte['no_rek']==4110610){
                   $content[$ii]['no_rek']=4110210;
                } else if ($resulte['no_rek']==4110611){
                   $content[$ii]['no_rek']=4110211;
                } else if ($resulte['no_rek']==4110612){
                   $content[$ii]['no_rek']=4110212;
                } else if ($resulte['no_rek']==4110613){
                   $content[$ii]['no_rek']=4110213;
                } else {
                   $resulte['no_rek'];
                } 

                //cek
					if($content[$ii]['kd_dati2']==''){
						$this->response(array('status' => 'kd_dati2 ada yang kosong', 502));
					}
					
					if($content[$ii]['no_rek']==''){
						$this->response(array('status' => 'no_rek ada yang kosong', 502));
					}
					
					if($content[$ii]['kode']==''){
						$this->response(array('status' => 'kode ada yang kosong', 502));
					}
					
					if($content[$ii]['jml_pener']==''){
						$this->response(array('status' => 'jml_pener ada yang kosong', 502));
					}
					
					if($content[$ii]['kd_upt']==''){
						$this->response(array('status' => 'kd_upt ada yang kosong', 502));
					}
					
					if($content[$ii]['kd_uptbyr']==''){
						$this->response(array('status' => 'kd_uptbyr ada yang kosong', 502));
					}
					
					if($content[$ii]['kd_lokasi']==''){
						$this->response(array('status' => 'kd_lokasi ada yang kosong', 502));
					}

				if($ii==0){
					
					//insert
                    $dsql = $dsql."('".$tanggal."','".$content[$ii]['no_rek']."','".$content[$ii]['kode']."',".$content[$ii]['jml_pener'].",'".$content[$ii]['kd_uptbyr']."','".$skpd_samsat."','".$now."','".$content[$ii]['kanal']."')";    
                }else{
					
					//insert
                    $dsql = $dsql.",('".$tanggal."','".$content[$ii]['no_rek']."','".$content[$ii]['kode']."',".$content[$ii]['jml_pener'].",'".$content[$ii]['kd_uptbyr']."','".$skpd_samsat."','".$now."','".$content[$ii]['kanal']."')";  
                }
            $ii++;    
            }
            $kodegerai = "('0106','0111')";
            
			$insert_ =  $this->mapi->savecustom_tgl('tsamsat',$dsql,$tanggal);

			if($insert_){
				$insert_ = $this->db->query("insert into tr_tetap select * from tr_tetap_api where no_tetap+kanal not in (select no_tetap+kanal from tr_tetap where tgl_tetap='$tanggal' and substring(substring(no_tetap,22,len(no_tetap)-21),2,len(substring(no_tetap,22,len(no_tetap)-21))-38) in $kodegerai ) and tgl_tetap='$tanggal' and substring(substring(no_tetap,22,len(no_tetap)-21),2,len(substring(no_tetap,22,len(no_tetap)-21))-38) in $kodegerai");
            	$insert_ = $this->db->query("insert into tr_terima select * from tr_terima_api where no_terima+kanal not in (select no_terima+kanal from tr_terima where kunci=1 and tgl_terima='$tanggal' and sumber in $kodegerai) and tgl_terima='$tanggal' and sumber in $kodegerai");

            	if ($insert_) {
					$this->response(array('status' => 'berhasil', 200));
				} else {
					$this->response(array('status' => 'gagal', 502));
				}

			}else{
				$this->response(array('status' => 'gagal', 502));
			} 


            
			
            
			

			}					
						
		
    }
    
    
	/**
	 * 
	 * Analizes self methods using reflection
	 * @return Boolean
	 */
	private function __getMyMethods()
	{
		$reflection = new ReflectionClass($this);
		
		//get all methods
		$methods = $reflection->getMethods();
		$this->builtInMethods = array();
		
		//get properties for each method
		if(!empty($methods))
		{
			foreach ($methods as $method) {
				if(!empty($method->name))
				{
					$methodProp = new ReflectionMethod($this, $method->name);
					
					//saves all methods names found
					$this->builtInMethods['all'][] = $method->name;
					
					//saves all private methods names found
					if($methodProp->isPrivate()) 
					{
						$this->builtInMethods['private'][] = $method->name;
					}
					
					//saves all private methods names found					
					if($methodProp->isPublic()) 
					{
						$this->builtInMethods['public'][] = $method->name;
						
						// gets info about the method and saves them. These info will be used for the xmlrpc server configuration.
						// (only for public methods => avoids also all the public methods starting with '_')
						if(!preg_match('/^_/', $method->name, $matches))
						{
							//consider only the methods having "_" inside their name
							if(preg_match('/_/', $method->name, $matches))
							{	
								//don't consider the methods get_instance and validation_errors
								if($method->name != 'get_instance' AND $method->name != 'validation_errors')
								{
									// -method name: user_get becomes [GET] user
									$name_split = explode("_", $method->name);
									$this->builtInMethods['functions'][$method->name]['function'] = $name_split['0'].' [method: '.$name_split['1'].']';
									
									// -method DocString
									$this->builtInMethods['functions'][$method->name]['docstring'] =  $this->__extractDocString($methodProp->getDocComment());
								}
							}
						}
					}
				}
			}
		} else {
			return false;
		}
		return true;
	}
	
	/**
	 * 
	 * Manipulates a DocString and returns a readable string
	 * @param String $DocComment
	 * @return Array $_tmp
	 */
	private function __extractDocString($DocComment)
	{
		$split = preg_split("/\r\n|\n|\r/", $DocComment);
		$_tmp = array();
		foreach ($split as $id => $row)
		{
			//clean up: removes useless chars like new-lines, tabs and *
			$_tmp[] = trim($row, "* /\n\t\r");
		}			
		return trim(implode("\n",$_tmp));
	}



	public function API_get()
	{
		$this->response($this->builtInMethods, 200); // 200 being the HTTP response code
	}
	
	// BIRO

	function spd_get(){
		ini_set('max_execution_time', -1); 
		ini_set('memory_limit','-1');
		$skpd = $this->get('kd_skpd');
        if($skpd==''){
			$this->response(array('error' => 'SKPD Belum dipilih'), 400); 
		}else {
			if ($skpd=='all'){
				$query = $this->mapi->get_spd($skpd,'b_spd_all','all');
			}else{
				$query = $this->mapi->get_spd($skpd,'b_spd2','kd_skpd');
			}

			if($query) {
				$this->response(array('data' => $query), 200);                
			} else {
				$this->response(array('error' => 'Data tidak ditemukan'), 404);
			}
		}
	}

	function pptk_post(){
		ini_set('max_execution_time', -1); 
		ini_set('memory_limit','-1');
		$request 	= file_get_contents("php://input");
		$json 		= json_decode($request, true);
		$in 		= $json['kd_sub_kegiatan'];
		$skpd 		= $json['kd_skpd'];
		$jenis 		= $json['jenis'];
        if($skpd==''){
			$this->response(array('error' => 'SKPD Belum dipilih'), 400); 
		}else {

			if($jenis=='all'){
				$query = $this->mapi->get_pptk($skpd,'b_pptk_all',$in);
			}else{
				$query = $this->mapi->get_pptk($skpd,'b_pptk_spj',$in);
			}
				
			

			if($query) {
				$this->response(array('data' => $query), 200);                
			} else {
				$this->response(array('error' => 'Data tidak ditemukan'), 404);
			}
		}
	}

	function skpd_get(){
		ini_set('max_execution_time', -1); 
		ini_set('memory_limit','-1');
			$query = $this->mapi->get_skpd();
			if($query) {
				$this->response(array('data' => $query), 200);                
			} else {
				$this->response(array('error' => 'Data tidak ditemukan'), 404);
			}
	}

	function dashboard_get(){
		ini_set('max_execution_time', -1); 
		ini_set('memory_limit','-1');
			$query = $this->mapi->get_dashboard();
			if($query) {
				$this->response(array('data' => $query), 200);                
			} else {
				$this->response(array('error' => 'Data tidak ditemukan'), 404);
			}
	}

	function lpj_get(){
		ini_set('max_execution_time', -1); 
		ini_set('memory_limit','-1');
			$query = $this->mapi->get_lpj();
			if($query) {
				$this->response(array('data' => $query), 200);                
			} else {
				$this->response(array('error' => 'Data tidak ditemukan'), 404);
			}
	}

	function subkegiatan_get(){
		ini_set('max_execution_time', -1); 
		ini_set('memory_limit','-1');
		$skpd = $this->get('kd_skpd');
        if($skpd==''){
			$this->response(array('error' => 'SKPD Belum dipilih'), 400); 
		}else {
			
			$query = $this->mapi->get_subkegiatan($skpd);
			if($query) {
				$this->response(array('data' => $query), 200);                
			} else {
				$this->response(array('error' => 'Data tidak ditemukan'), 404);
			}
		}
	}

	function rekening_get(){
		ini_set('max_execution_time', -1); 
		ini_set('memory_limit','-1');
		$skpd 			= $this->get('kd_skpd');
		$subkegiatan 	= $this->get('subkegiatan');
        if($skpd==''){
			$this->response(array('error' => 'SKPD Belum dipilih'), 400); 
		}else if($subkegiatan==''){
			$this->response(array('error' => 'Sub Kegiatan Belum dipilih'), 400); 
		}else {
			
			$query = $this->mapi->get_rekening($skpd,$subkegiatan);
			if($query) {
				$this->response(array('data' => $query), 200);                
			} else {
				$this->response(array('error' => 'Data tidak ditemukan'), 404);
			}
		}
	}


	function jnsang_get(){
		ini_set('max_execution_time', -1); 
		ini_set('memory_limit','-1');
      
			$query = $this->mapi->get_jnsang();
			if($query) {
				$this->response(array('data' => $query), 200);                
			} else {
				$this->response(array('error' => 'Data tidak ditemukan'), 404);
			}
	}

	function realisasifisik_get(){
		ini_set('max_execution_time', -1); 
		ini_set('memory_limit','-1');
		$skpd = $this->get('kd_skpd');
		$bulan = $this->get('bulan');
		$jns_ang = $this->get('jns_ang');
        if($skpd==''){
			$this->response(array('error' => 'SKPD Belum dipilih'), 400); 
		}else {
			
			$query = $this->mapi->get_realisasifisik($skpd,$bulan,$jns_ang);
			if($query) {
				$this->response(array('data' => $query), 200);                
			} else {
				$this->response(array('error' => 'Data tidak ditemukan'), 404);
			}
		}
	}

	function spjfungsional_get(){
		ini_set('max_execution_time', -1); 
		ini_set('memory_limit','-1');
		$skpd = $this->get('kd_skpd');
		$nbulan = $this->get('bulan');
		$jns_ang = $this->get('jns_ang');
        if($skpd==''){
			$this->response(array('error' => 'SKPD Belum dipilih'), 400); 
		}else {
			
			$query = $this->mapi->get_spjfungsional($skpd,$nbulan,$jns_ang);
			if($query) {
				$this->response(array('data' => $query), 200);                
			} else {
				$this->response(array('error' => 'Data tidak ditemukan'), 404);
			}
		}
	}


	function spjterima_get(){
		ini_set('max_execution_time', -1); 
		ini_set('memory_limit','-1');
		$skpd = $this->get('kd_skpd');
		$nbulan = $this->get('bulan');
		$jns_ang = $this->get('jns_ang');
        if($skpd==''){
			$this->response(array('error' => 'SKPD Belum dipilih'), 400); 
		}else {
			
			$query = $this->mapi->get_spjterima($skpd,$nbulan,$jns_ang);
			if($query) {
				$this->response(array('data' => $query), 200);                
			} else {
				$this->response(array('error' => 'Data tidak ditemukan'), 404);
			}
		}
	}

	function sp2d_get(){
		ini_set('max_execution_time', -1); 
		ini_set('memory_limit','2048M');
		$skpd = $this->get('kd_skpd');
		$subkegiatan = $this->get('subkegiatan');

        if($skpd==''){
			$this->response(array('error' => 'SKPD Belum dipilih'), 400); 
		}else {
			
			$query = $this->mapi->get_sp2d($skpd,$subkegiatan,'b_sp2d','kd_skpd','kd_sub_kegiatan');
			if($query) {
				$this->response(array('data' => $query), 200);                
			} else {
				$this->response(array('error' => 'Data tidak ditemukan'), 404);
			}
		}
	}
	
    
}