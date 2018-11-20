<?php
class Quality_water extends CI_Controller {

    function __construct() {      
        parent::__construct();
    }
    
    public function index(){


    }// fn.index

    public function site($sitecode){

        $step=3600; //1hr
        $datepicker1 = date('Y-m-d H:i:s',strtotime(date('Y-m-d 06:00:00') . "- 1day"));	
        $datepicker2 = date('Y-m-d 06:00:00');
        $datatable="";
        $date_time="";
        $val_avgDO="";
        $val_avgpH="";
        $val_avgEC="";
        $val_avgTem="";

        //query wq last
        $querydevice = ("SELECT site, do, ph, ec, tm, sitename, sensor_dt
                            FROM ss_wq LEFT JOIN ss_sites ON ss_wq.site=ss_sites.sitecode WHERE site='$sitecode'
                            ORDER BY sensor_dt DESC LIMIT 1
                        ");
        $sitesdevice = $this->db->query($querydevice);

        if($sitesdevice->num_rows()!=0){
            $sitesdevice = $sitesdevice->row_array();

            $ppn = $sitesdevice['site']." ".$sitesdevice['sitename'];

            if( date_format( date_create($sitesdevice['sensor_dt']) ,"Y-m-d") != date('Y-m-d') ){
                $date_time ='<p><font color="red">'.$sitesdevice['sensor_dt'].'</font></p>';
            }else{
                $date_time ='<p>'.$sitesdevice['sensor_dt'].'</p>';
            }

            $datatable.='<tr>
                            <td>pH</td>
                            <td>'.$sitesdevice['ph'].'</td>
                        </tr>
                        <tr>
                            <td>DO (mg/ml)</td>
                            <td>'.$sitesdevice['do'].'</td>
                        </tr>
                        <tr>
                            <td>EC (mS)</td>
                            <td>'.$sitesdevice['ec'].'</td>
                        </tr>
                        <tr>
                            <td>Temperature (c)</td>
                            <td>'.$sitesdevice['tm'].'</td>
                        </tr>
                        <tr>
                            <td>วัน/เวลา</td>
                            <td>'.$date_time.'</td>
                        </tr>';
        }



        if($this->input->post("btsearch")!=null){
            $step = $this->input->post('timeRange');
            $datepicker1 = $this->input->post('datepicker1');
            $datepicker2 = $this->input->post('datepicker2');
        }

        //query wq series
        $query_avg = ("SELECT * from (
                            SELECT * ,avg(do) as avgDO, avg(ph) as avgpH, avg(ec) as avgEC, avg(tm) as avgTem,
                                from_unixtime((unix_timestamp(sensor_dt) - (unix_timestamp(sensor_dt) % $step))) AS dt
                                from ss_wq where site = 'PPN12'
                                and sensor_dt >= '$datepicker1' and sensor_dt <= '$datepicker2'
                                group by (unix_timestamp(sensor_dt) DIV $step), site ORDER BY dt asc
                        ) vv where  vv.dt >= '$datepicker1' and dt<= '$datepicker2'
                        ");
        $sites_avg = $this->db->query($query_avg);
        $sites_avg = $sites_avg->result_array();

        foreach($sites_avg as $site){
            $datetime = strtotime($site['dt'] . " UTC+7");
            $datetime *= 1000;

            $site_avgDO = number_format($site['avgDO'],2);
            $site_avgpH = number_format($site['avgpH'],2);
            $site_avgEC = number_format($site['avgEC'],2);
            $site_avgTem = number_format($site['avgTem'],2);

            $val_avgDO[] = [ $datetime, (float)$site_avgDO];
            $val_avgpH[] = [ $datetime, (float)$site_avgpH];
            $val_avgEC[] = [ $datetime, (float)$site_avgEC];
            $val_avgTem[] = [ $datetime, (float)$site_avgTem];
        }//for sites_avg


$series_all = [
    [  "name"  =>  "DO (mg/ml)",
        "yAxis"=> 2,
        "color" =>  "#00cc00",
        "data"  =>  $val_avgDO
    ],
    [   "name"  =>  "pH",
        "yAxis"=> 1,
        "color" =>  "#0077FF",
        "data"  =>  $val_avgpH
    ],
    [   "name"  =>  "EC (mS)",
        "yAxis"=> 3,
        "color" =>  "#999966",
        "data"  =>  $val_avgEC
    ],
    [  "name"  =>  "Temperature (c)",
        "yAxis"=> 0,
        "color" =>  "#ff6600",
        "data"  =>  $val_avgTem
    ]
];


$dataseries = [['waterDO', $series_all]];



        $data['sitecode'] = $sitecode;
        $data['ppn'] = $ppn; //ชื้อเขือน
        $data['datatable'] = $datatable;
        $data['timeRange'] = ['900'=>'15 นาที', '3600'=>'1 ชั่วโมง', '10800'=>'3 ชั่วโมง'];
        $data['selectedtimeRange'] = $step;
        $data['datepicker1'] = $datepicker1;
        $data['datepicker2'] = $datepicker2;
        $data['dataseries'] = $dataseries;

        
        $this->load->view('layout/header_view');
        $this->load->view('quality_water_view',$data);
        $this->load->view('layout/footer_view');
        

    }// fn.site


   




}//class