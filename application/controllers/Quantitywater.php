<?php
class Quantitywater extends CI_Controller {

    function __construct() {      
        parent::__construct();
    }
    
    public function index(){


    }// fn.index


    public function ppn01(){

        $datatable1='<tr>
                    <td>อัตราการไหล (ลบ.ม/ชม.)</td>
                    <td>11596</td>
                    </tr>
                    <tr>
                    <td>อัตราการไหล (ลบ.ม/วินาที)</td>
                    <td>4.99</td>
                    </tr>
                    <tr>
                    <td>ปริมาณน้ำสะสม (ลบ.ม.)</td>
                    <td>994290.00</td>
                    </tr>
                    <tr>
                    <td>วัน/เวลา</td>
                    <td>2018-04-10 10:24:41</td>
                    </tr>';

        
        $datatable2='<tr>
                    <td>อัตราการไหล (ลบ.ม/ชม.)</td>
                    <td>547</td>
                    </tr>
                    <tr>
                    <td>อัตราการไหล (ลบ.ม/วินาที)</td>
                    <td>0.15</td>
                    </tr>
                    <tr>
                    <td>ปริมาณน้ำสะสม (ลบ.ม.)</td>
                    <td>834637.00</td>
                    </tr>
                    <tr>
                    <td>วัน/เวลา</td>
                    <td>2018-04-10 10:30:22</td>
                    </tr>';


        $datatable3='<tr>
                    <td>อัตราการไหล (ลบ.ม/ชม.)</td>
                    <td>9998</td>
                    </tr>
                    <tr>
                    <td>อัตราการไหล (ลบ.ม/วินาที)</td>
                    <td>2.78</td>
                    </tr>
                    <tr>
                    <td>ปริมาณน้ำสะสม (ลบ.ม.)</td>
                    <td>160682.00</td>
                    </tr>
                    <tr>
                    <td>วัน/เวลา</td>
                    <td>2018-04-10 10:30:56</td>
                    </tr>';
     



        $data['ppn'] = 'PPN01 เขื่อนห้วยน้ำใส';
        $data['title1'] = 'ปริมาณน้ำผ่านท่อทั้งหมด';
        $data['datatable1'] = $datatable1;
        $data['title2'] = 'ปริมาณน้ำผ่านท่อนิคมฯ';
        $data['datatable2'] = $datatable2;
        $data['title3'] = 'ปริมาณน้ำผ่านท่อลงคลอง';
        $data['datatable3'] = $datatable3;

        $this->load->view('layout/header_view');
        $this->load->view('quantitywater_view',$data);
        $this->load->view('layout/footer_view');


    }// fn.ppn01


    public function ppn02(){

        $datatable1='<tr>
                    <td>อัตราการไหล (ลบ.ม/ชม.)</td>
                    <td>11596</td>
                    </tr>
                    <tr>
                    <td>อัตราการไหล (ลบ.ม/วินาที)</td>
                    <td>4.99</td>
                    </tr>
                    <tr>
                    <td>ปริมาณน้ำสะสม (ลบ.ม.)</td>
                    <td>994290.00</td>
                    </tr>
                    <tr>
                    <td>วัน/เวลา</td>
                    <td>2018-04-10 10:24:41</td>
                    </tr>';

        
        $datatable2='<tr>
                    <td>อัตราการไหล (ลบ.ม/ชม.)</td>
                    <td>547</td>
                    </tr>
                    <tr>
                    <td>อัตราการไหล (ลบ.ม/วินาที)</td>
                    <td>0.15</td>
                    </tr>
                    <tr>
                    <td>ปริมาณน้ำสะสม (ลบ.ม.)</td>
                    <td>834637.00</td>
                    </tr>
                    <tr>
                    <td>วัน/เวลา</td>
                    <td>2018-04-10 10:30:22</td>
                    </tr>';


        $datatable3='<tr>
                    <td>อัตราการไหล (ลบ.ม/ชม.)</td>
                    <td>9998</td>
                    </tr>
                    <tr>
                    <td>อัตราการไหล (ลบ.ม/วินาที)</td>
                    <td>2.78</td>
                    </tr>
                    <tr>
                    <td>ปริมาณน้ำสะสม (ลบ.ม.)</td>
                    <td>160682.00</td>
                    </tr>
                    <tr>
                    <td>วัน/เวลา</td>
                    <td>2018-04-10 10:30:56</td>
                    </tr>';
     



        $data['ppn'] = 'PPN02 ฝายคลองไม้เสียบ';
        $data['title1'] = 'ปริมาณน้ำผ่านท่อทั้งหมด';
        $data['datatable1'] = $datatable1;
        $data['title2'] = 'ปริมาณน้ำผ่านท่อนิคมฯ';
        $data['datatable2'] = $datatable2;
        $data['title3'] = 'ปริมาณน้ำผ่านท่อลงคลอง';
        $data['datatable3'] = $datatable3;

        $this->load->view('layout/header_view');
        $this->load->view('quantitywater_view',$data);
        $this->load->view('layout/footer_view');


    }// fn.ppn02


