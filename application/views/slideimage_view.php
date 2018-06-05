<div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">ภาพนิ่ง</li>
      </ol>
      
      <div class="card mb-3">
        <div class="card-header">
      
            <div class="row">

              <div class="col-xs-6 col-md-4">
                  <select class="form-control" id="selected_sitecode" >
                    <?php foreach($rsquery as $side){ ?>
                      <option value="<?php echo  $side['devicesID'];?>"><?php echo  $side['sitename']." ".$side['location'];?></option>
                    <?php } ?>
                  </select>
              </div>
              
              <div class="col-xs-6 col-md-4">
                  <input type="text" id="datepicker" class="form-control"  />
              </div>
              <div class="col-xs-6 col-md-4">
                  <button type="button" onclick="submit()" class="btn btn-primary " >Search</button>
              </div>

            </div><!-- row -->      
            
        </div><!-- card-header --> 

      </div><!-- card mb-3 --> 

      <div id="gallery" style="visibility:hidden"></div>

</div>
<!-- /.container-fluid-->


<!-- Include Required Prerequisites -->
<script type="text/javascript" src="<?=base_url()?>asset/vendor/DateRangePicker/moment.min.js"></script>
<!-- Include Date Range Picker -->
<script type="text/javascript" src="<?=base_url()?>asset/vendor/DateRangePicker/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>asset/vendor/DateRangePicker/daterangepicker.css" />

<script type="text/javascript">



  function submit() {
    var selected_sitecode = document.getElementById("selected_sitecode").value;
    var datepicker = document.getElementById("datepicker").value;

    dataI={"selected_sitecode":selected_sitecode, "datepicker":datepicker};

    $.ajax({ 
      type:"POST",
      url:'<?=base_url("slideImage/search")?>',
      cache:false,
      dataType:"JSON",
      data:dataI,
      async:true,
      success:function(result){
          //alert('มา '+result);
          console.log(result);
          if(result=="not"){
            alert("not image");
            document.getElementById('gallery').style.visibility = "hidden";
          }else{

            $("#gallery").html(result);
            jQuery("#gallery").unitegallery();
            document.getElementById('gallery').style.visibility = "visible";
          }

      }//end success
    });//end $.ajax 

    
  }//end f.submit



  $(function() {
    $('#datepicker').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'YYYY/MM/DD'
        }
    });

  });//end f.daterangepicker






</script>