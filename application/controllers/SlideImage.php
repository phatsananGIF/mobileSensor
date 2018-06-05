<?php
class SlideImage extends CI_Controller {

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

        /*
        echo '<pre>';
        print_r($data);
        echo  '</pre>';
        */
        
        
        $this->load->view('layout/header_view');
        $this->load->view('slideimage_view',$data);
        $this->load->view('layout/footer_view');
        
        

    }// fn.index


    public function search(){
        if( $this->input->post("selected_sitecode")!=null){

            $selected_sitecode = $this->input->post("selected_sitecode");
            $datepicker = $this->input->post("datepicker");
            
            $queryimage = (" SELECT * FROM ss_picture WHERE deviceid='$selected_sitecode' and DATE_FORMAT(input_date,'%Y/%m/%d')='$datepicker' ");

            //$queryimage = (" SELECT * FROM ss_picture WHERE deviceid='61' and DATE_FORMAT(input_date,'%Y/%m/%d')='2018/05/08' ORDER BY input_date DESC ");

            
            $rsimage = $this->db->query($queryimage);

            

            if($rsimage->num_rows()==0){
                $tapImage ='not';
            }else{
                $rsimage = $rsimage->result_array();
                $tapImage = '';

                $url='/websensor/';
                foreach($rsimage as $rowimage){
                    $fdir=date('Ymd',strtotime($rowimage['input_date']));

                    $text = "<img alt='".$rowimage['input_date']."'
                    src='".$url."snapshot/".$rowimage['sitecode']."/".$fdir."/".$rowimage['picture_org']."_small.jpg'
                    data-image='".$url."snapshot/".$rowimage['sitecode']."/".$fdir."/".$rowimage['picture_org'].".jpg'
                    data-description='".$rowimage['input_date']."'>";

                    $tapImage .= $text;
                }

                
            }

            
                    
            echo json_encode($tapImage);
            exit(); 
        }//end if-post

        redirect('/slideImage');

    }// fn.search


}