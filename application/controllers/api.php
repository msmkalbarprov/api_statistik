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







	// STATISTIK
	function apbd_get(){
		ini_set('max_execution_time', -1); 
		ini_set('memory_limit',-1);
		$pemda 		= $this->get('kd_pemda');
		$anggaran 	= $this->get('jns_ang');
		$tahun 		= $this->get('tahun');

        if($pemda=='' && $anggaran='' && $tahun=''){
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