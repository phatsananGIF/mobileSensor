<?php
 
class Model_alarm extends CI_Model { 
    public function __construct() {
		parent::__construct();
		
	}  

    public function alarm_level($value , $deviceids=[] ){

        $sensor_label="ระดับน้ำ";
        foreach( $deviceids as $device_id){
            //โหลดข้อมูล sensor status
            $queryalarm = ("SELECT * FROM ss_alarm_lv WHERE deviceid = '$device_id' ORDER BY lv ASC");
            $alarm = $this->db->query($queryalarm);

            //โหลด label
            $querylabel = ("SELECT label FROM ss_content_label WHERE deviceid='$device_id' and tagcontent='sensorstatus' ");
            $resultlabel = $this->db->query($querylabel);
    
            if($resultlabel->num_rows()==1){
                $row = $resultlabel->row_array(); 
                if(empty($row['label'])==FALSE) $sensor_label=$row['label'];
            }
 
        
        
            if($alarm->num_rows()!=0){

            
                $color="";
                $label="";
                foreach($alarm->result() as $row_status){
                    $op=$row_status->op;
                    $v=$row_status->value;
                    
                    if("$op"=="<"){
                        if( floatval($value) < floatval($v) ) {
                            $color=$row_status->color;
                            $label=$row_status->label;
                        } 
                    }
                    
                    if("$op"==">"){
                        if( floatval($value) > floatval($v) ) {
                            $color=$row_status->color;
                            $label=$row_status->label;
                        } 
                    }
                    
                    if("$op"=="<="){
                        if(floatval($value) <= floatval($v) ) {
                            $color=$row_status->color;
                            $label=$row_status->label;
                        } 
                    } 
                    
                    if("$op"==">="){
                        if(floatval($value) >= floatval($v) ) {
                            $color=$row_status->color;
                            $label=$row_status->label;
                        } 
                    }  
                    
                    if("$op"=="=="){
                        if(floatval($value) == floatval($v) ) {
                            $color=$row_status->color;
                            $label=$row_status->label;
                        }
                    }
                    
                    if("$op"=="!="){
                        if(floatval($value) != floatval($v) ) {
                            $color=$row_status->color;
                            $label=$row_status->label;
                        }
                    }

                }//end foreach alarm
                
                return array('sensor_label'=>$sensor_label,'sensor_status'=>$label,'sensor_color'=>$color);

            }//end if
        }//end foreach
       
        


    }//fn.alarm_level
    



    
	
}//end class