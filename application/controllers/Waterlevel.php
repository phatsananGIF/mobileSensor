<?php
class Waterlevel extends CI_Controller {

    function __construct() {      
        parent::__construct();
        $this->load->model('model_cal'); 
        $this->load->model('model_alarm'); 
    }

    public function index($sitecode){

    }// fn.index


    
    public function site($sitecode){
        //$sitecode ='PPN01';
        $step=3600; //1hr
        $datepicker1 = date('Y-m-d H:i:s',strtotime(date('Y-m-d 06:00:00') . "- 1day"));	
        $datepicker2 = date('Y-m-d 06:00:00');
        $datatable="";
        $date_time="";
        $site_name="";
        $hUS= "ระดับเหนือน้ำ (ม.รทก.)";
        $hDS= "ระดับท้ายน้ำ (ม.รทก.)";
        $h_label = ['us'=>'ระดับเหนือน้ำ (ม.รทก.)','ds'=>'ระดับท้ายน้ำ (ม.รทก.)'];
        $cal_value = ['sitecode'=>$sitecode,'us'=>0,'ds'=>0];	
        

        //query device
        $querydevice = ("SELECT ss_devices.id as devicesID, siteid, location, sensor, sitecode, sitename, input_update
                    FROM ss_devices
                    LEFT JOIN ss_sites ON ss_devices.siteid=ss_sites.id
                    WHERE sitecode='$sitecode' and sensor='wl'
                    ORDER BY ss_sites.lined ASC, ss_devices.location DESC"); 
        $sitesdevice = $this->db->query($querydevice);

        if($sitesdevice->num_rows()!=0){
            $sitesdevice = $sitesdevice->result_array();


            foreach( $sitesdevice as $rowdevice){
                $id_devices[] = $rowdevice['devicesID'];
                //query us ds ล่าสุด
                $queryUsds = (" SELECT * , sensor_value + offset as sum_val FROM `ss_sensor`
                        WHERE siteid='".$rowdevice['sitecode']."' and sensor_type='".$rowdevice['sensor']."' and location='".$rowdevice['location']."'
                        ORDER BY sensor_dt DESC LIMIT 1 ");
                $siteUsds = $this->db->query($queryUsds);

                if($siteUsds->num_rows()!=0){
                    $siteUsds = $siteUsds->row_array();
                    $cal_value[strtolower($siteUsds['location'])] = number_format($siteUsds['sensor_value'] + $siteUsds['offset'],2);
                    
                    $datatable.='<tr>
                                <td>'.$h_label[strtolower($siteUsds['location'])].'</td>
                                <td>'.$cal_value[strtolower($siteUsds['location'])].'</td>
                                </tr>';
                    
                }else{
                    $siteUsds = $siteUsds->row_array();
                }
                
                    $site_name= $rowdevice['sitecode']." ".$rowdevice['sitename'];

                    if( date_format( date_create($rowdevice['input_update']) ,"Y-m-d") != date('Y-m-d') ){
                        $date_time.='<p><font color="red">'.$rowdevice['input_update'].'</font></p>';
                    }else{
                        $date_time.='<p>'.$rowdevice['input_update'].'</p>';
                    }



                //query us ds ย้อนหลัง 1 วัน เอาไว้ทำกราฟ
                if($this->input->post("btsearch")!=null){
                    $step = $this->input->post('timeRange');
                    $startdate = $this->input->post('datepicker1');
                    $enddate = $this->input->post('datepicker2');
                    $datepicker1 = $this->input->post('datepicker1');
                    $datepicker2 = $this->input->post('datepicker2');
                }else{
                   $startdate = date('Y-m-d H:i:s',strtotime($siteUsds['sensor_dt']. "- 1day"));
                   $enddate = $siteUsds['sensor_dt'];
                }

                $querydateUsds = (" SELECT * ,( avg(`sensor_value`) + avg(offset)) AS `cal_value`,
                            from_unixtime((unix_timestamp(`sensor_dt`) - (unix_timestamp(`sensor_dt`) % $step))) AS `dt`
                            from `ss_sensor` where siteid = '".$rowdevice['sitecode']."'  and sensor_type = '".$rowdevice['sensor']."' and location='".$rowdevice['location']."' and 
                            from_unixtime((unix_timestamp(`sensor_dt`) - (unix_timestamp(`sensor_dt`) % $step))) between '$startdate' and '$enddate'  
                            group by (unix_timestamp(`sensor_dt`) DIV $step),`siteid`,`location`,`sensor_type`  ORDER BY `dt` asc  ");

                $sitedateUsds = $this->db->query($querydateUsds);
                $sitedateUsds = $sitedateUsds->result_array();
                $arrdateUsds['sitecode'] = $rowdevice['sitecode'];

                if(count($sitedateUsds)!=0){
                    $arrcount = (count($sitedateUsds))-1;
                    $arrdateUsds['dt'.strtolower($rowdevice['location'])] = $sitedateUsds[$arrcount]['dt'];
                }else{
                    $arrdateUsds['dt'.strtolower($rowdevice['location'])] = 0;
                }

                $arrdateUsds[strtolower($rowdevice['location'])] = $sitedateUsds;

                //print_r($this->db->last_query());
                /*
                echo '<pre>';
                print_r($siteUsds);
                echo  '</pre>';
                */

            }//end foreach



            
            $data_cal = $this->model_cal->runcal($cal_value);


            if($data_cal['sitecode']!=''){
                $value=$data_cal['value'];
            }else{
                $value=$cal_value['us'];
            }

            number_format($data_cal['value'],3);

            $datasensor = $this->model_alarm->alarm_level($value, $id_devices);

            if($value != $cal_value['us']){
                $datatable.='<tr>
                        <td></td>
                        <td>'.number_format($value,3).'</td>
                        </tr>';
            }

            $datatable.='<tr>
                        <td>'.$datasensor['sensor_label'].'</td>
                        <td><strong style="color: '.$datasensor['sensor_color'].';">'.$datasensor['sensor_status'].'</strong></td>
                        </tr>
                        <td>วัน/เวลา</td>
                        <td>'.$date_time.'</td>
                        </tr>';

            
            

            //คำนวน cal PPN01,PPN02
            $valUs="";
            $valDs="";
            $valCal="";
            $plotLines="";
            if($arrdateUsds['sitecode'] == 'PPN01' or $arrdateUsds['sitecode'] == 'PPN02'){
                //if(count($arrdateUsds['us']) !=0 && count($arrdateUsds['ds']) !=0 ){

                if( isset($arrdateUsds['us'])  && isset($arrdateUsds['ds']) ){
                    //หา valcal
                    
                    
                    if($arrdateUsds['dtus'] >= $arrdateUsds['dtds']){
                        foreach($arrdateUsds['us'] as $key=>$rowvalueUs){
                            $cal_value['us'] = 0;
                            $cal_value['ds'] = 0;

                            foreach($arrdateUsds['ds'] as $rowvalueDs){
                                $cal_value['ds'] = 0;

                                if($rowvalueUs['dt']==$rowvalueDs['dt']){
                                    $mydate=getdate(strtotime($rowvalueUs['dt']));
                                    $dt= "Date.UTC($mydate[year], $mydate[mon]-1, $mydate[mday], $mydate[hours], $mydate[minutes])";
    
                                    $cal_value['us'] = $rowvalueUs['cal_value'];
                                    $cal_value['ds'] = $rowvalueDs['cal_value'];
    
                                    break;
                                }
                            }

                            if($cal_value['ds']=0){
                                $cal_value['us'] = $rowvalueUs['cal_value'];
                                $cal_value['ds'] = 0;
                            }

                            $data_cal = $this->model_cal->runcal($cal_value);
                            $valCal.="[".$dt.",".number_format($data_cal['value'],3)."],";

                        }//for valCal

                    }elseif($arrdateUsds['dtds'] >= $arrdateUsds['dtus']){
                        foreach($arrdateUsds['ds'] as $key=>$rowvalueDs){
                            $cal_value['us'] = 0;
                            $cal_value['ds'] = 0;

                            foreach($arrdateUsds['ds'] as $rowvalueUs){
                                $cal_value['us'] = 0;

                                if($rowvalueDs['dt']==$rowvalueUs['dt']){
                                    $mydate=getdate(strtotime($rowvalueDs['dt']));
                                    $dt= "Date.UTC($mydate[year], $mydate[mon]-1, $mydate[mday], $mydate[hours], $mydate[minutes])";
    
                                    $cal_value['ds'] = $rowvalueDs['cal_value'];
                                    $cal_value['us'] = $rowvalueUs['cal_value'];
    
                                    break;
                                }
                            }

                            if($cal_value['us']=0){
                                $cal_value['ds'] = $rowvalueDs['cal_value'];
                                $cal_value['us'] = 0;
                            }

                            $data_cal = $this->model_cal->runcal($cal_value);
                            $valCal.="[".$dt.",".number_format($data_cal['value'],3)."],";

                        }//for valCal
                    }


                    

                    foreach($arrdateUsds['us'] as $key=>$rowvalueUs){
                        $mydate=getdate(strtotime($rowvalueUs['dt']));
                        $dt= "Date.UTC($mydate[year], $mydate[mon]-1, $mydate[mday], $mydate[hours], $mydate[minutes])";
    
                        $us=number_format($rowvalueUs['cal_value'],2);
    
                        $valUs.="[".$dt.",".$us."],";
                    }//for us
    
                    foreach($arrdateUsds['ds'] as $key=>$rowvalueDs){
                        $mydate=getdate(strtotime($rowvalueDs['dt']));
                        $dt= "Date.UTC($mydate[year], $mydate[mon]-1, $mydate[mday], $mydate[hours], $mydate[minutes])";
    
                        $ds=number_format($rowvalueDs['cal_value'],2);
    
                        $valDs.="[".$dt.",".$ds."],";
                    }//for us

                    $series="{
                        name: 'ระดับเหนือน้ำ',
                        color: '#0077FF',
                        data: [".$valUs."]
                    },{
                        name: 'ระดับท้ายน้ำ',
                        color: '#7D0096',
                        data: [".$valDs."]
                    },{
                        name: '".$datasensor['sensor_label']."',
                        color: '#00CC00',
                        data: [".$valCal."]
                    }";



               }else if( isset($arrdateUsds['us']) ){
                    foreach($arrdateUsds['us'] as $key=>$rowvalueUs){
                        $mydate=getdate(strtotime($rowvalueUs['dt']));
                        $dt= "Date.UTC($mydate[year], $mydate[mon]-1, $mydate[mday], $mydate[hours], $mydate[minutes])";
    
                        $us=number_format($rowvalueUs['cal_value'],2);
                        $valUs.="[".$dt.",".$us."],";

                        $cal_value['us'] = $rowvalueUs['cal_value'];
                        $cal_value['ds'] = 0;
                        $data_cal = $this->model_cal->runcal($cal_value);

                        if($data_cal['sitecode']!='') {
                            $valCal.="[".$dt.",".number_format($data_cal['value'],3)."],";
                        }
                    }

                    $series="{
                        name: 'ระดับเหนือน้ำ',
                        color: '#0077FF',
                        data: [".$valUs."]
                    },{
                        name: '".$datasensor['sensor_label']."',
                        color: '#00CC00',
                        data: [".$valCal."]
                    }";

                }

            }else if( $arrdateUsds['sitecode'] == 'PPN04'){ //PPN04
                foreach($arrdateUsds['us'] as $key=>$rowvalueUs){
                    $mydate=getdate(strtotime($rowvalueUs['dt']));
                    $dt= "Date.UTC($mydate[year], $mydate[mon]-1, $mydate[mday], $mydate[hours], $mydate[minutes])";

                    $us=number_format($rowvalueUs['cal_value'],2);
                    $valUs.="[".$dt.",".$us."],";
                    //$valL1.="[".$dt.",1.550],";
                    //$valL2.="[".$dt.",2.200],";
                }

                $series="{
                    name: 'ระดับเหนือน้ำ',
                    color: '#0077FF',
                    data: [".$valUs."]
                }";

                $plotLines="plotLines: [{
                    color: '#FF0000',
                    width: 2,
                    value: 1.550,
                    label: {
                        text: 'วิกฤต ระดับ 1',
                        align: 'right',
                        x: -10
                    }
                },{
                    color: '#FF0000',
                    width: 2,
                    value: 2.200,
                    label: {
                        text: 'วิกฤต ระดับ 2',
                        align: 'right',
                        x: -10
                    }
                }],
                softMax: 2.300";


            }else if( isset($arrdateUsds['us'])  && isset($arrdateUsds['ds']) ){
                foreach($arrdateUsds['us'] as $key=>$rowvalueUs){
                    $mydate=getdate(strtotime($rowvalueUs['dt']));
                    $dt= "Date.UTC($mydate[year], $mydate[mon]-1, $mydate[mday], $mydate[hours], $mydate[minutes])";

                    $us=number_format($rowvalueUs['cal_value'],2);

                    $valUs.="[".$dt.",".$us."],";
                }//for us

                foreach($arrdateUsds['ds'] as $key=>$rowvalueDs){
                    $mydate=getdate(strtotime($rowvalueDs['dt']));
                    $dt= "Date.UTC($mydate[year], $mydate[mon]-1, $mydate[mday], $mydate[hours], $mydate[minutes])";

                    $ds=number_format($rowvalueDs['cal_value'],2);

                    $valDs.="[".$dt.",".$ds."],";
                }//for us


                $series="{
                    name: 'ระดับเหนือน้ำ',
                    color: '#0077FF',
                    data: [".$valUs."]
                },{
                    name: 'ระดับท้ายน้ำ',
                    color: '#7D0096',
                    data: [".$valDs."]
                }";


            }else if( isset($arrdateUsds['us']) ){
                foreach($arrdateUsds['us'] as $key=>$rowvalueUs){
                    $mydate=getdate(strtotime($rowvalueUs['dt']));
                    $dt= "Date.UTC($mydate[year], $mydate[mon]-1, $mydate[mday], $mydate[hours], $mydate[minutes])";


                    $us=number_format($rowvalueUs['cal_value'],2);
                    $valUs.="[".$dt.",".$us."],";
                }

                $series="{
                    name: 'ระดับเหนือน้ำ',
                    color: '#0077FF',
                    data: [".$valUs."]
                }";

            }//end if
            





            $data['sitecode'] = $sitecode;
            $data['ppn'] = $site_name;
            $data['datatable'] = $datatable;
            $data['series'] = $series;
            $data['plotLines'] = $plotLines;
            $data['timeRange'] = ['900'=>'15 นาที', '3600'=>'1 ชั่วโมง', '10800'=>'3 ชั่วโมง'];
            $data['selectedtimeRange'] = $step;
            $data['datepicker1'] = $datepicker1;
            $data['datepicker2'] = $datepicker2;
            
            $this->load->view('layout/header_view');
            $this->load->view('waterlevel_view',$data);
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
            $this->load->view('waterlevel_view',$data);
            $this->load->view('layout/footer_view');
        }

    }// fn.site




}