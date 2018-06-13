<div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">ปริมาณน้ำฝน</li>
      </ol>


      <!-- data -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-tint"></i> <?= $ppn ?></div>
          <div class="list-group list-group-flush small">
              <div class="table-responsive">
                <table class="table">
                  <tbody>
                    <?= $datatable ?>
                  </tbody>
                </table>
              </div> 
          </div>
      </div>


      <!-- Area Chart-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-tint"></i> <?= $ppn ?></div>
        <div class="card-body">

            <?php echo form_open('Rainfall/site/'.$sitecode);?>
                <div class="row">
                    <div class="col-xs-6 col-md-3">
                        <select class="form-control" id="timeRange" name="timeRange" >
                            <?php foreach($timeRange as $key=>$rang){ 
                                if($selectedtimeRange == $key){
                                    $selected = "selected";
                                }else{
                                    $selected = "";
                                }
                            ?>
                                <option value="<?php echo $key;?>" <?= $selected ?>><?php echo $rang;?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-xs-6 col-md-3">
                        <input type="text" id="datepicker1" name="datepicker1"  class="form-control" value="<?= $datepicker1 ?>" />
                    </div>
                    <div class="col-xs-6 col-md-3">
                        <input type="text" id="datepicker2" name="datepicker2"  class="form-control" value="<?= $datepicker2 ?>" />
                    </div>

                    <div class="col-xs-6 col-md-3">
                        <button type="submit" name="btsearch" class="btn btn-primary" value="Search" >Search</button>
                    </div>
                </div>
            <?php echo form_close();?>

            <div id="rainfall" style="width: 100%; height: 400px; margin-bottom: 20px; margin-top: 20px;"></div>
        </div>
      </div>
     
</div>
<!-- /.container-fluid-->

<script src="<?=base_url()?>asset/vendor/Highcharts/code/highcharts.js"></script>
<script src="<?=base_url()?>asset/vendor/Highcharts/code/modules/exporting.js"></script>

<script type="text/javascript">

    Highcharts.chart('rainfall', {
        chart: {
            zoomType: 'x'
        },
        title: {
            text: ''
        },
       
        xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: {
                day: '%Y-%m-%d'
            }
        },

        yAxis: [{
            reversed: true,
            title: {
                text: 'ปริมาณน้ำฝน (มม.)'
            }
        }, {
            opposite: true,
            title: {
                text: 'ปริมาณน้ำฝนสะสม (มม.)'
            }
        }],

        legend: {
            verticalAlign: 'top'
        },
        
        tooltip: {
            xDateFormat: '%Y-%m-%d %H:%M:%S',
            headerFormat:'{point.key}<br/>',
            pointFormat: '{series.name} : {point.y}'
        },

        series: [<?= $series ?>]


    });//end chart waterlevel




     $(function() {
        $('#datepicker1').daterangepicker({
            timePicker: true,
            singleDatePicker: true,
            locale: {
                format: 'YYYY-MM-DD HH:mm:ss'
            }
        });

        $('#datepicker2').daterangepicker({
            timePicker: true,
            singleDatePicker: true,
            locale: {
                format: 'YYYY-MM-DD HH:mm:ss'
            }
        });
    });//end f.daterangepicker
    

</script>