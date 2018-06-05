<div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">ระบบสื่อสาร</li>
      </ol>


  <div id="databox">
  </div>


   
        

</div>
<!-- /.container-fluid-->



<script type="text/javascript">

  $(document).ready(function(){
    var sensorall = <?php echo json_encode($sensorall); ?>;

    //--foreach--//
    var sitecode = "";
    sensorall.forEach(function(sensor){
      console.log(sensor);

      if(sensor.sitecode != sitecode){

          var dataCourtStatus = '<div class="card mb-3">';
              dataCourtStatus += '<div class="card-header">';
              dataCourtStatus += '<i class="fa fa-podcast"></i> '+sensor.sitecode+' '+sensor.sitename+'</div>';
              dataCourtStatus += '<div class="list-group list-group-flush small">';
              dataCourtStatus += '<div class="list-group-item" >';
              dataCourtStatus += '<div class="media">';
              dataCourtStatus += '<div class="media-body" >';
              dataCourtStatus += '<div id="'+sensor.sitecode+'">'+sensor.name_location+' '+sensor.network_label+' <strong style="color: '+sensor.network_color+';">'+sensor.network_level+'</strong> </div>';
              dataCourtStatus += '<div id="'+sensor.sitecode+'WL">'+sensor.sensor_label+' '+sensor.sensor_cal+' <strong style="color: '+sensor.sensor_color+';">'+sensor.sensor_status+'</strong> <p>'+sensor.sensor_dt +'</p></div>';
              dataCourtStatus += '</div>';
              dataCourtStatus += '</div>';
              dataCourtStatus += '</div>';
              dataCourtStatus += '</div>';
              dataCourtStatus += '</div>';
              $(dataCourtStatus).appendTo("#databox");

        sitecode=sensor.sitecode

      }else{

        $('<div>'+sensor.name_location+' '+sensor.network_label+' <strong style="color: '+sensor.network_color+';">'+sensor.network_level+'</strong></div>').appendTo("#"+sensor.sitecode+"");

        if(sensor.sitecode != 'PPN06'){
          $("#"+sensor.sitecode+"WL").html('<div>'+sensor.sensor_label+' '+sensor.sensor_cal+' <strong style="color: '+sensor.sensor_color+';">'+sensor.sensor_status+'</strong> <p>'+sensor.sensor_dt +'</p></div>');
        }

      }


    }); //end foreach
   



  });// end document.ready
  


</script>