<?php
 
class Model_cal extends CI_Model { 
    public function __construct() {
		parent::__construct();
		
	}  

    public function runcal($data){
       
        $Q=0;
        $SITECODE="";
        $formula="";
        switch ($data['sitecode']) {
            case 'A001': //TEST
           case 'PPN02':
                   $SITECODE=$data['sitecode'];
                   $C=0.65;
                   $W=40;
                   $g=9.81;
                   $D=$data['us']-(+21.5);
                   if((floatval($data['ds'])<21.5) and ($D >0))  {
                       //กรณีที่ 1 ระดับน้ำท้ายฝายต่ำกว่าระดับสันฝาย (กรณี D เป็นค่าบวก และระดับน้ำท้ายฝายต่ำกว่าระดับสันฝาย)
                        $Q=(2/3)*$C*$W*pow($D,1.5)*SQRT(2*$g);
                        $formula=$data['us'] . ";$Q=(2/3)*$C*$W*pow($D,1.5)*SQRT(2*$g)";
                   }
                   if((floatval($data['us'])<21.5) and ($D <=0))  {
                       //กรณีที่ 2 ระดับน้ำหน้าฝาย ต่ำกว่าระดับสันฝาย (กรณี D เป็นค่าลบ และระดับน้ำท้ายฝายต่ำกว่าระดับสันฝาย)
                        $Q=0;
                       $formula="2"; 
                   }
                   
                   if(floatval($data['ds'])>21.5)  {
                       //กรณีที่ 3 ระดับน้ำท้ายฝายสูงกว่าระดับสันฝาย
                       $H=floatval($data['us']) - floatval($data['ds']);
                       $Q=$C*$W*sqrt(2*$g*$H)*($D-$H/3);
                       $formula="3";
                   }
                   break;
           case 'PPN01':
                       $SITECODE=$data['sitecode'];
                       $C=0.1209;
                       $D=14.704;
                       $E=450.65;
                       $value=floatval($data['us']);
                       $V=pow($value,2);
           
                       $Q=($C*$V)-$D*$value+$E;
                       $formula="($C*$V)-$D*$value+$E";
                   
                   break;
           default:
               //default wl
               $Q=0;	
         }
        return array('sitecode'=>$SITECODE,'value'=>$Q,'formula'=>$formula);

    }//fn.runcal
    



    
	
}//end class