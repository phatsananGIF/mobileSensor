<?php
class Home2 extends CI_Controller {

    function __construct() {      
        parent::__construct();
        $this->load->model('model_cal'); 
        $this->load->model('model_alarm');
        $this->load->model('model_sensor');
        $this->load->model('model_gate');
        $this->load->model('model_alarm');
        $this->load->model('model_wq'); 

    }
    
    public function index(){

        $data['data_all_sensor'] = $this->getdata_all();

        $this->load->view('layout/header_view');
        $this->load->view('home2_view',$data);
        $this->load->view('layout/footer_view');
        

    }// fn.index


    /**ดึงข้อมูลระบบสื่อสาร */ 
    public function getdata_com(){

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
                
                

                if(is_array($datasensor) && is_array($network_status) ){
                    $sensorall[] = array_merge($site, $sitedata, $network_status, $datasensor);
                }

            }else{
                $sitedata = ['name_location'=>'', 'network_label'=>'', 'network_color'=>'', 'network_level'=>''];
                $datasensor = ['sensor_label'=> '', 'sensor_status'=>'', 'sensor_color'=>'', 'sensor_cal'=>'', 'sensor_dt'=>''];
                $sensorall[] = array_merge($site, $sitedata, $datasensor);
            }

        }//end foreach site


        echo json_encode($sensorall);
        exit(); 
        
        

    }// fn.getdata_com




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
        $lv=['network_level'=>'ปกติ','network_color'=>'green'];
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






    /**ดึงข้อมูลน้ำทั้งหมด */
    public function getdata_all(){

        $location=array(
            ''=>'',
            'us'=>'เหนือน้ำ',
            'ds'=>'ท้ายน้ำ',
            'usr'=>'เหนือ ปตร.ฝั่งขวา',
            'usl'=>'เหนือ ปตร.ฝั่งซ้าย',
            'dsr'=>'ท้าย ปตร.ฝั่งขวา',
            'dsl'=>'ท้าย ปตร.ฝั่งซ้าย'
        
        );

        $status=array(
            3=>'red',
            2=>"orange",
            1=>"green",
            4=>"brown",
        );
        
        $status_sensor=array(
            4=>'วิกฤต',
            3=>'สูง',
            2=>'ปกติ',
            1=>'น้อย',
        );
        
        
        $status_network=array(
            5=>'',
            4=>'',
            3=>'ขัดข้อง',
            2=>'ระวัง',
            1=>'ปกติ',
        );

        

        //query sitecode
        $query = ("SELECT ss_devices.*, sitecode, sitename FROM (`ss_devices`) JOIN `ss_sites` ON `ss_devices`.`siteid` = `ss_sites`.`id` 
                    GROUP BY sitecode ORDER BY `sitecode` ASC"); 
        $data_sitecode = $this->db->query($query);

        foreach($data_sitecode->result_array() as $row){
            $data_all_site = "";
            //echo $row['sitecode'].' '.$row['sitename'];
            

            $sitecode = $row['sitecode'];

            //ประกาศตัวแปล ส่งค่าไปยังสูตร gate
            $data_pass_gate=array(
                'siteid'=>$row['sitecode'],
                'us'=>0,
                'ds'=>0,
                'gate'=>0,
                'D'=>0,
                'dt'=>0,
            );


            //wl
            $this->db->select('ss_devices.*,sitecode');
            $this->db->from('ss_devices');
            $this->db->join('ss_sites', 'ss_devices.siteid = ss_sites.id');
            $data_wl=$this->db->where(array(
                'sitecode'=>$row['sitecode'],
                'sensor'=>'wl',
            ))->order_by('location desc')->get();

            


            //Gate
			$this->db->select('*');
			$this->db->from('ss_devices');
			$this->db->join('ss_sites', 'ss_devices.siteid = ss_sites.id');
			$data_gate=$this->db->where(array(
				'sitecode'=>$row['sitecode'],
				'devtype'=>18,
            ))->order_by('location desc')->get();
            

            //wf ปริมาณน้ำ
			$this->db->select('ss_devices.*,sitecode,sitename');
			$this->db->from('ss_devices');
			$this->db->join('ss_sites', 'ss_devices.siteid = ss_sites.id');
			$data_wf=$this->db->where(array(
				'sitecode'=>$row['sitecode'],
				'devtype'=>12,
            ))->order_by('id asc , location desc')->get();

            
            //rq
			$this->db->select('*');
			$this->db->from('ss_devices');
			$this->db->join('ss_sites', 'ss_devices.siteid = ss_sites.id');
			$data_rq=$this->db->where(array(
				'sitecode'=>$row['sitecode'],
				'sensor'=>'rq',
            ))->order_by('location desc')->get();


            //wq
			$this->db->select('*');
			$this->db->from('ss_devices');
			$this->db->join('ss_sites', 'ss_devices.siteid = ss_sites.id');
			$data_wq=$this->db->where(array(
				'sitecode'=>$row['sitecode'],
				'sensor'=>'wq',
			))->order_by('location desc')->get();
            
            //--------------------------------------------------//





            //ระดับน้ำ wl
            $sitecode=$row['sitecode'];
            $cal_value=array('sitecode'=>"$sitecode",'us'=>0,'ds'=>0);//ประการตัวแปลส่งไปยัง อัตราการไหล
            foreach($data_wl->result() as $wl){
                $data_sensor=$this->model_sensor->sensor_lastinfo($wl->input_code,$wl->location,$wl->sensor,$wl->ipaddress);

                $textdisplay="ระดับน้ำ " . $location[$wl->location] ;
                if(empty($wl->textdisplay)==FALSE) $textdisplay=$wl->textdisplay;
                
                $cal_value[$wl->location]=number_format($data_sensor['sensor_value'] + $data_sensor['offset'],2);
                
                $data_all_site .= '<div>';
                $data_all_site .= $this->model_sensor->show_content_label('wl',$wl->id,"$textdisplay");
                $data_all_site .= ' <strong style="background-color:#fff; padding:0 3px; color:#001dff;">';
                $data_all_site .= number_format($data_sensor['sensor_value'] + $data_sensor['offset'],2);
                $data_all_site .= '</strong> ';
                $data_all_site .= 'มรทก.';
                $data_all_site .= '</div>';
                

            }//for-wl


            

            // ปริมาณการไหล
            if(($row['sitecode'] == "PPN04") or ( $row['sitecode'] == "PPN05")) {
            
                $_data=$this->model_sensor->get_wl_5min($data_pass_gate);

                
                $data_pass_gate['us']=$_data['us'];
                $data_pass_gate['ds']=$_data['ds'];
                $data_pass_gate['dt']=$_data['dt'];


                if($row['sitecode'] == "PPN04") $Q=$this->model_cal->cal_PPN04($data_pass_gate);
                if($row['sitecode'] == "PPN05") $Q=$this->model_cal->cal_PPN05($data_pass_gate);
                                            
                                    

                if(is_nan($Q)) { 
                    $Q="N/A";
                }else{
                    $Q=number_format($Q,2);
                }


                $data_all_site .= '<div>';
                $data_all_site .= 'ปริมาณการไหล';
                $data_all_site .= ' <strong style="background-color:#fff; padding:0 3px; color:#001dff;">';
                $data_all_site .= $Q;
                $data_all_site .= '</strong> ';
                $data_all_site .= 'ลบ.ม./วินาที';
                $data_all_site .= '</div>';
            
        
            }//if-ปริมาณการไหล



            //ปริมาณน้ำผ่าน ประตู
/*            if(	$data_pass_gate['siteid']=="PPN02") {
					
                $_data=$this->model_sensor->get_wl_5min($data_pass_gate);

                
                $data_pass_gate['us']=$_data['us'];
                $data_pass_gate['ds']=$_data['ds'];
                $data_pass_gate['dt']=$_data['dt'];

                
                foreach($data_gate->result() as $gw){

                    $gw_all=$gw->gw_value;
                    for($i=1;$i<=$gw_all;$i++){
                        //ดึงค่าประตูล่าสุด	
                        $data_pass_gate['gate']=$i;
                        $_gate=$this->model_gate->get_value_gate($data_pass_gate)	;
                        $data_pass_gate['D']=$_gate['gate_value'];
                        
                        $gate_value=$this->model_cal->water_pass_gate($data_pass_gate);
                        
                        if(is_nan($gate_value['value'])) { 
                            $Q="N/A";
                        }else{
                            $Q=number_format($gate_value['value'],2);
                        }
                        
                        $data_all_site .= '<div>';
                        $data_all_site .= 'ปริมาณน้ำผ่าน ปตร. บานที่'.$i;
                        $data_all_site .= ' <strong style="background-color:#fff; padding:0 3px; color:#001dff;">';
                        $data_all_site .= $Q;
                        $data_all_site .= '</strong> ';
                        $data_all_site .= 'ลบ.ม./วินาที';
                        $data_all_site .= '</div>';

                    }//for-gw_all
                }//foreach-data_gate
            }//if-ปริมาณน้ำผ่าน ประตู
            */



            //ปริมาณน้ำผ่าน ปตร.PPN06
            if($data_pass_gate['siteid']=="PPN06") {
						 
						 
                $_data=$this->model_sensor->get_wl_5min($data_pass_gate);

               $data_pass_gate['us']=$_data['us'];
               $data_pass_gate['ds']=$_data['ds'];
               $data_pass_gate['dt']=$_data['dt'];
               $data_pass_gate['s_earth']='-5';


               foreach($data_gate->result() as $gw){

                   $data_pass_gate['gate']=$gw->gw_value;
                   $gate_value=$this->model_cal->cal_gate($data_pass_gate);

                    $data_all_site .= '<div>';
                    $data_all_site .= 'ปริมาณน้ำผ่าน ปตร.';
                    $data_all_site .= ' <strong style="background-color:#fff; padding:0 3px; color:#001dff;">';
                    $data_all_site .= number_format($gate_value['value'],2);
                    $data_all_site .= '</strong> ';
                    $data_all_site .= 'ลบ.ม./วินาที';
                    $data_all_site .= '</div>';
           
               }//foreach-data_gate
            }//if-ปริมาณน้ำผ่าน ปตร.PPN06



            //อัตราการไหล
            $data_cal=$this->model_cal->runcal($cal_value);
            if($data_cal['sitecode']!=''){
                $data_all_site .= '<div>';
                $data_all_site .= $this->model_sensor->show_content_label('wlw',$wl->id,"อัตราการไหล");
                $data_all_site .= ' <strong style="background-color:#fff; padding:0 3px; color:#001dff;">';
                $data_all_site .= number_format($data_cal['value'],3);
                $data_all_site .= '</strong> ';
            
                    //หน่วย 
                    switch($sitecode){
                        case 'PPN01':
                            $data_all_site .= "ล้านลบ.ม." ;
                            break;
                        case 'PPN02':
                            $data_all_site .= "ลบ.ม./วินาที";
                            break;
                    }
                                 
                $data_all_site .= '</div>';
            }//if-อัตราการไหล




            //ปริมาณน้ำ wf
/*            $wfi=0;
            foreach($data_wf->result() as $wf){
                $wfi++;
                $data_all_site .= '<div>';
                $data_all_site .= $this->model_sensor->show_content_label('wf',$wf->id,"ปริมาณน้ำ $wfi");
                $data_all_site .= ' <strong style="background-color:#fff; padding:0 3px; color:#001dff;" id="'.$wf->input_code.'_'.$wf->input_value.'" >';
                $data_all_site .= '0.00';
                $data_all_site .= '</strong> ';
                $data_all_site .= 'ลบ.ม./วินาที';
                $data_all_site .= '</div>';
            }//foreach-data_wf

            if($data_wf->num_rows()==2){
                $data_all_site .= '<div>';
                $data_all_site .= $this->model_sensor->show_content_label('wff',$wf->id,"ปริมาณน้ำ $wfi");
                $data_all_site .= ' <strong style="background-color:#fff; padding:0 3px; color:#001dff;">';
                $data_all_site .= '0.00';
                $data_all_site .= '</strong> ';
                $data_all_site .= 'ลบ.ม./วินาที';
                $data_all_site .= '</div>';
            }//if-data_wf
*/



            //แสดง rq
            foreach($data_rq->result() as $rq){
							
                $data_today=$this->model_sensor->sensor_today_now($rq->input_code,$rq->location,$rq->sensor,$rq->ipaddress);
                
                $start= strtotime(date('Y-m-d 05:59:59') . "- 1day");
                $end=date('Y-m-d 06:04:59');	
                $start=date('Y-m-d H:i:s',$start);	
                $data_today1=$this->model_sensor->sensor_today($rq->input_code,$rq->location,$rq->sensor,$rq->ipaddress,$start,$end);

                $data_all_site .= '<div>';
                $data_all_site .= 'ฝนรายวัน  (6:00 - 6:00)';
                $data_all_site .= ' <strong style="background-color:#fff; padding:0 3px; color:#001dff;">';
                $data_all_site .= number_format($data_today1['sumvalue'],2);
                $data_all_site .= '</strong> ';
                $data_all_site .= 'มม.';
                $data_all_site .= '</div>';
            }//foreach-rq


            



            $name_silte = $row['sitecode'].' '.$row['sitename'];
            $sensorall[]= ["sitecode"=>$row['sitecode'], "name_silte"=>$name_silte, "data_all_site"=>$data_all_site];


        }//for-sitecode

        return $sensorall;
        exit(); 
        


    }//fn.getdata_all




    public function water_level($wl=0,$wlv=0){
        if($wlv==0) $wlv=$wl*2;
        $h50=$wlv/2;
        $lv=1;
        if($wl < $h50) $lv=1;
        if($wl >= $h50) $lv=2;
        if($wl >= $wlv) $lv=3;
        //echo $lv;
        return $lv;
    }//fn.water_level

    
    public function num_network_level($dt){
        $today=strtotime(date('Y-m-d H:i:s'));
        $update=strtotime($dt);
        $sec=$today - $update;
        $lv=1;
        if((int)$sec < 300) $lv=1;
        if((int)$sec >=900) $lv=2;
        if((int)$sec >=1200) $lv=3;
        return $lv;
    }//fn.num_network_level




}//class