    public function ppn04(){

        $datatable1='<tr>
                    <td>อัตราการไหล (ลบ.ม/ชม.)</td>
                    <td>11596</td>
                    </tr>
                    <tr>
                    <td>อัตราการไหล (ลบ.ม/วินาที)</td>
                    <td>4.99</td>
                    </tr>
                    <tr>
                    <td>ปริมาณน้ำสะสม (ลบ.ม.)</td>
                    <td>994290.00</td>
                    </tr>
                    <tr>
                    <td>วัน/เวลา</td>
                    <td>2018-04-10 10:24:41</td>
                    </tr>';

        
        $datatable2='<tr>
                    <td>อัตราการไหล (ลบ.ม/ชม.)</td>
                    <td>547</td>
                    </tr>
                    <tr>
                    <td>อัตราการไหล (ลบ.ม/วินาที)</td>
                    <td>0.15</td>
                    </tr>
                    <tr>
                    <td>ปริมาณน้ำสะสม (ลบ.ม.)</td>
                    <td>834637.00</td>
                    </tr>
                    <tr>
                    <td>วัน/เวลา</td>
                    <td>2018-04-10 10:30:22</td>
                    </tr>';


        $datatable3='<tr>
                    <td>อัตราการไหล (ลบ.ม/ชม.)</td>
                    <td>9998</td>
                    </tr>
                    <tr>
                    <td>อัตราการไหล (ลบ.ม/วินาที)</td>
                    <td>2.78</td>
                    </tr>
                    <tr>
                    <td>ปริมาณน้ำสะสม (ลบ.ม.)</td>
                    <td>160682.00</td>
                    </tr>
                    <tr>
                    <td>วัน/เวลา</td>
                    <td>2018-04-10 10:30:56</td>
                    </tr>';
     



        $data['ppn'] = 'PPN04 อำเภอชะอวด';
        $data['title1'] = 'ปริมาณน้ำผ่านท่อทั้งหมด';
        $data['datatable1'] = $datatable1;
        $data['title2'] = 'ปริมาณน้ำผ่านท่อนิคมฯ';
        $data['datatable2'] = $datatable2;
        $data['title3'] = 'ปริมาณน้ำผ่านท่อลงคลอง';
        $data['datatable3'] = $datatable3;

        $this->load->view('layout/header_view');
        $this->load->view('quantitywater_view',$data);
        $this->load->view('layout/footer_view');


    }// fn.ppn04



