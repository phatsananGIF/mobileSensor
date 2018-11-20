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
    

    public function cal_gate($data)
 {
	 /*
	 ppn06
	 $data=array(
		'siteid'=>'',
		'us'=>0,
		'ds'=>0,
		'gate'=>'5',
		's_earth=>'-5',
	 );
	 */
	 
	 $C=0.583;
	 $W=12.5;
	 $H=floatval($data['us'])-floatval($data['ds']);
	 $D=floatval($data['us'])-floatval($data['s_earth']);
	 $d=floatval($data['ds'])-floatval($data['s_earth']);
	 $g=9.81;
	 
	 $Q=0.00;
	 $F=0;
	 switch ($data['siteid']) {
		case 'LP01': //test
		case 'PPN06':
			if( ($D > floatval($data['us']))  and ($D > floatval($data['ds'])) ){
				//กรณีที่ 1
				//Q = n*C*W*D*sqrt[(2gH)/(1-(C*d/D)^2)
				$Q=$data['gate'] * $C * $W * $D * sqrt(2*$g*$H)/(1-($C*$d/$D)^2) ;
				$F=1;
			}else{
				//กรณีที่ 2
				//Q = n*C*W*D*sqrt(2gH)
				$Q= $data['gate']*$C*$W*$D*sqrt(2*$g*$H);
				$F=2;
			} 
			
			if( (floatval($data['s_earth']) > floatval($data['us']))  or ( floatval($data['s_earth']) > floatval($data['ds'])) ){
				$Q="0.00";
			}
		
		break;
		default:
			$Q=0.00;
	 }	
	 return array('siteid'=>$data['siteid'],'value'=>$Q,'F'=>$F);
 }//end function cal_gate()
 
 
 public function water_pass_gate($data)
 {
		/*
		ppn02
			$data=array(
				'sitecode'=>'',
				'gate'=> id ประตู,
				'D'=>ระยะเปิดบาน,
				'us'=>,
				'ds'=>,
			);
		*/
		$C=0.7;
		$W=6;
		$D=$data['D'];
		$g=9.81;
		$H=floatval($data['us']) - floatval($data['ds']);
		
		$Q=$C*$W*$D * sqrt(2*$g*$H);
		
		
		return array('sitecode'=>$data['siteid'],'value'=>$Q);
		
 }//End function water_pass_gate
 
 
 public function cal_PPN04_s($num){
	echo "<!-- cal_PPN04_s  $num //-->\n";
	switch ($num) {
		  case 0: 
				$Q=0;
				break;
		  case 1: 
				$Q=12;
				break;
		  case 2: 
				$Q=25;
				break;
		  case 3: 
				$Q=60;
				break;
		  case 4: 
				$Q=100;
				break;
		  case 5: 
				$Q=165;
				break;
		  case 6:
				$Q=240;
				break;
		  case 7: 
				$Q=325;
				break;
		  case 8:
				$Q=420;
				break;
		  case 9:
				$Q=545;
				break;
		  case 10:
				$Q=680;
				break;
		  default :
			$Q=$num;
	  }
	  
	  return $Q;
 }
 
 public function cal_PPN04($data)
 {
	 /*
		$data=array(
			'us'=>'',
			'ds'=>'',
		)
	 */
	 $num_interger=(int)$data['us'];
	 $num_min=(int)$num_interger;
	 $num_max=(int)$num_interger+1;
	 $c=array();
	 $Q="N/A";
	 for($i=$num_min ;$i <=$num_max;$i+=0.01){
		$a=$this->cal_PPN04_s("$i");
		$ii=number_format($i,2);
		$c["$ii"]=$a;

		 if(!is_int($a)) {
			 //$c[$i]=$c[$i-0.1] + ( ((int)$i + 1) - $c[(int)$i - 1 ])/10 ;
			 $t= number_format(($i - 0.01),2) ;
			 // echo "<!-- T = $t //-->";
			 if(isset($c["$t"])){
				 $min=$c["$t"];
				 
				 $m=(int)$i  + 1; 
				 echo "<!-- M = $m  , $min//-->"; 
				 $max=$this->cal_PPN04_s("$m");
				 $m=(int)$i - 1;
				 if($m < $num_min) $m=$num_min;
				 $m=number_format($m,2);
				 $min1=$c["$m"];
				 
				 echo  "<!-- $i -> $t ->  $min + ( $max - $min1 ) /100 //-->\n" ;
				 $ii=number_format($i,2);
				 $c["$ii"]=$min + ($max - $min1)/100;
				 //$r=number_format($data['us'],2);
				 //$Q=$c["$r"];
				 $Q="0";
			}//else{
			//	$Q="N/A";
			//}
		 }
			 
	 }
	 
	 echo "<!-- " ; print_r($c); echo "//-->\n";
	 
	 echo "<!-- Q = $Q //-->";

	 if($Q!="N/A"){
		$r=number_format($data['us'],2);
		echo "<!-- R = $r //-->\n";
		$Q=$c["$r"];
	 }else{
		 $Q=0;
	 }
	  return $Q;
 }
 
 public function cal_PPN05_s($num)
 {
	$Q=-1;
	
	 switch ($num) {
		  case "0.00": 
				$Q='0.00';
				break;
		  case "1.00": 
				$Q=35.55;
				break;
		  case "2.00": 
				$Q=113.33;
				break;
		  case "3.00": 
				$Q=224.57;
				break;
		  case "4.00": 
				$Q=365.15;
				break;
		  case "5.05": 
				$Q=542.45;
				break;
		  case "6.00":
				$Q=727.96;
				break;
		  case "10.00":
				$Q=2500.00;
				break;
	  }
	  
	  return $Q;
	 
 }
 
 public function cal_PPN05($data)
 {
	 $num_interger=number_format($data['us'],2);
	 $num_min=number_format((int)$num_interger,2);
	 if($num_min < 0) $num_min = 0.00;
	 
	 $num_max=number_format((int)$num_interger+1.01,2);
			  
	 $c=array();
	 
	 for($i=$num_min ;$i <=$num_max;$i+=0.01){
		
	
		
		$a=$this->cal_PPN05_s("$i");

	
			
		$ii=number_format($i,2);
		
		$c["$ii"]=number_format($a,2);
		
		 if( $a == -1) {
			 $a=$i;
			 //	echo "$i = $a \n";

			 $t= number_format(($i - 0.01),2) ;
			
			if( isset($c["$t"]) ){ 
			  $min=$c["$t"];
			 }else{
			  return 0;
			 }

			 $m= (int)$i  + 1; 
			 $m=number_format($m,2);
			 
			 $max=$this->cal_PPN05_s("$m");
			 $m=(int)$i - 1;
			 if($m < $num_min) $m=$num_min;
			 $m=number_format($m,2);
			 $min1=$c["$m"];
			 
			// echo  "$i -> $t ->  $min + ( $max - $min1 ) /10<br>\n" ;
			 $ii=number_format($i,2);
			 $c["$ii"]=$min + ($max - $min1)/100;
		 }
			 
	 }
	 
	 //print_r($c);
	 
	 $r=number_format($data['us'],2);
	 
	 $Q="0";
	 if(isset($c["$r"])) $Q=$c["$r"];
	 
	  return $Q;
	 
 }


    
	
}//end class