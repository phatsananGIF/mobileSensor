<?php
class Videoview extends CI_Controller {

    function __construct() {      
        parent::__construct();
    }
    
    public function index(){

        $query = ("SELECT ss_devices.id as devicesID, siteid, location, sitecode, sitename 
                    FROM ss_devices
                    LEFT JOIN ss_sites ON ss_devices.siteid=ss_sites.id
                    WHERE devtype='13'
                    ORDER BY ss_sites.lined ASC, ss_devices.location DESC"); 
        $rsquery = $this->db->query($query);
        $rsquery = $rsquery->result_array();
        $data['rsquery'] = $rsquery;

        
        $this->load->view('layout/header_view');
        $this->load->view('video_view',$data);
        $this->load->view('layout/footer_view');
        
        
    }// fn.index



    public function getfile(){
        if( $this->input->post()!=null){
            $selected_sitecode = $this->input->post("selected_sitecode");
            //$selected_sitecode = "PPN01_us";

            //อ่านไฟล์ในโฟเดอ//
            $objScan = scandir(FCPATH."video/".$selected_sitecode."/");

            $filename = "";
            if(count($objScan)>=3){
                $filename = explode(".", $objScan[2]);
                $filename = $filename[0];
            }

            $startfile = "";
            if (is_numeric($filename)) {
                $startfile = $filename;
                echo json_encode($startfile);
                exit();
            }else{
                echo json_encode("no");
                exit();
            }
             
        }//end if-post
    }





}//end class