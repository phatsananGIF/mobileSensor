<?php
class Rainfall extends CI_Controller {

    function __construct() {      
        parent::__construct();
    }
    
    public function index(){

    }// fn.index


    public function site($sitecode){

        $datatable="";
        $label_location = ['us'=>'ด้านเหนือน้ำ','ds'=>'ด้านท้ายน้ำ'];
        $startday=date('Y-m-d 05:59:59');
        $endday=date('Y-m-d H:i:s');	
        $startdate=date('Y-m-d H:i:s',strtotime(date('Y-m-d 05:59:59') . "- 1day"));	
		$enddate=date('Y-m-d 06:04:59');
        


        //query device
        $querydevice = ("SELECT ss_devices.id as devicesID, siteid, location, sensor, sitecode, sitename, ipaddress
                    FROM ss_devices
                    LEFT JOIN ss_sites ON ss_devices.siteid=ss_sites.id
                    WHERE sitecode='$sitecode' and sensor='rq'
                    ORDER BY ss_sites.lined ASC, ss_devices.location DESC"); 
        $sitesdevice = $this->db->query($querydevice);

        if($sitesdevice->num_rows()!=0){
            $sitesdevice = $sitesdevice->row_array();

            $datatable.='<tr>
                        <td>ตำแหน่ง</td>
                        <td>'.$label_location[ $sitesdevice['location'] ].'</td>
                        </tr>';

            //query lastinfo
            $querylastinfo = ("SELECT * FROM `ss_sensor` WHERE siteid = '$sitecode' and location = '".$sitesdevice['location']."' 
                        and sensor_type = '".$sitesdevice['sensor']."' and ipaddress = '".$sitesdevice['ipaddress']."'
                        order by sensor_dt desc limit 1 ");
            $sitelastinfo = $this->db->query($querylastinfo);
            
            if($sitelastinfo->num_rows()!=0){
                $sitelastinfo = $sitelastinfo->row_array();

                $datatable.='<tr>
                            <td>ปริมาณน้ำฝน (มม.)</td>
                            <td>'.number_format($sitelastinfo['sensor_value'],2).'</td>
                            </tr>';
            


                //query day
                $queryday = ("SELECT sum(sensor_value)/4 as sensor_value, max(sensor_dt)
                            FROM `ss_sensor` WHERE siteid = '$sitecode' and location = '".$sitesdevice['location']."'
                            and sensor_type = '".$sitesdevice['sensor']."' and ipaddress = '".$sitesdevice['ipaddress']."' and
                            sensor_dt between '$startday' and '$endday' ");
                $siteday = $this->db->query($queryday);
                
                if($siteday->num_rows()!=0){
                    $siteday = $siteday->row_array();

                    $datatable.='<tr>
                                <td>ฝนสะสมวันนี้ (6:00 - ปัจจุบัน)</td>
                                <td>'.number_format($siteday['sensor_value'],2).'</td>
                                </tr>';
                }


                //query date
                $querydate = ("SELECT sum(sensor_value)/4 as sensor_value, max(sensor_dt)
                            FROM `ss_sensor` WHERE siteid = '$sitecode' and location = '".$sitesdevice['location']."'
                            and sensor_type = '".$sitesdevice['sensor']."' and ipaddress = '".$sitesdevice['ipaddress']."' and
                            sensor_dt between '$startdate' and '$enddate' ");
                $sitedate = $this->db->query($querydate);
                
                if($sitedate->num_rows()!=0){
                    $sitedate = $sitedate->row_array();

                    $datatable.='<tr>
                                <td>ฝนรายวัน (6:00 - 6:00)</td>
                                <td>'.number_format($sitedate['sensor_value'],2).'</td>
                                </tr>';
                }

            /*
                print_r($this->db->last_query());

                echo '<pre>';
                print_r($sitedate);
                echo  '</pre>';
                */
        
                if( date_format( date_create($sitelastinfo['sensor_dt']) ,"Y-m-d") != date('Y-m-d') ){
                    $date_time='<font color="red">'.$sitelastinfo['sensor_dt'].'</font>';
                }else{
                    $date_time=$sitelastinfo['sensor_dt'];
                }


                $datatable.='<tr>
                        <td>วัน/เวลา</td>
                        <td>'.$date_time.'</td>
                        </tr>';
            }


            //query rq ย้อนหลัง 1 วัน เอาไว้ทำกราฟ
            $total=0;
            $step=3600; //1hr
            $datepicker1 = date('Y-m-d H:i:s',strtotime(date('Y-m-d 06:00:00') . "- 1day"));	
            $datepicker2 = date('Y-m-d 06:00:00');
            $valRainfall="";
            $valAccumulateRainfall="";

            if($this->input->post("btsearch")!=null){
                $step = $this->input->post('timeRange');
                $date_start = $this->input->post('datepicker1');
                $date_end = $this->input->post('datepicker2');
                $datepicker1 = $this->input->post('datepicker1');
                $datepicker2 = $this->input->post('datepicker2');
            }else{
               $date_start = $startdate;
               $date_end = $endday;
            }

            $queryRQ = (" SELECT * from (
                            SELECT * ,(sum(sensor_value) / 4) AS sum_value ,
                            from_unixtime((unix_timestamp(`sensor_dt`) - (unix_timestamp(`sensor_dt`) % $step))) AS `dt`
                            from `ss_sensor` where siteid = '$sitecode'  and sensor_type = '".$sitesdevice['sensor']."'
                            and location='".$sitesdevice['location']."'
                            and sensor_dt >= '$date_start' and sensor_dt <= '$date_end'
                            group by (unix_timestamp(`sensor_dt`) DIV $step),`siteid`,`location`,`sensor_type`  ORDER BY `dt` asc  
                        ) vv where  vv.dt >= '$date_start' and dt<= '$date_end'
                        ");

            $siteRQ = $this->db->query($queryRQ);
            $siteRQ = $siteRQ->result_array();

            
            foreach($siteRQ as $rowvalue){
                $mydate=getdate(strtotime($rowvalue['dt']));
                $dt= "Date.UTC($mydate[year], $mydate[mon]-1, $mydate[mday], $mydate[hours], $mydate[minutes])";

                $sum_value=number_format($rowvalue['sum_value'],2);
                $total+=$rowvalue['sum_value'];

                $valRainfall.="[".$dt.",".$sum_value."],";
                $valAccumulateRainfall.="[".$dt.",".$total."],";
            }//for us



            $series="{
                name: 'ปริมาณน้ำฝน (มม.)',
                color: '#0077FF',
                data: [".$valRainfall."]
            },{
                name: 'ปริมาณน้ำฝนสะสม (มม.)',
                color: '#7D0096',
                yAxis: 1,
                data: [".$valAccumulateRainfall."]
            }";
            

            $data['sitecode'] = $sitecode;
            $data['ppn'] = $sitecode.' '.$sitesdevice['sitename'];
            $data['timeRange'] = ['900'=>'15 นาที', '3600'=>'1 ชั่วโมง', '10800'=>'3 ชั่วโมง',];
            $data['selectedtimeRange'] = $step;
            $data['datatable'] = $datatable;
            $data['series'] = $series;
            $data['datepicker1'] = $datepicker1;
            $data['datepicker2'] = $datepicker2;

            
            $this->load->view('layout/header_view');
            $this->load->view('rainfall_view',$data);
            $this->load->view('layout/footer_view');
            
            
            
    
        }else{
            $data['sitecode'] = '';
            $data['ppn'] = '';
            $data['timeRange'] = ['900'=>'15 นาที', '3600'=>'1 ชั่วโมง', '10800'=>'3 ชั่วโมง'];
            $data['datatable'] = '';
            $data['series'] = '';
            $data['datepicker1'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d 06:00:00') . "- 1day"));
            $data['datepicker2'] = date('Y-m-d 06:00:00');


            $this->load->view('layout/header_view');
            $this->load->view('rainfall_view',$data);
            $this->load->view('layout/footer_view');
        }
        

    }// fn.site



}//end class