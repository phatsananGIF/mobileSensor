<div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">ปริมาณน้ำ <?= $ppn ?></li>
      </ol>

      <?php 
      $sitecode="[]";
      foreach( $sitesdevice as $siteWF){
        $sitecode = $siteWF['sitecode'];
        $sitecode = "[['$sitecode']]";
        
      ?>

      <!-- data -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-industry"></i> <?= $siteWF['label'] ?></div>

          <div class="list-group list-group-flush small">
              <div class="table-responsive">
                <table class="table">
                  <tbody>

                    <tr>
                      <td>อัตราการไหล (ลบ.ม/ชม.)</td>
                      <td><div id="<?php echo $siteWF['sitecode']?>_<?php echo $siteWF['input_value']?>raw"></div></td>
                    </tr>

                    <tr>
                      <td>อัตราการไหล (ลบ.ม/วินาที)</td>
                      <td><div id="<?php echo $siteWF['sitecode']?>_<?php echo $siteWF['input_value']?>"></div></td>
                    </tr>

                    <tr>
                      <td>ปริมาณน้ำสะสม (ลบ.ม.)</td>
                      <td><div id="<?php echo $siteWF['sitecode']?>_<?php echo $siteWF['input_value']?>total"></div></td>
                    </tr>

                    <tr>
                      <td>วัน/เวลา</td>
                      <td><div id="<?php echo$siteWF['sitecode']?>_<?php echo $siteWF['input_value']?>date"></div></td>
                    </tr>

                  </tbody>
                </table>
              </div> 
          </div>
      </div>

      <?php } ?>


</div>
<!-- /.container-fluid-->


<script type="text/javascript">
console.log(<?php echo $sitecode; ?>);

  $(function() {
  
  function add2zero(num)
  {
    if(num <= 9) {
       return "0"+num;
    }else{
      return num;
    }
  }
    
  function flows(){		
         var flow=<?php echo $sitecode?>;
       console.log("sitecode => "+flow);
       //ajax 
      function get_value(code){
          url="<?php echo site_url('flow/flow_value')?>/" + code ;
          console.log("dddd => "+url);
        
           $.ajax({
                  type: "POST",
                  contentType: "application/json; charset=utf-8",
                  url: url,
                  success: function (data){
            console.log("ข้อมูลกลับมา => "+data);
  
                      var obj= eval("(" + data + ")");
            console.log("ผ่าน eval => "+obj);
  
                      $.each( obj, function(i,data) {
                        F1=parseFloat(data.F1)/3600
                        F2=parseFloat(data.F2)/3600
                        F3=(parseFloat(data.F1) - parseFloat(data.F2)) /3600;
                        
                        T1=parseFloat(data.T1);
                        T2=parseFloat(data.T2);
                        T3=parseFloat(data.T1) -parseFloat(data.T2) ;
                        
              $('#'+ code + '_f1').html(F1.toFixed(2));
              $('#'+ code + '_f2').html(F2.toFixed(2));
              $('#'+ code + '_f1f2').html(F3.toFixed(2));
              
              $('#'+ code + '_f1raw').html(data.F1);
              $('#'+ code + '_f2raw').html(data.F2);
              $('#'+ code + '_f1f2raw').html((parseFloat(data.F1) - parseFloat(data.F2)));
              
              $('#'+ code + '_f1total').html(T1.toFixed(2));
              $('#'+ code + '_f2total').html(T2.toFixed(2));
              $('#'+ code + '_f1f2total').html(T3.toFixed(2));
              
              var date = new Date();
              
                dt=date.getFullYear() + "-" + add2zero(date.getMonth())  + "-" + add2zero(date.getDate()) + 
                  " " + add2zero(date.getHours()) + ":" +  add2zero(date.getMinutes()) + ":" + add2zero(date.getSeconds());
              
              $('#'+ code + '_f1date').html(dt);
              $('#'+ code + '_f2date').html(dt);
              $('#'+ code + '_f1f2date').html(dt);
              
              
                  
            });	
          }, 
          error: function (result) {
            console.log(result);
                  }  
      }); //End ajax
      }   
       
       for (var i = 0; i < flow.length; ++i) {
         console.log("flow=> "+flow[i]);
        get_value(flow[i]);
        }
   }
   
   function update_timer() {
       flows();
       /*
       //ทำซ้ำทุก 1นาที
       setTimeout(function(){
        update_timer()
      },3000);
      */
   }  
  
  
   update_timer();
   
  });
  
  
  
  
   
  </script>