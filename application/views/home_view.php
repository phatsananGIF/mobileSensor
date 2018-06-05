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


      <!-- Area Chart ปริมาณน้ำ-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-industry"></i> ปริมาณน้ำ</div>
        <div class="card-body">
            <div id="quantitywater" style="width: 100%; height: 400px; margin-bottom: 20px; margin-top: 20px;"></div>
        </div>
      </div>


      <!-- Area Chart ปริมาณน้ำฝน-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-tint"></i> ปริมาณน้ำฝน</div>
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
        type: 'datetime'
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
        headerFormat: '<span><b>{point.key}</b></span> <br/>',
        pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:.2f}</b></span> <br/>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },

    series: [<?= $series ?>]

});//end chart waterlevel


Highcharts.chart('quantitywater', {
    chart: {
        type: 'line',
        zoomType: 'xy'
    },

    title: {
        text: ''
    },

    xAxis: {
        type: 'datetime'
    },

    yAxis: {
        title: {
            text: 'ลบ.ม./วินาที'
        }
    },

    legend: {
        verticalAlign: 'top'
    },

    tooltip: {
        headerFormat: '<span><b>{point.key}</b></span> <br/>',
        pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:.2f}</b></span> <br/>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },

    series: [<?= $series ?>]


});//end chart quantitywater



Highcharts.chart('rainfall', {
    chart: {
        type: 'line',
        zoomType: 'xy'
    },

    title: {
        text: ''
    },

    xAxis: {
        type: 'datetime'
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
        headerFormat: '<span><b>{point.key}</b></span> <br/>',
        pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:.2f}</b></span> <br/>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },

    series: [<?= $series ?>]


});//end chart rainfall

</script>