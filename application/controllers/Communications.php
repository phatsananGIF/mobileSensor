<?php
class Communications extends CI_Controller {

    function __construct() {      
        parent::__construct();
    }
    
    public function index(){

        $location=array(
            ''=>'',
            'us'=>'เหนือน้ำ',
            'ds'=>'ท้ายน้ำ',
            'usr'=>'เหนือ ปตร.ฝั่งขวา',
            'usl'=>'เหนือ ปตร.ฝั่งซ้าย',
            'dsr'=>'ท้าย ปตร.ฝั่งขวา',
            'dsl'=>'ท้าย ปตร.ฝั่งซ้าย'
        
        );
        
        //query sites
        $query = ("SELECT ss_devices.id as devicesID, siteid, location, sensor, sitecode, sitename 
                    FROM ss_devices
                    LEFT JOIN ss_sites ON ss_devices.siteid=ss_sites.id
                    WHERE sensor='wl'
                    ORDER BY ss_sites.lined ASC, ss_devices.location DESC"); 
        $sites = $this->db->query($query);
        $sites = $sites->result_array();

        
        foreach($sites as $site){
            $querysensor = ("SELECT id as id_sensor, location, sensor_dt, sensor_value, offset
                        FROM ss_sensor WHERE siteid='".$site['sitecode']."' and sensor_type='".$site['sensor']."'
                        and location='".$site['location']."' ORDER BY sensor_dt DESC LIMIT 1");
            $sitedata = $this->db->query($querysensor);

            //print_r($this->db->last_query());

            if($sitedata->num_rows()!=0){
                $sitedata = $sitedata->row_array();                
                $sitedata['name_location'] = $location[strtolower($sitedata['location'])];
                $sitedata['network_label'] = $this->content_label($site['devicesID'], 'networkstatus', "ระบบสื่อสาร");
                $network_status = $this->network_level($sitedata['sensor_dt']);



                $cal_value = ['sitecode'=>$site['sitecode'],'us'=>0,'ds'=>0];

                //ดึง us
                $queryUs = (" SELECT * , sensor_value + offset as sum_val FROM `ss_sensor`
                                WHERE siteid='".$site['sitecode']."' and sensor_type='".$site['sensor']."' and location='us'
                                ORDER BY sensor_dt DESC LIMIT 1 ");
                $siteUs = $this->db->query($queryUs);

                if($siteUs->num_rows()!=0){
                    $siteUs = $siteUs->row_array();
                    $cal_value['us'] = number_format($siteUs['sensor_value'] + $siteUs['offset'],2);
                }
                

                //ดึง ds
                $queryDs = (" SELECT * , sensor_value + offset as sum_val FROM `ss_sensor`
                                WHERE siteid='".$site['sitecode']."' and sensor_type='".$site['sensor']."' and location='ds'
                                ORDER BY sensor_dt DESC LIMIT 1 ");
                $siteDs = $this->db->query($queryDs);

                if($siteDs->num_rows()!=0){
                    $siteDs = $siteDs->row_array();
                    $cal_value['ds'] = number_format($siteDs['sensor_value'] + $siteDs['offset'],2);
                }

                


                $data_cal = $this->runcal($cal_value);
                    if($data_cal['sitecode']!='') { 
                        $value=$data_cal['value'];
                    }else{
                        $value=$cal_value[$site['location']];
                    }



                
                //โหลดข้อมูล sensor status
                $queryalarm = ("SELECT * FROM ss_alarm_lv WHERE deviceid = '".$site['devicesID']."' ORDER BY lv ASC");
                $alarm = $this->db->query($queryalarm);
                
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

                $sensor_label= $this->content_label($site['devicesID'], 'sensorstatus', "ระดับน้ำ");
                $datasensor = ['sensor_label'=> $sensor_label, 'sensor_status'=>$label, 'sensor_color'=>$color, 'sensor_cal'=>number_format($value,2) ];
                $sensorall[] = array_merge($site, $sitedata, $network_status, $datasensor);

            }//end if

        }//end foreach site



        /*
        echo '<pre>';
        print_r($data_cal);
        echo  '</pre>';
        */

    




        $data['sensorall'] = $sensorall;
        
        
        $this->load->view('layout/header_view');
        $this->load->view('communications_view',$data);
        $this->load->view('layout/footer_view');
        

        

    }// fn.index


    public function content_label($deviceid, $tagcontent, $label=""){
        
        $query = ("SELECT label FROM ss_content_label WHERE deviceid='$deviceid' and tagcontent='$tagcontent'");
        $result = $this->db->query($query);

        if($result->num_rows()==1){
            $row = $result->row_array(); 
            if(empty($row['label'])==FALSE) $label=$row['label'];
        }
        return $label;
    }//fn.content_label



    public function network_level($dt){
        $today=strtotime(date('Y-m-d H:i:s'));
        $update=strtotime($dt);
        $sec=$today - $update;
        $lv=1;
        if((int)$sec < 300) $lv=['network_level'=>'ปกติ','network_color'=>'green'];
        if((int)$sec >=900) $lv=['network_level'=>'ระวัง','network_color'=>'orange'];
        if((int)$sec >=1200) $lv=['network_level'=>'ขัดข้อง','network_color'=>'red'];
        return $lv;
    }//fn.network_level




    public function runcal($data){
       
        $Q=0;
        $SITECODE="";
        $formula="";
        switch ($data['sitecode']) {
            case 'A001': //TEST
           case 'PPN02':
                   $SITECODE=$data['sitecode'];
                   $C=0.65;
                   $W=40;
                   $g=9.81;
                   $D=$data['us']-(+21.5);
                   if((floatval($data['ds'])<21.5) and ($D >0))  {
                       //กรณีที่ 1 ระดับน้ำท้ายฝายต่ำกว่าระดับสันฝาย (กรณี D เป็นค่าบวก และระดับน้ำท้ายฝายต่ำกว่าระดับสันฝาย)
                        $Q=(2/3)*$C*$W*pow($D,1.5)*SQRT(2*$g);
                        $formula=$data['us'] . ";$Q=(2/3)*$C*$W*pow($D,1.5)*SQRT(2*$g)";
                   }
                   if((floatval($data['us'])<21.5) and ($D <=0))  {
                       //กรณีที่ 2 ระดับน้ำหน้าฝาย ต่ำกว่าระดับสันฝาย (กรณี D เป็นค่าลบ และระดับน้ำท้ายฝายต่ำกว่าระดับสันฝาย)
                        $Q=0;
                       $formula="2"; 
                   }
                   
                   if(floatval($data['ds'])>21.5)  {
                       //กรณีที่ 3 ระดับน้ำท้ายฝายสูงกว่าระดับสันฝาย
                       $H=floatval($data['us']) - floatval($data['ds']);
                       $Q=$C*$W*sqrt(2*$g*$H)*($D-$H/3);
                       $formula="3";
                   }
                   break;
           case 'PPN01':
                       $SITECODE=$data['sitecode'];
                       $C=0.1209;
                       $D=14.704;
                       $E=450.65;
                       $value=floatval($data['us']);
                       $V=pow($value,2);
           
                       $Q=($C*$V)-$D*$value+$E;
                       $formula="($C*$V)-$D*$value+$E";
                   
                   break;
           default:
               //default wl
               $Q=0;	
         }
        return array('sitecode'=>"$SITECODE",'value'=>$Q,'formula'=>$formula);

    }//fn.runcal




}//end class