<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
Class Mapi extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	

	// STATISTIK
	function get_apbd($pemda,$anggaran,$tahun,$tabel){
		$data = array();
		$this->db->select('*');
		$this->db->from($tabel);
        $this->db->where('kodedata', $anggaran);
		$this->db->where('kodesatker', $pemda);
		$this->db->where('tahun', $tahun);
		$hasil = $this->db->get();
		if($hasil->num_rows() > 0){
			return $hasil->result();
			//return $hasil->row();
		}else{
			return $data;
		}
	}

	function get_lra($pemda,$bulan,$tahun){
		$data = array();
		$this->db->select('*');
		$this->db->from('statistik_lra a');
        $this->db->where('bulan', $bulan);
		$this->db->where('tahun', $tahun);
		$this->db->where('kodesatker', $pemda);
		$hasil = $this->db->get();
		if($hasil->num_rows() > 0){
			return $hasil->result();
			//return $hasil->row();
		}else{
			return $data;
		}
	}

	
    
}
