<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_gate extends CI_Model {
	

	public function __construct() {
		parent::__construct();
		
	}     

	public function status($id=0)
	{
		return rand(10,30) * 1.2;
	}
	
	
	public function get_value_gate($data)
	{
		$site=$data['siteid'];
		$dt=date('YmdHi',strtotime($data['dt']));
		$gate=$data['gate'];
		
		
		
		$SQL="SELECT  *  
FROM `ss_gate_value_json` where sitecode = \"$site\" and gate_input = \"$dt\" and gate_id = $gate ";


	
	$q=$this->db->query($SQL);
	
	$row=array(
			'gate_value'=>0,
		);
		
		if($q->num_rows()==1) {
			$row=$q->row_array();
		} 
		return $row;	
	}
}

/* End of file model_ipsm.php */
/* Location: ./application/models/model_ipsm.php */