    public function ppn05(){

        $datatable1='<tr>
                    <td>อัตราการไหล (ลบ.ม/ชม.)</td>
                    <td>11596</td>
                    </tr>
                    <tr>
                    <td>อัตราการไหล (ลบ.ม/วินาที)</td>
                    <td>4.99</td>
                    </tr>
                    <tr>
                    <td>ปริมาณน้ำสะสม (ลบ.ม.)</td>
                    <td>994290.00</td>
                    </tr>
                    <tr>
                    <td>วัน/เวลา</td>
                    <td>2018-04-10 10:24:41</td>
                    </tr>';

        
        $datatable2='<tr>
                    <td>อัตราการไหล (ลบ.ม/ชม.)</td>
                    <td>547</td>
                    </tr>
                    <tr>
                    <td>อัตราการไหล (ลบ.ม/วินาที)</td>
                    <td>0.15</td>
                    </tr>
                    <tr>
                    <td>ปริมาณน้ำสะสม (ลบ.ม.)</td>
                    <td>834637.00</td>
                    </tr>
                    <tr>
                    <td>วัน/เวลา</td>
                    <td>2018-04-10 10:30:22</td>
                    </tr>';


        $datatable3='<tr>
                    <td>อัตราการไหล (ลบ.ม/ชม.)</td>
                    <td>9998</td>
                    </tr>
                    <tr>
                    <td>อัตราการไหล (ลบ.ม/วินาที)</td>
                    <td>2.78</td>
                    </tr>
                    <tr>
                    <td>ปริมาณน้ำสะสม (ลบ.ม.)</td>
                    <td>160682.00</td>
                    </tr>
                    <tr>
                    <td>วัน/เวลา</td>
                    <td>2018-04-10 10:30:56</td>
                    </tr>';
     



        $data['ppn'] = 'PPN05 บ้านท้ายทะเล';
        $data['title1'] = 'ปริมาณน้ำผ่านท่อทั้งหมด';
        $data['datatable1'] = $datatable1;
        $data['title2'] = 'ปริมาณน้ำผ่านท่อนิคมฯ';
        $data['datatable2'] = $datatable2;
        $data['title3'] = 'ปริมาณน้ำผ่านท่อลงคลอง';
        $data['datatable3'] = $datatable3;

        $this->load->view('layout/header_view');
        $this->load->view('quantitywater_view',$data);
        $this->load->view('layout/footer_view');


    }// fn.ppn05



    public function ppn06(){

        $datatable1='<tr>
                    <td>อัตราการไหล (ลบ.ม/ชม.)</td>
                    <td>11596</td>
                    </tr>
                    <tr>
                    <td>อัตราการไหล (ลบ.ม/วินาที)</td>
                    <td>4.99</td>
                    </tr>
                    <tr>
                    <td>ปริมาณน้ำสะสม (ลบ.ม.)</td>
                    <td>994290.00</td>
                    </tr>
                    <tr>
                    <td>วัน/เวลา</td>
                    <td>2018-04-10 10:24:41</td>
                    </tr>';

        
        $datatable2='<tr>
                    <td>อัตราการไหล (ลบ.ม/ชม.)</td>
                    <td>547</td>
                    </tr>
                    <tr>
                    <td>อัตราการไหล (ลบ.ม/วินาที)</td>
                    <td>0.15</td>
                    </tr>
                    <tr>
                    <td>ปริมาณน้ำสะสม (ลบ.ม.)</td>
                    <td>834637.00</td>
                    </tr>
                    <tr>
                    <td>วัน/เวลา</td>
                    <td>2018-04-10 10:30:22</td>
                    </tr>';


        $datatable3='<tr>
                    <td>อัตราการไหล (ลบ.ม/ชม.)</td>
                    <td>9998</td>
                    </tr>
                    <tr>
                    <td>อัตราการไหล (ลบ.ม/วินาที)</td>
                    <td>2.78</td>
                    </tr>
                    <tr>
                    <td>ปริมาณน้ำสะสม (ลบ.ม.)</td>
                    <td>160682.00</td>
                    </tr>
                    <tr>
                    <td>วัน/เวลา</td>
                    <td>2018-04-10 10:30:56</td>
                    </tr>';
     



        $data['ppn'] = 'PPN06 ปตร.คลองชะอวด-แพรกเมือง';
        $data['title1'] = 'ปริมาณน้ำผ่านท่อทั้งหมด';
        $data['datatable1'] = $datatable1;
        $data['title2'] = 'ปริมาณน้ำผ่านท่อนิคมฯ';
        $data['datatable2'] = $datatable2;
        $data['title3'] = 'ปริมาณน้ำผ่านท่อลงคลอง';
        $data['datatable3'] = $datatable3;

        $this->load->view('layout/header_view');
        $this->load->view('quantitywater_view',$data);
        $this->load->view('layout/footer_view');


    }// fn.ppn06





}