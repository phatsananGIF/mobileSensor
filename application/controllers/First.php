<?php
class First extends CI_Controller {

    function __construct() {      
        parent::__construct();
    }
    
    public function index(){


        $this->load->view('layout/first_view');

    }// fn.index


}