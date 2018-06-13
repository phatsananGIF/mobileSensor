<div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">ข้อมูลโดยรวม</li>
      </ol>

      <!-- Area Chart ระดับน้ำ-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-thermometer-half"></i> ระดับน้ำ</div>
        <div class="card-body">
            <div id="waterlevel" style="width: 100%; height: 400px; margin-bottom: 20px; margin-top: 20px;"></div>
        </div>
      </div>


      <!-- Area Chart ปริมาณน้ำฝน-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-tint"></i> ปริมาณน้ำฝนสะสม</div>
        <div class="card-body">
            <div id="rainfall" style="width: 100%; height: 400px; margin-bottom: 20px; margin-top: 20px;"></div>
        </div>
      </div>


    
</div>
<!-- /.container-fluid-->

<script src="<?=base_url()?>asset/vendor/Highcharts/code/highcharts.js"></script>
<script src="<?=base_url()?>asset/vendor/Highcharts/code/modules/exporting.js"></script>

<script type="text/javascript">

Highcharts.chart('waterlevel', {
    chart: {
        type: 'line',
        zoomType: 'xy'
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

    yAxis: {
        title: {
            text: 'ม.รทก.'
        }
    },

    legend: {
        verticalAlign: 'top'
    },

    tooltip: {
        shared: true,
        xDateFormat: '%Y-%m-%d',
        headerFormat: '<span>{point.key}</span> <br/>',
        pointFormat: '<span style="color:{series.color}">{series.name}: {point.y:.2f}</span> <br/>'
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },

    series: [<?= $seriesWL ?>]

});//end chart waterlevel




Highcharts.chart('rainfall', {
    chart: {
        type: 'line',
        zoomType: 'xy'
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

    yAxis: {
        title: {
            text: 'มม.'
        }
    },

    legend: {
        verticalAlign: 'top'
    },

    tooltip: {
        shared: true,
        xDateFormat: '%Y-%m-%d',
        headerFormat: '<span>{point.key}</span> <br/>',
        pointFormat: '<span style="color:{series.color}">{series.name}: {point.y:.2f}</span> <br/>'
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },

    series: [<?= $seriesRQ ?>]


});//end chart rainfall

</script>