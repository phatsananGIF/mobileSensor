<?php
class Home extends CI_Controller {

    function __construct() {      
        parent::__construct();
    }
    
    public function index(){


        $colorsite=array(
            'PPN01US'=>'#00ff00',
            'PPN01DS'=>'#00ffff',
            'PPN02US'=>'#ffff00',
            'PPN02DS'=>'#ff6600',
            'PPN04US'=>'#ff3366',
            'PPN04DS'=>'#ff66cc',
            'PPN05US'=>'#999966',
            'PPN05DS'=>'#007f7f',
            'PPN06US'=>'#5656ff',
            'PPN06DS'=>'#660033',
            'PPN07US'=>'#008000',
            'PPN07DS'=>'#00cccc',
            'PPN08US'=>'#999900',
            'PPN08DS'=>'#662900',
            'PPN09US'=>'#ff99b3',
            'PPN09DS'=>'#990066',
            'PPN10US'=>'#adad85',
            'PPN10DS'=>'#00e6ac',
            'PPN11US'=>'#ccccff',
            'PPN11DS'=>'#ff4d4d',
            'PPN12US'=>'#ace600',
            'PPN12DS'=>'#e60000',
        );

        $typeWL=array(
            'PPN01US'=>'ระดับน้ำ',
            'PPN02US'=>'ระดับเหนือน้ำ',
            'PPN02DS'=>'ระดับท้ายน้ำ',
            'PPN04US'=>'ระดับน้ำ',
            'PPN05US'=>'ระดับน้ำ',
            'PPN06US'=>'ระดับเหนือน้ำ',
            'PPN06DS'=>'ระดับท้ายน้ำ',
            'PPN07US'=>'ระดับเหนือน้ำ',
            'PPN07DS'=>'ระดับท้ายน้ำ',
            'PPN08US'=>'ระดับเหนือน้ำ',
            'PPN08DS'=>'ระดับท้ายน้ำ',
            'PPN09US'=>'ระดับเหนือน้ำ',
            'PPN09DS'=>'ระดับท้ายน้ำ',
            'PPN10US'=>'ระดับเหนือน้ำ',
            'PPN10DS'=>'ระดับท้ายน้ำ',
            'PPN11US'=>'ระดับเหนือน้ำ',
            'PPN11DS'=>'ระดับท้ายน้ำ',
            'PPN12US'=>'ระดับเหนือน้ำ',
            'PPN12DS'=>'ระดับท้ายน้ำ'
        );

        $date_start = date('Y-04-d H:i:s',strtotime(date('Y-04-d 00:00:00') . "- 7day"));	
        $date_end = date('Y-04-d H:i:s');
        $step=86400; //1hr=3600, 1day= 86400

        
        // --query sites wl--//
        $seriesWL = "";

        $query = ("SELECT ss_devices.id as devicesID, siteid, location, sensor, sitecode, sitename 
                    FROM ss_devices
                    LEFT JOIN ss_sites ON ss_devices.siteid=ss_sites.id
                    WHERE sensor='wl'
                    ORDER BY ss_sites.lined ASC, ss_devices.location DESC"); 
        $sites = $this->db->query($query);
        $sites = $sites->result_array();

        foreach($sites as $sitewl){
            /*
            $querysensorWL = (" SELECT * ,( avg(`sensor_value`) + avg(offset)) AS `cal_value`,
                        from_unixtime((unix_timestamp(`sensor_dt`) - (unix_timestamp(`sensor_dt`) % $step))) AS `dt`
                        from `ss_sensor` where siteid = '".$sitewl['sitecode']."'  and sensor_type = '".$sitewl['sensor']."' and location='".$sitewl['location']."' and 
                        from_unixtime((unix_timestamp(`sensor_dt`) - (unix_timestamp(`sensor_dt`) % $step))) between '$date_start' and '$date_end'  
                        group by (unix_timestamp(`sensor_dt`) DIV $step),`siteid`,`location`,`sensor_type`  ORDER BY `dt` asc  ");
                        */

            $querysensorWL = (" SELECT * ,( avg(`sensor_value`) + avg(offset)) AS `cal_value`
                        from `ss_sensor` where siteid = '".$sitewl['sitecode']."' and sensor_type = '".$sitewl['sensor']."'and location='".$sitewl['location']."' and
                        sensor_dt between '$date_start' and '$date_end' group by DATE_FORMAT(sensor_dt, '%Y-%m-%d')
                        ORDER BY `sensor_dt` asc ");
            $sitesensorWL = $this->db->query($querysensorWL);

            if($sitesensorWL->num_rows()!=0){
                $sitesensorWL = $sitesensorWL->result_array();
                $val = "";
                foreach($sitesensorWL as $lineWL){
                    $mydate=getdate(strtotime($lineWL['sensor_dt']));
                    $dt= "Date.UTC($mydate[year], $mydate[mon]-1, $mydate[mday])";
                    $dataWL=number_format($lineWL['cal_value'],2);
                    $val.="[".$dt.",".$dataWL."],";
                }
                $seriesWL.="{
                    name: '".$typeWL[$sitewl['sitecode'].strtoupper($sitewl['location'])]." ".$sitewl['sitecode']." ".$sitewl['sitename']."',
                    color: '".$colorsite[$sitewl['sitecode'].strtoupper($sitewl['location'])]."',
                    data: [".$val."]
                },";

            }else{
                $mydate = getdate(strtotime($date_end));
                $dt= "Date.UTC($mydate[year], $mydate[mon]-1, $mydate[mday])";
                $seriesWL.="{
                    name: '".$typeWL[$sitewl['sitecode'].strtoupper($sitewl['location'])]." ".$sitewl['sitecode']." ".$sitewl['sitename']."',
                    color: '".$colorsite[$sitewl['sitecode'].strtoupper($sitewl['location'])]."',
                    data: [[".$dt.",0]]
                },";
            }


        }//end foreach WL
        
        //print_r($this->db->last_query());



        // --query sites RQ--//
        $seriesRQ = "";

        $query = ("SELECT ss_devices.id as devicesID, siteid, location, sensor, sitecode, sitename 
                    FROM ss_devices
                    LEFT JOIN ss_sites ON ss_devices.siteid=ss_sites.id
                    WHERE sensor='rq'
                    ORDER BY ss_sites.lined ASC, ss_devices.location DESC"); 
        $sites = $this->db->query($query);
        $sites = $sites->result_array();

        foreach($sites as $siteRQ){
            /*
            $querysensorRQ = (" SELECT * ,(sum(sensor_value) / 4) AS sum_value ,
                                from_unixtime((unix_timestamp(`sensor_dt`) - (unix_timestamp(`sensor_dt`) % $step))) AS `dt`
                                from `ss_sensor` where siteid = '".$siteRQ['sitecode']."' and sensor_type = 'RQ'
                                and from_unixtime((unix_timestamp(`sensor_dt`) - (unix_timestamp(`sensor_dt`) % $step))) between  '$date_start' and '$date_end' 
                                group by (unix_timestamp(`sensor_dt`) DIV $step),`siteid`,`location`,`sensor_type`  ORDER BY `dt` asc ");

                                */

                                
            $querysensorRQ = (" SELECT * ,(sum(sensor_value) /4) AS sum_value FROM `ss_sensor`
                                WHERE `sensor_type` = 'RQ'  and siteid = '".$siteRQ['sitecode']."'
                                and sensor_dt between '$date_start' and '$date_end' 
                                group by DATE_FORMAT(sensor_dt, '%Y-%m-%d')
                                ORDER BY `sensor_dt` asc ");
                                

            $sitesensorRQ = $this->db->query($querysensorRQ);
            
            if($sitesensorRQ->num_rows()!=0){
                $sitesensorRQ = $sitesensorRQ->result_array();
                $val = "";
                foreach($sitesensorRQ as $lineRQ){
                    $mydate=getdate(strtotime($lineRQ['sensor_dt']));
                    $dt= "Date.UTC($mydate[year], $mydate[mon]-1, $mydate[mday])";
                    $us=number_format($lineRQ['sum_value'],2);
                    $val.="[".$dt.",".$us."],";
                }
                $seriesRQ.="{
                    name: '".$siteRQ['sitecode']." ".$siteRQ['sitename']."',
                    color: '".$colorsite[$siteRQ['sitecode']."US"]."',
                    data: [".$val."]
                },";

            }else{
                $mydate = getdate(strtotime($date_end));
                $dt= "Date.UTC($mydate[year], $mydate[mon]-1, $mydate[mday])";
                $seriesRQ.="{
                    name: '".$siteRQ['sitecode']." ".$siteRQ['sitename']."',
                    color: '".$colorsite[$siteRQ['sitecode']."US"]."',
                    data: [[".$dt.",0]]
                },";

            }


        }//end foreach RQ



        
        //print_r($this->db->last_query());
        /*
        echo '<pre>';
        print_r($sites);
        echo  '</pre>';
        */
        
        
        
        
        $data['series'] = "";
        $data['seriesWL'] = $seriesWL;
        $data['seriesRQ'] = $seriesRQ;


        
        $this->load->view('layout/header_view');
        $this->load->view('home_view',$data);
        $this->load->view('layout/footer_view');
        
        

    }// fn.index


}