<?php
class Quantitywater extends CI_Controller {

    function __construct() {      
        parent::__construct();
    }
    
    public function index(){


    }// fn.index

    public function site($sitecode){

        //echo $sitecode;

        //query device
        $querydevice = ("SELECT ss_devices.id as devicesID, location, sensor, input_value, sitecode, sitename
                    FROM ss_devices LEFT JOIN ss_sites ON ss_devices.siteid=ss_sites.id WHERE sitecode='$sitecode' and sensor='wf'
                    ORDER BY ss_devices.id ASC"); 
        $sitesdevice = $this->db->query($querydevice);

        if($sitesdevice->num_rows()!=0){
            $sitesdevice = $sitesdevice->result_array();
            $i = 0;

            foreach($sitesdevice as $siteWF ){


                $query = ("SELECT label FROM ss_content_label WHERE deviceid='".$siteWF['devicesID']."' and tagcontent='".$siteWF['sensor']."'");
                $result = $this->db->query($query);
        
                $label="อัตราการไหล";
                if($result->num_rows()==1){
                    $row = $result->row_array(); 
                    if(empty($row['label'])==FALSE) $label=$row['label'];
                }
                $ppn = $siteWF['sitecode']." ".$siteWF['sitename'];
                $sitesdevice[$i]['label']=$label;
                $i++;



            }



            $data['ppn'] = $ppn; //ชื้อเขือน
            $data['sitesdevice'] = $sitesdevice;


            /*
            echo '<pre>';
            print_r($sitesdevice);
            echo  '</pre>';
            */

            
            $this->load->view('layout/header_view');
            $this->load->view('quantitywater_view',$data);
            $this->load->view('layout/footer_view');
            



        }




    }// fn.site


   




}//class