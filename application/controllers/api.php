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




	// STATISTIK
	function apbd_get(){
		ini_set('max_execution_time', -1); 
		ini_set('memory_limit',-1);
		$pemda 		= $this->get('kd_pemda');
		$anggaran 	= $this->get('jns_ang');
		$tahun 		= $this->get('tahun');

        if($pemda=='' && $anggaran=''){
			$this->response(array('error' => 'SKPD dan Jenis Anggaran Belum Ada'), 400); 
		}else {
			
			$query = $this->mapi->get_apbd($pemda,$anggaran,$tahun,'statistik_apbd');
			if($query) {
				$this->response(array(
					'status' => true,
					'message' => 'SUKSES',
					'data' => $query,
					), 200);                
			} else {
				
				$this->response(array(
					'status' => false,
					'message' => 'Data tidak ditemukan',
					'data' => '',
					), 404);  
			}
		}
	}

	function lra_get(){
		ini_set('max_execution_time', -1); 
		ini_set('memory_limit',-1);
		$pemda 		= $this->get('kd_pemda');
		$bulan 		= $this->get('bulan');
		$tahun 		= $this->get('tahun');

        if($pemda=='' && $bulan=''){
			$this->response(array('error' => 'SKPD dan Bulan Belum Ada'), 400); 
		}else {
			
			$query = $this->mapi->get_lra($pemda,$bulan,$tahun);
			if($query) {
				$this->response(array(
					'status' => true,
					'message' => 'SUKSES',
					'data' => $query,
					), 200);                
			} else {
				
				$this->response(array(
					'status' => false,
					'message' => 'Data tidak ditemukan',
					'data' => '',
					), 404);  
			}
		}
	}

	
    
}