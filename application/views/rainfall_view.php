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
            type: 'datetime'
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

        plotOptions: {
            area: {
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                },
                marker: {
                    radius: 2
                },
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
                threshold: null
            }
        },

        series: [<?= $series ?>]


    });//end chart waterlevel

</script>