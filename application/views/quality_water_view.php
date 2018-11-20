<div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">คุณภาพน้ำ <?= $ppn ?></li>
      </ol>

      <!-- data last query wq-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-shield"></i> <?= $ppn ?></div>
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
          <i class="fa fa-shield"></i> <?= $ppn ?></div>
        <div class="card-body">

            <?php echo form_open('quality_water/site/'.$sitecode);?>
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

            <div id="wq_Allin" style="width: 100%; height: 400px; margin-bottom: 20px; margin-top: 20px;"></div>


        </div>
      </div>


</div>
<!-- /.container-fluid-->

<!-- highchartsr -->
<script src="<?=base_url()?>asset/vendor/Highcharts/code/highcharts.js"></script>
<script src="<?=base_url()?>asset/vendor/Highcharts/code/modules/exporting.js"></script>


<script type="text/javascript">

   var options = {
        chart: {
            renderTo: 'wq_Allin',
            zoomType: 'x',
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

        yAxis: [{ // waterTem
            labels: {
                format: '',
                style: {
                    color: '#ff6600'
                }
            },
            title: {
                text: '',
                style: {
                    color: '#ff6600'
                }
            }

        }, { // waterpH
            gridLineWidth: 0,
            title: {
                text: '',
                style: {
                    color: '#0077FF'
                }
            },
            labels: {
                format: '',
                style: {
                    color: '#0077FF'
                }
            }

        }, { // waterDO
            gridLineWidth: 0,
            title: {
                text: '',
                style: {
                    color: '#00cc00'
                }
            },
            labels: {
                format: '',
                style: {
                    color: '#00cc00'
                }
            }

        }, { // waterEC
            gridLineWidth: 0,
            title: {
                text: '',
                style: {
                    color: '#999966'
                }
            },
            labels: {
                format: '',
                style: {
                    color: '#999966'
                }
            }
        }],

        legend: {
            verticalAlign: 'top'
        },

        tooltip: {
            shared: true,
            xDateFormat: '<b>%Y-%m-%d %H:%M:%S<b>',
        },
        series: []

  };



  var dataseries = <?php echo json_encode($dataseries); ?>;
  
  dataseries.forEach(function(entry) {

    //options.chart.renderTo = entry[0];//ตำแหน่งว่างกราฟ
    //options.yAxis[0].title.text = entry[1][0]['name'];//ชื่อหัว
    options.series = entry[1];
    var chart = new Highcharts.Chart(options);
  });




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