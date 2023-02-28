<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
Class Mapi extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function insert_data($data,$tabel){	   
	   $data = $this->db->insert($tabel, $data);       
       if ($data) {
         return $data;
       } else {
         return false;
       }        
	}    
	
	function save_tgl($tabel,$data,$tgl){
		$hasil = 0; $cek = 0;
        $csql1 ="delete from $tabel where tgl_samsat='$tgl' ";
        $cek = $this->db->query($csql1);
        if($cek>0){
            $csql ="insert into $tabel values $data";
            $hasil = $this->db->query($csql);
        }
		
		/*Sub Query*/
		$tabel_tetap = "tr_tetap";
		$tabel_tetap_api = "tr_tetap_api";
		$tabel_terima= "tr_terima";	
		$tabel_terima_api= "tr_terima_api";
		
		if($hasil){
			//tetap
			$hasil = $this->db->query("delete from $tabel_tetap where tgl_tetap='$tgl'");	
			$hasil = $this->db->query("delete from $tabel_tetap_api where tgl_tetap='$tgl'");
			
			$hasil = $this->db->query("
			INSERT into $tabel_tetap_api
			select 
			isnull(no_tetap,'') no_tetap,
			tgl_samsat,kd_skpd,ISNULL(no_rek,no_rek2) [no_rek],kd_sub_kegiatan,isnull(kd_rek_lo,'') kd_rek_lo,sum(nilai) [nilai],isnull(keterangan,'') keterangan,'samsat' user_name,isnull(kanal,'')as kanal from(
				select 
				replace(tgl_samsat,'-','')+'/'+b.kd_rek6+'/'+kd_uptbyr+'/'+a.kd_upt+'/tetap'+kode+'/samsat'+'/'+isnull(kanal,'') [no_tetap],
				replace(tgl_samsat,'-','')+'/'+b.kd_rek6+'/'+kd_uptbyr+'/'+a.kd_upt+'/terima'+kode+'/samsat'+'/'+isnull(kanal,'') [no_terima],

				a.tgl_samsat,a.kd_upt [kd_skpd],b.kd_rek6 [no_rek],a.no_rek [no_rek2],b.nm_rek6,
				a.kode [jenis],a.kd_uptbyr,c.nm_pengirim,a.jml_pener [nilai],b.map_lo [kd_rek_lo],b.nm_rek6+' dari '+c.nm_pengirim [keterangan],LEFT(a.kd_upt,4)+'.00.0.00.04' [kd_sub_kegiatan],a.kanal
				from $tabel a  left join  
					(
					select d.kd_rek6,d.kd_rek64,d.nm_rek6,d.map_lo from ms_rek6 d join trdrka_pend e on d.kd_rek6=e.kd_rek6 group by d.kd_rek6,d.kd_rek64,d.nm_rek6,d.map_lo
					) b on a.no_rek=b.kd_rek6
					left join ms_pengirim c on a.kd_uptbyr=c.kd_pengirim
				  	where tgl_samsat='$tgl'
					)as gabung group by no_tetap,no_terima,tgl_samsat,kd_skpd,no_rek,no_rek2,nm_rek6,jenis,kd_uptbyr,nm_pengirim,kd_rek_lo,keterangan,kd_sub_kegiatan,kanal order by kd_uptbyr,no_rek");
		
			//terima
			$hasil = $this->db->query("delete from $tabel_terima where tgl_tetap='$tgl' and kunci=0");
			$hasil = $this->db->query("delete from $tabel_terima_api where tgl_tetap='$tgl'");
			
			$hasil = $this->db->query("
			INSERT into $tabel_terima_api
			select 
			isnull(no_terima,'') no_terima,tgl_samsat,isnull(no_tetap,'') no_tetap,tgl_samsat,'1' sts_tetap,
			kd_skpd,kd_sub_kegiatan,ISNULL(no_rek,no_rek2) [no_rek],isnull(kd_rek_lo,'') kd_rek_lo,sum(nilai) [nilai],
			isnull(keterangan,'') keterangan,'1' jenis,'samsat' user_name,kd_uptbyr as sumber,'0' kunci ,isnull(kanal,'')as kanal
			from(
				select 
				replace(tgl_samsat,'-','')+'/'+b.kd_rek6+'/'+kd_uptbyr+'/'+a.kd_upt+'/tetap'+kode+'/samsat'+'/'+isnull(kanal,'') [no_tetap],
				replace(tgl_samsat,'-','')+'/'+b.kd_rek6+'/'+kd_uptbyr+'/'+a.kd_upt+'/terima'+kode+'/samsat'+'/'+isnull(kanal,'') [no_terima],

				a.tgl_samsat,a.kd_upt [kd_skpd],b.kd_rek6 [no_rek],a.no_rek [no_rek2],b.nm_rek6,
				a.kode [jenis],a.kd_uptbyr,c.nm_pengirim,
				a.jml_pener [nilai],b.map_lo [kd_rek_lo],b.nm_rek6+' dari '+c.nm_pengirim [keterangan],LEFT(a.kd_upt,4)+'.00.0.00.04' [kd_sub_kegiatan],a.kanal
				from $tabel a  left join  
				(
					select d.kd_rek6,d.kd_rek64,d.nm_rek6,d.map_lo from ms_rek6 d join trdrka_pend e on d.kd_rek6=e.kd_rek6 group by d.kd_rek6,d.kd_rek64,d.nm_rek6,d.map_lo
				) b on a.no_rek=b.kd_rek6
				left join ms_pengirim c on a.kd_uptbyr=c.kd_pengirim
				where tgl_samsat='$tgl'
				)as gabung group by no_tetap,no_terima,tgl_samsat,kd_skpd,no_rek,no_rek2,nm_rek6,jenis,kd_uptbyr,nm_pengirim,kd_rek_lo,keterangan,kd_sub_kegiatan,kanal order by kd_uptbyr,no_rek");
		
		}
		
        return $hasil;
	}

	function savecustom_tgl($tabel,$data,$tgl,$kodegerai){
		$hasil = 0; $cek = 0;
        $csql1 ="delete from $tabel where tgl_samsat='$tgl' and kd_uptbyr in $kodegerai ";
        $cek = $this->db->query($csql1);
        if($cek>0){
            $csql ="insert into $tabel values $data";
            $hasil = $this->db->query($csql);
        }
		
		/*Sub Query*/
		$tabel_tetap = "tr_tetap";
		$tabel_tetap_api = "tr_tetap_api";
		$tabel_terima= "tr_terima";	
		$tabel_terima_api= "tr_terima_api";
		
		if($hasil){
			//tetap
			$hasil = $this->db->query("delete from $tabel_tetap where tgl_tetap='$tgl' and substring(substring(no_tetap,22,len(no_tetap)-21),2,len(substring(no_tetap,22,len(no_tetap)-21))-38) in $kodegerai ");	
			$hasil = $this->db->query("delete from $tabel_tetap_api where tgl_tetap='$tgl' and and substring(substring(no_tetap,22,len(no_tetap)-21),2,len(substring(no_tetap,22,len(no_tetap)-21))-38) in $kodegerai ");
			
			$hasil = $this->db->query("
			INSERT into $tabel_tetap_api
			select 
			isnull(no_tetap,'') no_tetap,
			tgl_samsat,kd_skpd,ISNULL(no_rek,no_rek2) [no_rek],kd_sub_kegiatan,isnull(kd_rek_lo,'') kd_rek_lo,sum(nilai) [nilai],isnull(keterangan,'') keterangan,'samsat' user_name,isnull(kanal,'')as kanal from(
				select 
				replace(tgl_samsat,'-','')+'/'+b.kd_rek6+'/'+kd_uptbyr+'/'+a.kd_upt+'/tetap'+kode+'/samsat'+'/'+isnull(kanal,'') [no_tetap],
				replace(tgl_samsat,'-','')+'/'+b.kd_rek6+'/'+kd_uptbyr+'/'+a.kd_upt+'/terima'+kode+'/samsat'+'/'+isnull(kanal,'') [no_terima],

				a.tgl_samsat,a.kd_upt [kd_skpd],b.kd_rek6 [no_rek],a.no_rek [no_rek2],b.nm_rek6,
				a.kode [jenis],a.kd_uptbyr,c.nm_pengirim,a.jml_pener [nilai],b.map_lo [kd_rek_lo],b.nm_rek6+' dari '+c.nm_pengirim [keterangan],LEFT(a.kd_upt,4)+'.00.0.00.04' [kd_sub_kegiatan],a.kanal
				from $tabel a  left join  
					(
					select d.kd_rek6,d.kd_rek64,d.nm_rek6,d.map_lo from ms_rek6 d join trdrka_pend e on d.kd_rek6=e.kd_rek6 group by d.kd_rek6,d.kd_rek64,d.nm_rek6,d.map_lo
					) b on a.no_rek=b.kd_rek6
					left join ms_pengirim c on a.kd_uptbyr=c.kd_pengirim
				  	where tgl_samsat='$tgl' and a.kd_uptbyr in $kodegerai 
					)as gabung group by no_tetap,no_terima,tgl_samsat,kd_skpd,no_rek,no_rek2,nm_rek6,jenis,kd_uptbyr,nm_pengirim,kd_rek_lo,keterangan,kd_sub_kegiatan,kanal order by kd_uptbyr,no_rek");
		
			//terima
			$hasil = $this->db->query("delete from $tabel_terima where tgl_tetap='$tgl' and kunci=0 and sumber in $kodegerai ");
			$hasil = $this->db->query("delete from $tabel_terima_api where tgl_tetap='$tgl' and sumber in $kodegerai ");
			
			$hasil = $this->db->query("
			INSERT into $tabel_terima_api
			select 
			isnull(no_terima,'') no_terima,tgl_samsat,isnull(no_tetap,'') no_tetap,tgl_samsat,'1' sts_tetap,
			kd_skpd,kd_sub_kegiatan,ISNULL(no_rek,no_rek2) [no_rek],isnull(kd_rek_lo,'') kd_rek_lo,sum(nilai) [nilai],
			isnull(keterangan,'') keterangan,'1' jenis,'samsat' user_name,kd_uptbyr as sumber,'0' kunci ,isnull(kanal,'')as kanal
			from(
				select 
				replace(tgl_samsat,'-','')+'/'+b.kd_rek6+'/'+kd_uptbyr+'/'+a.kd_upt+'/tetap'+kode+'/samsat'+'/'+isnull(kanal,'') [no_tetap],
				replace(tgl_samsat,'-','')+'/'+b.kd_rek6+'/'+kd_uptbyr+'/'+a.kd_upt+'/terima'+kode+'/samsat'+'/'+isnull(kanal,'') [no_terima],

				a.tgl_samsat,a.kd_upt [kd_skpd],b.kd_rek6 [no_rek],a.no_rek [no_rek2],b.nm_rek6,
				a.kode [jenis],a.kd_uptbyr,c.nm_pengirim,
				a.jml_pener [nilai],b.map_lo [kd_rek_lo],b.nm_rek6+' dari '+c.nm_pengirim [keterangan],LEFT(a.kd_upt,4)+'.00.0.00.04' [kd_sub_kegiatan],a.kanal
				from $tabel a  left join  
				(
					select d.kd_rek6,d.kd_rek64,d.nm_rek6,d.map_lo from ms_rek6 d join trdrka_pend e on d.kd_rek6=e.kd_rek6 group by d.kd_rek6,d.kd_rek64,d.nm_rek6,d.map_lo
				) b on a.no_rek=b.kd_rek6
				left join ms_pengirim c on a.kd_uptbyr=c.kd_pengirim
				where tgl_samsat='$tgl' and a.kd_uptbyr in $kodegerai 
				)as gabung group by no_tetap,no_terima,tgl_samsat,kd_skpd,no_rek,no_rek2,nm_rek6,jenis,kd_uptbyr,nm_pengirim,kd_rek_lo,keterangan,kd_sub_kegiatan,kanal order by kd_uptbyr,no_rek");
		
		}
		
        return $hasil;
	}

	
    function get_tgl($tgl,$tabel,$ky){
		$data = array();
		$this->db->select('*');
        $this->db->where($ky, $tgl);	
        $this->db->from($tabel);
		$hasil = $this->db->get();
		if($hasil->num_rows() > 0){
			return $hasil->result();
			//return $hasil->row();
		}else{
			return $data;
		}
	}

	function get_spd($skpd,$tabel,$ky){
		$data = array();
		$this->db->select('*');
		if ($ky=='all'){
			$this->db->from($tabel);
		}else{
			$this->db->where($ky, $skpd);	
			$this->db->from($tabel);
		}
        
        
		$this->db->order_by('urut');
		$hasil = $this->db->get();
		if($hasil->num_rows() > 0){
			return $hasil->result();
			//return $hasil->row();
		}else{
			return $data;
		}
	}

	function get_pptk($skpd,$tabel,$in){
		$data = array();
		$params = array();
		array_push($params, $skpd);
		$params = array_merge($params, $in);
		$in_string = str_replace(' ', ',', trim(str_repeat("? ", count($in))));

		if($tabel=='b_pptk_spj'){
			$sql = "SELECT kd_skpd+kode as urut,kd_skpd,nm_skpd,kode,nama,
					isnull(sum(anggaran),0) as anggaran,
					isnull(sum(tw1),0)as tw1,
					isnull(sum(rtw1),0)as rtw1,
					isnull(sum(tw2),0)as tw2,
					isnull(sum(rtw2),0)as rtw2,
					isnull(sum(tw3),0)as tw3,
					isnull(sum(rtw3),0)as rtw3,
					isnull(sum(tw4),0)as tw4,
					isnull(sum(rtw4),0)as rtw4 FROM ( 
						select * from b_pptk_spj WHERE kd_skpd = ? and  kode+'.'+kd_rek6 in (".$in_string.")   
					)z
					group by kd_skpd,nm_skpd,kode,nama";
		}else{
			$sql = "SELECT kd_skpd+kode as urut,kd_skpd,nm_skpd,kode,nama,
					isnull(sum(anggaran),0) as anggaran,
					isnull(sum(tw1),0)as tw1,
					isnull(sum(rtw1),0)as rtw1,
					isnull(sum(tw2),0)as tw2,
					isnull(sum(rtw2),0)as rtw2,
					isnull(sum(tw3),0)as tw3,
					isnull(sum(rtw3),0)as rtw3,
					isnull(sum(tw4),0)as tw4,
					isnull(sum(rtw4),0)as rtw4 FROM ( 
						select * from b_pptk_all WHERE kd_skpd = ? and  kode+'.'+kd_rek6 in (".$in_string.")   
					)z
					group by kd_skpd,nm_skpd,kode,nama";
		}
		
					
				$query = $this->db->query($sql, $params);
				// if($query->num_rows() > 0){
					return $query->result();
				// }else{
					// return $data;
				// }
				
	
	}

	function get_skpd(){
		$data = array();
		$this->db->select('*');
        $this->db->where('left(kd_skpd,17)','4.01.0.00.0.00.01');	
        $this->db->from('ms_skpd');
		$hasil = $this->db->get();
		if($hasil->num_rows() > 0){
			return $hasil->result();
			//return $hasil->row();
		}else{
			return $data;
		}
	}

	function get_dashboard(){
		$data = array();
		$this->db->select('*');
        $this->db->from('b_dashboard');
		$hasil = $this->db->get();
		if($hasil->num_rows() > 0){
			return $hasil->result();
			//return $hasil->row();
		}else{
			return $data;
		}
	}

	function get_realisasifisik($skpd,$nbulan,$jns_ang){
		$data = array();
		$hasil = $this->db->query("exec realisasi_fisik_sekda '$skpd','$nbulan','$jns_ang'");
		if($hasil->num_rows() > 0){
			return $hasil->result();
			//return $hasil->row();
		}else{
			return $data;
		}
	}

	function get_spjfungsional($lcskpd,$nbulan,$jns_ang){
		$data = array();
		$att = "exec spj_skpd '$lcskpd','$nbulan','$jns_ang'";
        $hasil = $this->db->query($att);
		return $hasil->result();

	}


	function get_spjterima($lcskpd,$bulan,$kd_ang){
		// WHERE
				$thn_ang = 2022;
				$kon_terimaini = " (month(a.tgl_terima)= '$bulan' AND year(a.tgl_terima) = '$thn_ang')";
				// $kon_terimaini_ppkd = " (month(b.tgl_sts)= '$bulan' AND year(b.tgl_sts) = '$thn_ang')";
				$kon_keluarini = " (month(a.tgl_sts)= '$bulan' AND year(a.tgl_sts) = '$thn_ang')";
				// $kon_keluarini_ppkd = " (month(b.tgl_sts)= '$bulan' AND year(b.tgl_sts) = '$thn_ang')";
				$kon_terimalalu = " (month(a.tgl_terima)< '$bulan' AND year(a.tgl_terima) = '$thn_ang')";
				// $kon_terimalalu_ppkd = " (month(b.tgl_sts)< '$bulan' AND year(b.tgl_sts) = '$thn_ang')";
				$kon_keluarlalu = " (month(a.tgl_sts)< '$bulan' AND year(a.tgl_sts) = '$thn_ang')";
				// $kon_keluarlalu_ppkd = " (month(b.tgl_sts)< '$bulan' AND year(b.tgl_sts) = '$thn_ang')";
		// END WHERE


		$att = "SELECT a.kd_skpd, a.kd_skpd as kd_sub_kegiatan, '9999' kode, 'JUMLAH' nama, a.ang, 
		a.anggaran, isnull(terima_ini,0) as terima_ini, 
		isnull(terima_lalu,0) terima_lalu, isnull(keluar_ini,0) keluar_ini,
		isnull(keluar_lalu,0) keluar_lalu 
 from ( 

	 -- ------------------asasas
	SELECT left(kd_skpd,len('$lcskpd')) as kd_skpd,sum(ang)as ang,sum(anggaran)as anggaran,
	sum(terima_ini)as terima_ini,sum(terima_lalu)as terima_lalu,sum(keluar_ini)as keluar_ini,sum(keluar_lalu)as keluar_lalu from(

	SELECT z.kd_skpd, z.kd_sub_kegiatan, left(z.kd_rek6,2) kd_rek, SUM(z.nilai) AS ang, 
	SUM(z.nilai) AS anggaran, 
	((SELECT isnull(SUM(a.nilai),0) nilai 
	FROM tr_terima a WHERE left(a.kd_rek6,2)=left(z.kd_rek6,2) and a.kd_skpd=z.kd_skpd 
	and $kon_terimaini)+
	(SELECT isnull(SUM(a.nilai),0) nilai 
	FROM tr_terima_blud a WHERE left(a.kd_rek5,2)=left(z.kd_rek6,2) and a.kd_skpd=z.kd_skpd 
	and $kon_terimaini)
	-
	(SELECT ISNULL(SUM(b.rupiah),0) nilai FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b 
	ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
	WHERE left(b.kd_rek6,2)=left(z.kd_rek6,2) and a.kd_skpd=z.kd_skpd and jns_trans IN ('3')
	and $kon_keluarini
	)
	) AS terima_ini, 

	((SELECT isnull(SUM(a.nilai),0) FROM tr_terima a WHERE left(a.kd_rek6,2)=left(z.kd_rek6,2) 
	and a.kd_skpd=z.kd_skpd and $kon_terimalalu)+(SELECT isnull(SUM(a.nilai),0) FROM tr_terima_blud a WHERE left(a.kd_rek5,2)=left(z.kd_rek6,2) 
	and a.kd_skpd=z.kd_skpd and $kon_terimalalu)
	-
	(SELECT ISNULL(SUM(b.rupiah),0) nilai FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b 
	ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
	WHERE left(b.kd_rek6,2)=left(z.kd_rek6,2) and a.kd_skpd=z.kd_skpd and jns_trans IN ('3')
	and $kon_keluarlalu)
	) AS terima_lalu, 

	(SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b 
	ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
	WHERE left(b.kd_rek6,2)=left(z.kd_rek6,2) and a.kd_skpd=z.kd_skpd and left(b.kd_rek6,12)<>'410411010001' and 
	$kon_keluarini)
	
	+ --test
	
	(SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b 
	ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
	WHERE left(b.kd_rek5,2)=left(z.kd_rek6,2) and a.kd_skpd=z.kd_skpd and
	$kon_keluarini) AS keluar_ini, 


	
	(SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b 
	ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
	WHERE left(b.kd_rek6,2)=left(z.kd_rek6,2) and a.kd_skpd=z.kd_skpd
	and $kon_keluarlalu)
	+
	
	(SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b 
	ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
	WHERE left(b.kd_rek5,2)=left(z.kd_rek6,2) and a.kd_skpd=z.kd_skpd
	and $kon_keluarlalu) AS keluar_lalu 
	
	FROM trdrka_pend z WHERE left(z.kd_skpd,len('$lcskpd'))='$lcskpd'  and z.jns_ang='$kd_ang'
	and left(z.kd_rek6,1)='4' and right(z.kd_sub_kegiatan,5)='00.04'
	GROUP BY z.kd_skpd, z.kd_sub_kegiatan, left(z.kd_rek6,2))zzz group by left(kd_skpd,len('$lcskpd')) )a 

			
			UNION ALL
			SELECT a.kd_skpd, a.kd_sub_kegiatan, b.kd_rek2 kode, b.nm_rek2 nama, a.ang, 
		a.anggaran, isnull(terima_ini,0) as terima_ini, 
		isnull(terima_lalu,0) terima_lalu, isnull(keluar_ini,0) keluar_ini,
		isnull(keluar_lalu,0) keluar_lalu 
 from ( 

	 -- ------------------asasas
	SELECT left(kd_skpd,len('$lcskpd')) as kd_skpd,kd_sub_kegiatan,kd_rek,sum(ang)as ang,sum(anggaran)as anggaran,
	sum(terima_ini)as terima_ini,sum(terima_lalu)as terima_lalu,sum(keluar_ini)as keluar_ini,sum(keluar_lalu)as keluar_lalu from(

	SELECT z.kd_skpd, z.kd_sub_kegiatan, left(z.kd_rek6,2) kd_rek, SUM(z.nilai) AS ang, 
	SUM(z.nilai) AS anggaran, 
	((SELECT isnull(SUM(a.nilai),0) nilai 
	FROM tr_terima a WHERE left(a.kd_rek6,2)=left(z.kd_rek6,2) and a.kd_skpd=z.kd_skpd 
	and $kon_terimaini)+
	(SELECT isnull(SUM(a.nilai),0) nilai 
	FROM tr_terima_blud a WHERE left(a.kd_rek5,2)=left(z.kd_rek6,2) and a.kd_skpd=z.kd_skpd 
	and $kon_terimaini)
	-
	(SELECT ISNULL(SUM(b.rupiah),0) nilai FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b 
	ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
	WHERE left(b.kd_rek6,2)=left(z.kd_rek6,2) and a.kd_skpd=z.kd_skpd and jns_trans IN ('3')
	and $kon_keluarini
	)
	) AS terima_ini, 

	((SELECT isnull(SUM(a.nilai),0) FROM tr_terima a WHERE left(a.kd_rek6,2)=left(z.kd_rek6,2) 
	and a.kd_skpd=z.kd_skpd and $kon_terimalalu)+(SELECT isnull(SUM(a.nilai),0) FROM tr_terima_blud a WHERE left(a.kd_rek5,2)=left(z.kd_rek6,2) 
	and a.kd_skpd=z.kd_skpd and $kon_terimalalu)
	-
	(SELECT ISNULL(SUM(b.rupiah),0) nilai FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b 
	ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
	WHERE left(b.kd_rek6,2)=left(z.kd_rek6,2) and a.kd_skpd=z.kd_skpd and jns_trans IN ('3')
	and $kon_keluarlalu)
 --    -
 --    (SELECT ISNULL(SUM(b.rupiah),0) nilai FROM trhkasin_blud a INNER JOIN trdkasin_blud b 
 --    ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
 --    WHERE left(b.kd_rek5,2)=left(z.kd_rek6,2) and a.kd_skpd=z.kd_skpd and jns_trans IN ('4')
 --    and $kon_keluarlalu
	) AS terima_lalu, 

	(SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b 
	ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
	WHERE left(b.kd_rek6,2)=left(z.kd_rek6,2) and a.kd_skpd=z.kd_skpd and left(b.kd_rek6,12)<>'410411010001' and 
	$kon_keluarini)
	
	+ --test
	
	(SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b 
	ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
	WHERE left(b.kd_rek5,2)=left(z.kd_rek6,2) and a.kd_skpd=z.kd_skpd and
	$kon_keluarini) AS keluar_ini, 


	
	(SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b 
	ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
	WHERE left(b.kd_rek6,2)=left(z.kd_rek6,2) and a.kd_skpd=z.kd_skpd
	and $kon_keluarlalu)
	+
	
	(SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b 
	ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
	WHERE left(b.kd_rek5,2)=left(z.kd_rek6,2) and a.kd_skpd=z.kd_skpd
	and $kon_keluarlalu) AS keluar_lalu 
	
	FROM trdrka_pend z WHERE left(z.kd_skpd,len('$lcskpd'))='$lcskpd'  and z.jns_ang='$kd_ang'
	and left(z.kd_rek6,1)='4' and right(z.kd_sub_kegiatan,5)='00.04'
	GROUP BY z.kd_skpd, z.kd_sub_kegiatan, left(z.kd_rek6,2))zzz group by left(kd_skpd,len('$lcskpd')),kd_sub_kegiatan,kd_rek )a 
	left join (select kd_rek2, nm_rek2 from ms_rek2) b on a.kd_rek=b.kd_rek2 

			 UNION ALL --asasasas

 select a.kd_skpd, a.kd_sub_kegiatan, b.kd_rek5 kode, b.nm_rek5 nama, a.ang, a.anggaran, isnull(terima_ini,0) as terima_ini, isnull(terima_lalu,0) terima_lalu,
 isnull(keluar_ini,0) keluar_ini,isnull(keluar_lalu,0) keluar_lalu  
 from (
 SELECT left(kd_skpd,len('$lcskpd')) as kd_skpd,kd_sub_kegiatan,kd_rek,sum(ang)as ang,sum(anggaran)as anggaran,
	sum(terima_ini)as terima_ini,sum(terima_lalu)as terima_lalu,sum(keluar_ini)as keluar_ini,sum(keluar_lalu)as keluar_lalu from(
 SELECT z.kd_skpd, z.kd_sub_kegiatan, left(z.kd_rek6,8) kd_rek, SUM(z.nilai) AS ang, 
 SUM(z.nilai) AS anggaran,
 ((SELECT isnull(SUM(a.nilai),0) nilai FROM tr_terima a 
			 WHERE left(a.kd_rek6,8)=left(z.kd_rek6,8) and a.kd_skpd=z.kd_skpd and $kon_terimaini)+
			 (SELECT isnull(SUM(a.nilai),0) nilai FROM tr_terima_blud a 
			 WHERE left(a.kd_rek5,8)=left(z.kd_rek6,8) and a.kd_skpd=z.kd_skpd and $kon_terimaini)  
			 -
			 (SELECT ISNULL(SUM(b.rupiah),0) FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b 
			 ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
			 WHERE left(b.kd_rek6,8)=left(z.kd_rek6,8) and a.kd_skpd=z.kd_skpd and jns_trans IN ('3') and $kon_keluarini)) AS terima_ini,
 ((SELECT isnull(SUM(a.nilai),0) FROM tr_terima a 
			WHERE left(a.kd_rek6,8)=left(z.kd_rek6,8) and a.kd_skpd=z.kd_skpd 
			and $kon_terimalalu) +

			(SELECT isnull(SUM(a.nilai),0) FROM tr_terima_blud a 
			WHERE left(a.kd_rek5,8)=left(z.kd_rek6,8) and a.kd_skpd=z.kd_skpd 
			and $kon_terimalalu) 
			-
			 (SELECT ISNULL(SUM(b.rupiah),0) FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b 
			 ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
			 WHERE left(b.kd_rek6,8)=left(z.kd_rek6,8) and a.kd_skpd=z.kd_skpd and jns_trans IN ('3') and $kon_keluarlalu)
			 -- -
			 -- (SELECT ISNULL(SUM(b.rupiah),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b 
			 -- ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
			 -- WHERE left(b.kd_rek5,8)=left(z.kd_rek6,8) and a.kd_skpd=z.kd_skpd and jns_trans IN ('4') and $kon_keluarlalu)
			 ) AS terima_lalu,


		(SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b 
			 ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
			 WHERE left(b.kd_rek6,8)=left(z.kd_rek6,8) and a.kd_skpd=z.kd_skpd and $kon_keluarini)
			 + (SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b 
			 ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
			 WHERE left(b.kd_rek5,8)=left(z.kd_rek6,8) and a.kd_skpd=z.kd_skpd and $kon_keluarini)  AS keluar_ini, 

		(SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b 
			 ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
			 WHERE left(b.kd_rek6,8)=left(z.kd_rek6,8) and a.kd_skpd=z.kd_skpd
			 and $kon_keluarlalu)+
			 (SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b 
			 ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
			 WHERE left(b.kd_rek5,8)=left(z.kd_rek6,8) and a.kd_skpd=z.kd_skpd
			 and $kon_keluarlalu) AS keluar_lalu 

 FROM trdrka_pend z WHERE left(z.kd_skpd,len('$lcskpd'))='$lcskpd'  and z.jns_ang='$kd_ang'
 and left(z.kd_rek6,1)='4' and right(z.kd_sub_kegiatan,5)='00.04' 
 GROUP BY z.kd_skpd, z.kd_sub_kegiatan, left(z.kd_rek6,8) 
 )zzz group by left(kd_skpd,len('$lcskpd')),kd_sub_kegiatan,kd_rek
 )a
 left join 
 (select kd_rek5, nm_rek5 from ms_rek5) b
 on a.kd_rek=b.kd_rek5

 UNION ALL --bbbbbbbbb
  SELECT left(kd_skpd,len('$lcskpd')) as kd_skpd,kd_sub_kegiatan,kode, nama,sum(ang)as ang,sum(anggaran)as anggaran,
	sum(terima_ini)as terima_ini,sum(terima_lalu)as terima_lalu,sum(keluar_ini)as keluar_ini,sum(keluar_lalu)as keluar_lalu from(

 SELECT z.kd_skpd, z.kd_sub_kegiatan, z.kd_rek6 kode, z.nm_rek6 nama, 
		SUM(z.nilai) AS ang, SUM(z.nilai) AS anggaran, 
		((SELECT isnull(SUM(a.nilai),0) FROM tr_terima a 
			 WHERE a.kd_rek6=z.kd_rek6 and a.kd_skpd=z.kd_skpd and $kon_terimaini)+
			 (SELECT isnull(SUM(a.nilai),0) FROM tr_terima_blud a 
			 WHERE left(a.kd_rek5,12)=z.kd_rek6 and a.kd_skpd=z.kd_skpd and $kon_terimaini)  
			 -
			 (SELECT ISNULL(SUM(b.rupiah),0) FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b 
			 ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
			 WHERE b.kd_rek6=z.kd_rek6 and a.kd_skpd=z.kd_skpd and jns_trans in ('3') and $kon_keluarini)) AS terima_ini, 
		
		(
		 (SELECT isnull(SUM(a.nilai),0) FROM tr_terima a 
			WHERE a.kd_rek6=z.kd_rek6 and a.kd_skpd=z.kd_skpd 
			and $kon_terimalalu)
			+
			(SELECT isnull(SUM(a.nilai),0) FROM tr_terima_blud a 
			WHERE left(a.kd_rek5,12)=z.kd_rek6 and a.kd_skpd=z.kd_skpd 
			and $kon_terimalalu)
			-
			 (SELECT ISNULL(SUM(b.rupiah),0) FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b 
			 ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
			 WHERE b.kd_rek6=z.kd_rek6 and a.kd_skpd=z.kd_skpd and jns_trans in ('3') and $kon_keluarlalu)
		 --    -
		 -- 	(SELECT ISNULL(SUM(b.rupiah),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b 
		 -- 	ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
		 -- 	WHERE b.kd_rek5=z.kd_rek6 and a.kd_skpd=z.kd_skpd and jns_trans in ('4') and $kon_keluarlalu)
			 
			 ) AS terima_lalu,


		(SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b 
			 ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
			 WHERE b.kd_rek6=z.kd_rek6 and a.kd_skpd=z.kd_skpd and $kon_keluarini)
			 +(SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b 
			 ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
			 WHERE left(b.kd_rek5,12)=z.kd_rek6 and a.kd_skpd=z.kd_skpd and $kon_keluarini) AS keluar_ini, 


		(SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b 
			 ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
			 WHERE b.kd_rek6=z.kd_rek6 and a.kd_skpd=z.kd_skpd
			 and $kon_keluarlalu)
			 +
			 
			 (SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b 
			 ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd 
			 WHERE left(b.kd_rek5,12)=z.kd_rek6 and a.kd_skpd=z.kd_skpd
			 and $kon_keluarlalu) AS keluar_lalu 

 FROM trdrka_pend z WHERE left(z.kd_skpd,len('$lcskpd'))='$lcskpd'  and z.jns_ang='$kd_ang'
 and left(z.kd_rek6,1)='4' and right(z.kd_sub_kegiatan,5)='00.04' 
 GROUP BY z.kd_skpd, z.kd_sub_kegiatan, z.kd_rek6,z.nm_rek6 
 )zzz group by left(kd_skpd,len('$lcskpd')),kd_sub_kegiatan,kode,nama
 
			 order by kd_skpd,kode";
        $hasil = $this->db->query($att);
		return $hasil->result();

	}



	

	
	function get_lpj(){
		$data = array();
		$this->db->select('*');
        $this->db->from('b_lpj');
		$this->db->order_by('kd_skpd');
		$hasil = $this->db->get();
		if($hasil->num_rows() > 0){
			return $hasil->result();
			//return $hasil->row();
		}else{
			return $data;
		}
	}

	function get_jnsang(){
		$data = array();
		$this->db->select('kode,nama');
        $this->db->where('status_aktif',1);	
        $this->db->from('tb_status_anggaran');
		$hasil = $this->db->get();
		if($hasil->num_rows() > 0){
			return $hasil->result();
			//return $hasil->row();
		}else{
			return $data;
		}
	}

	function get_subkegiatan($skpd){
		$data = array();
		$this->db->select('kd_sub_kegiatan,nm_sub_kegiatan');
        $this->db->where('kd_skpd',$skpd);	
        $this->db->from('trdrka');
		$this->db->group_by('kd_sub_kegiatan,nm_sub_kegiatan');
		$this->db->order_by('kd_sub_kegiatan,nm_sub_kegiatan');
		$hasil = $this->db->get();
		if($hasil->num_rows() > 0){
			return $hasil->result();
			//return $hasil->row();
		}else{
			return $data;
		}
	}

	function get_rekening($skpd,$subkegiatan){
		$data = array();
		$this->db->select('kd_rek6,nm_rek6');
        $this->db->where('kd_skpd',$skpd);	
		$this->db->where('kd_sub_kegiatan',$subkegiatan);	
        $this->db->from('trdrka');
		$this->db->group_by('kd_rek6,nm_rek6');
		$this->db->order_by('kd_rek6,nm_rek6');
		$hasil = $this->db->get();
		if($hasil->num_rows() > 0){
			return $hasil->result();
			//return $hasil->row();
		}else{
			return $data;
		}
	}

	function get_sp2d($skpd,$subkegiatan,$tabel,$ky,$ky2){
		$data = array();
		$this->db->select('*');
        $this->db->where($ky, $skpd);
		$this->db->where($ky2, $subkegiatan);
        $this->db->from($tabel);
        $this->db->order_by('tgl_terbit', 'DESC');
		$hasil = $this->db->get();
		if($hasil->num_rows() > 0){
			return $hasil->result();
			//return $hasil->row();
		}else{
			return $data;
		}
	}

	
    
}
