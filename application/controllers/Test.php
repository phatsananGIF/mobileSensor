<?php
class Test extends CI_Controller {

    function __construct() {      
        parent::__construct();
    }
    
    public function index(){

        $query = ("SELECT ss_devices.id as devicesID, siteid, location, sensor, sitecode, sitename 
                    FROM ss_devices
                    LEFT JOIN ss_sites ON ss_devices.siteid=ss_sites.id
                    WHERE sensor='rq'
                    ORDER BY ss_sites.lined ASC, ss_devices.location DESC"); 
        $sites = $this->db->query($query);
        $sites = $sites->result_array();


        echo '<pre>';
        print_r($sites);
        echo  '</pre>';

        

        foreach( $sites as $site ){

            if(($site['sitecode'] == "PPN04") or ( $site['sitecode'] == "PPN05")) {

                $siteid=$site['sitecode'];
                $date_start = date('Y-04-d H:i:s',strtotime(date('Y-04-d 00:00:00') . "- 7day"));
                $date_end = date('Y-04-d H:i:s');	 
                $SQL="SELECT siteid,
                        sum(if(ss_sensor.location=\"US\",ss_sensor.sensor_value,0)) + sum(if(ss_sensor.location=\"US\",ss_sensor.offset,0))  as us ,
                        sum(if(ss_sensor.location=\"DS\",ss_sensor.sensor_value,0)) +  sum(if(ss_sensor.location=\"DS\",ss_sensor.offset,0)) as ds ,
                        from_unixtime((unix_timestamp(`ss_sensor`.`sensor_dt`) - (unix_timestamp(`ss_sensor`.`sensor_dt`) % 86400))) AS `dt` 
                        FROM `ss_sensor`  where sensor_type = 'WL' and ss_sensor.sensor_dt between  '$date_start' and '$date_end' and siteid = \"$siteid\"
                        group by siteid ,(unix_timestamp(`ss_sensor`.`sensor_dt`) DIV 86400) order by dt asc";

                $q=$this->db->query($SQL);

                $rowdata=array(
                    'siteid'=>$site['sitecode'],
                    'us'=>0,
                    'ds'=>0,
                    'dt'=>0,
                );

                if($q->num_rows()!=0) {
                    $rowdata = $q->result_array();
                }

                


                print_r($this->db->last_query());
                echo '<pre>';
                print_r($rowdata);
                echo  '</pre>';



            }

        }

    }// fn.index




}//end class