<?php
class Test extends CI_Controller {

    function __construct() {
        parent::__construct();
    }
    
    public function index(){

        //Convert div into downloadable Image

        $this->load->view('layout/header_view');
        $this->load->view('test_view');
        $this->load->view('layout/footer_view');



    }// fn.index


}//end class