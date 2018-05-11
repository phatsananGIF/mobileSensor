<?php
class SlideImage extends CI_Controller {

    function __construct() {      
        parent::__construct();
    }
    
    public function index(){

        $this->load->view('layout/header_view');
        $this->load->view('slideimage_view');
        $this->load->view('layout/footer_view');

    }// fn.index


    public function search(){
        if( $this->input->post("selected_sitecode")!=null){

            $selected_sitecode = $this->input->post("selected_sitecode");
            $datepicker = $this->input->post("datepicker");
            
            $queryimage = (" SELECT * FROM ss_picture WHERE deviceid='$selected_sitecode' and DATE_FORMAT(input_date,'%Y/%m/%d')='$datepicker' ");

            //$queryimage = (" SELECT * FROM ss_picture WHERE deviceid='61' and DATE_FORMAT(input_date,'%Y/%m/%d')='2018/05/09' ORDER BY input_date DESC ");

            
            $rsimage = $this->db->query($queryimage);

            

            if($rsimage->num_rows()==0){
                $tapImage ='not';
            }else{
                $rsimage = $rsimage->result_array();
                $tapImage = '';
                foreach($rsimage as $rowimage){
                    $fdir=date('Ymd',strtotime($rowimage['input_date']));
                    $text = "<img alt='".$rowimage['input_date']."'
                    src='/websensor/snapshot/".$rowimage['sitecode']."/".$fdir."/".$rowimage['picture_org']."_small.jpg'
                    data-image='/websensor/snapshot/".$rowimage['sitecode']."/".$fdir."/".$rowimage['picture_org'].".jpg'
                    data-description='".$rowimage['input_date']."'>";

                    $tapImage .= $text;
                }

                
            }

            
                    
            echo json_encode($tapImage);
            exit(); 
        }

        redirect('/slideImage');

    }// fn.search


}