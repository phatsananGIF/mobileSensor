<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_wq extends CI_Model {

	public function __construct() {
		parent::__construct();
		
    }  
    
    public function store($data)
    {
        //check dup
        $find = $data;
        unset($find['server_dt']);
        unset($find['do']);
        unset($find['ph']);
        unset($find['ec']);
        unset($find['tm']);
        $result=$this->db->get_where('ss_wq',$find);
	//echo $this->db->last_query();
        if( $result->num_rows() != 0 ) {
            //echo "dup";
        }else{
            $this->db->insert('ss_wq',$data);
        }
    }

    public function check_site($site,$sensor="wq")
    { //ยังไม่ได้ใช้
        $SQL="SELECT *
        FROM `view_devices2sites`
        WHERE `sitecode` = '$site' AND `sensor` = '$sensor' ";
        $query=$this->db->query($SQL);
        $result=false;
        if ( $query->num_rows() == 1 ){
            $result=true;
        }
        return $result;
    }


    public function get_last_by_site_location($data)
    {
        $site=$data['site'];
        $location=$data['location'];
     
        $SQL="SELECT * FROM `ss_wq` WHERE `site` = '$site' AND
             `location` = '$location'  ORDER BY `sensor_dt` DESC LIMIT 1";
        $query=$this->db->query($SQL);
        $row=(object)array(
            'do'=>0,
            'ph'=>0,
            'ec'=>0,
            'tm'=>0,
            'sensor_dt'=>'1977-01-01 00:00:00'
        );
        if($query->num_rows() == 1) {
            $row=$query->row();
        }    
        return $row;
    }
}
