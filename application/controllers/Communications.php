<?php
class Communications extends CI_Controller {

    function __construct() {      
        parent::__construct();
    }
    
    public function index(){


        $this->load->view('layout/header_view');
        $this->load->view('communications_view');
        $this->load->view('layout/footer_view');

    }// fn.index


}