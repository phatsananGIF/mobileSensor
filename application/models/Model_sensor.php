<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_sensor extends CI_Model {
	
	public function __construct() {
		parent::__construct();
		
	}     

	public function offset_update_old($data)
	{
		 $id=$data['id'];
		 $offset=$data['offset'];
		 $where=array('sitecode'=>"$id",'offset'=>"$offset");
		 $this->db->where($where);
		 $query=$this->db->get('ss_sites');
		 if($query->num_rows()==0)
		 {
		 	$data=array(
				'offset'=>$offset,
			);
			
			$this->db->where(array('sitecode'=>"$id"))->update('ss_sites',$data);
		 }
	}


  public function offset_update($data)
  {
  	  $input_code=$data['id'];
	  $ipaddress=$data['ipaddress'];
	  $input_value=$data['sensor'];
	  $location=$data['location'];
	  
	  $where=array(
	  	'location'=>"$location",
	  	'input_value'=>"$input_value",
	    'ipaddress'=>"$ipaddress",
	    'input_code'=>"$input_code",
	  );
	  
	   $this->db->where($where)->where(array('water_offset'=>$data['offset']));
	   $query=$this->db->get('ss_devices');
		 if($query->num_rows()==0)
		 {
		   $update=array(
	  			'water_offset'=>$data['offset'],
		  	);
			
			  $this->db->where($where)->update('ss_devices',$update);
		 } 
  }

  public function sensor_lastinfo($siteid,$location,$sensor,$ip)
  {
  	   $SQL="SELECT * FROM `ss_sensor` WHERE 
  	   				siteid = '$siteid' and location = '$location' 
  	   				and sensor_type = '$sensor' 
  	   				and ipaddress = '$ip' 
  	   				order by sensor_dt desc limit 1";
		$q=$this->db->query($SQL);
		
		$row=array(
			'id'=>'',
			'siteid'=>$siteid,
			'location'=>$location,
			'sensor_type'=>$sensor,
			'sensor_value'=>0,
			'ipaddress'=>$ip,
			'offset'=>0,
			'sensor_dt'=>'',
		);
		
		if($q->num_rows()==1) {
			$row=$q->row_array();
		} 
		return $row;
  }

  

 public function sensor_today($siteid,$location,$sensor,$ip,$start,$end)
  {
  	   
	$divider_rq = 4;
	switch ($siteid) {
		case 'PPN08':
		case 'PPN09':
		case 'PPN10':
		case 'PPN11':
		case 'PPN12': $divider_rq = 2; break;
		
	}
  	   $SQL="SELECT sum(sensor_value)/$divider_rq  as sumvalue  , max(sensor_dt) as dt FROM `ss_sensor` WHERE 
  	   				siteid = '$siteid' and location = '$location' 
  	   				and sensor_type = '$sensor' 
  	   				and ipaddress = '$ip' 
  	   				and  sensor_dt between '$start' and '$end' 
  	   				group by  siteid , location , sensor_type";
		
		//echo "$SQL\n";
		$q=$this->db->query($SQL);
		
		$row=array(
			'sumvalue'=>0,
			'dt'=>0,
		);
		
		if($q->num_rows()==1) {
			$row=$q->row_array();
		} 
		return $row;
  }

  
  
 public function sensor_today_now($siteid,$location,$sensor,$ip)
  {
			//น้ำฝนปัจจุบัน
  			$start=date('Y-m-d 05:59:59');
			$end=date('Y-m-d H:i:s');	
			
			if(strtotime(date('Y-m-d H:i:s')) < strtotime(date('Y-m-d 05:59:59'))) {
				
				 if((int)date('His',strtotime($end)) <= 55959 ){
					// ตี 1 - 5.59.59
					$end=$start;
					$start=date('Y-m-d H:i:s',strtotime($start . " -1 day"));
				 }else{
					  $start= date('Y-m-d H:i:s',strtotime(date('Y-m-d 05:59:59') . "- 1day"));	
				 }
				
			}
			
  	        $divider_rq = 4;
       	       switch ($siteid) {
                case 'PPN08':
                case 'PPN09':
                case 'PPN10':
                case 'PPN11':
                case 'PPN12': $divider_rq = 2; break;
	
        	}

  	   $SQL="SELECT sum(sensor_value)/$divider_rq  as sumvalue  , max(sensor_dt) as dt FROM `ss_sensor` WHERE 
  	   				siteid = '$siteid' and location = '$location' 
  	   				and sensor_type = '$sensor' 
  	   				and ipaddress = '$ip' 
  	   				and  sensor_dt between '$start' and '$end' 
  	   				group by  siteid , location , sensor_type";
		
		//echo "<!-- $SQL //-->";
		$q=$this->db->query($SQL);
		
		$row=array(
			'sumvalue'=>0,
			'dt'=>0,
		);
		
		if($q->num_rows()==1) {
			$row=$q->row_array();
		} 
		return $row;
  }
  
function show_content_label($tagcontent,$deviceid,$label="")
{
	  $result=$this->db->where(array('deviceid'=>$deviceid,'tagcontent LIKE '=>$tagcontent))->get('ss_content_label');
	 if($result->num_rows()==1){
	 	$row=$result->row();
		 if(empty($row->label)==FALSE) $label=$row->label;
	 }
	 return $label;
}

function hide_content($tagcontent,$deviceid,$disable=0)
{
	 $result=$this->db->where(array('deviceid'=>$deviceid,'tagcontent LIKE '=>$tagcontent))->get('ss_content_label');
	 if($result->num_rows()==1){
	 	$row=$result->row();
		 if($row->disable==1) $disable=$row->disable;
	 }
	 return $disable;
}
  
  public function cal1($us,$us_lv,$ds,$ds_lv,$C=0.65,$W=40,$g=9.81)
  {
  		//D = ระดับน้ำหน้าฝาย - ระดับสันฝาย (+21.5 ม.รทก.)
  		//H = ผลต่างของระดับน้ำหน้าฝาย กับ ระดับน้ำท้ายฝาย
  		$D= 21.66 - (+21.5);
		$H= 21.66 - 15.20;
  		$G=2*$g;
  		$Q = pow((2/3)*$C*$W*$D,1.5)*sqrt($G);
		 
		//echo 2/3;
		echo  (2/3)*$C*$W*$D;
		//echo sqrt(2*9.81);
  }
  
 public function get_wl_5min($data)
 {
	 
	 $siteid=$data['siteid'];
	 $today=date('Y-m-d');	 
	 $SQL="SELECT siteid,
sum(if(ss_sensor.location=\"US\",ss_sensor.sensor_value,0)) + sum(if(ss_sensor.location=\"US\",ss_sensor.offset,0))  as us ,
sum(if(ss_sensor.location=\"DS\",ss_sensor.sensor_value,0)) +  sum(if(ss_sensor.location=\"DS\",ss_sensor.offset,0)) as ds ,
from_unixtime((unix_timestamp(`ss_sensor`.`sensor_dt`) - (unix_timestamp(`ss_sensor`.`sensor_dt`) % (5 * 60)))) AS `dt` 
FROM `ss_sensor`  where sensor_type = 'WL' and sensor_dt >= '${today} 00:00:00' and sensor_dt <= '${today} 23:59:59' and siteid = \"$siteid\" group by siteid ,(unix_timestamp(`ss_sensor`.`sensor_dt`) DIV 300) order by dt desc limit 1";
	
	$q=$this->db->query($SQL);
	
	$row=array(
			'siteid'=>$data['siteid'],
			'us'=>0,
			'ds'=>0,
			'dt'=>0,
		);
		
		if($q->num_rows()==1) {
			$row=$q->row_array();
		} 
		return $row;	
 }
  
  
}

/* End of file model_sensor.php */
/* Location: ./application/models/model_sensor.php */
