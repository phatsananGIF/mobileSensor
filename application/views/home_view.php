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
        categories: ['06:00','08:00','10:00','12:00','14:00','16:00','18:00','20:00','22:00','00:00','02:00','04:00','06:00']
    },

    yAxis: {
        title: {
            text: 'ม.รทก.'
        }
    },

    legend: {
        verticalAlign: 'top'
    },

    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },

     series: [{
        name: 'PPN01 เขื่อนห้วยน้ำใส',
        color:'#c80000',
        data: [159,90,109,86,173,236,71,159,90,109,86,173,236,]
    }, {
        name: 'PPN02 ฝายคลองไม้เสียบ',
        color:'#0057ae',
        data: [119,80,110,95,180,210,65,140,80,111,75,126,220,]
    }, {
        name: 'PPN04 อำเภอชะอวด',
        color:'#ffc107',
        data: [129,70,120,96,133,222,56,130,70,120,65,172,210,]
    }, {
        name: 'PPN05 บ้านท้ายทะเล',
        color:'#28a745',
        data: [139,66,105,47,158,240,45,120,60,130,56,162,200,]
    }, {
        name: 'PPN06 ปตร.คลองชะอวด-แพรกเมือง',
        color:'#17a2b8',
        data: [109,50,111,67,165,215,40,110,50,140,55,153,230,]
    }]
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
        categories: ['06:00','08:00','10:00','12:00','14:00','16:00','18:00','20:00','22:00','00:00','02:00','04:00','06:00']
    },

    yAxis: {
        title: {
            text: 'ลบ.ม./วินาที'
        }
    },

    legend: {
        verticalAlign: 'top'
    },

    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },

     series: [{
        name: 'PPN01 เขื่อนห้วยน้ำใส',
        color:'#c80000',
        data: [159,90,109,86,173,236,71,159,90,109,86,173,236,]
    }, {
        name: 'PPN02 ฝายคลองไม้เสียบ',
        color:'#0057ae',
        data: [119,80,110,95,180,210,65,140,80,111,75,126,220,]
    }, {
        name: 'PPN04 อำเภอชะอวด',
        color:'#ffc107',
        data: [129,70,120,96,133,222,56,130,70,120,65,172,210,]
    }, {
        name: 'PPN05 บ้านท้ายทะเล',
        color:'#28a745',
        data: [139,66,105,47,158,240,45,120,60,130,56,162,200,]
    }, {
        name: 'PPN06 ปตร.คลองชะอวด-แพรกเมือง',
        color:'#17a2b8',
        data: [109,50,111,67,165,215,40,110,50,140,55,153,230,]
    }]
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
        categories: ['06:00','08:00','10:00','12:00','14:00','16:00','18:00','20:00','22:00','00:00','02:00','04:00','06:00']
    },

    yAxis: {
        title: {
            text: 'มม.'
        }
    },

    legend: {
        verticalAlign: 'top'
    },

    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },

     series: [{
        name: 'PPN01 เขื่อนห้วยน้ำใส',
        color:'#c80000',
        data: [159,90,109,86,173,236,71,159,90,109,86,173,236,]
    }, {
        name: 'PPN02 ฝายคลองไม้เสียบ',
        color:'#0057ae',
        data: [119,80,110,95,180,210,65,140,80,111,75,126,220,]
    }, {
        name: 'PPN04 อำเภอชะอวด',
        color:'#ffc107',
        data: [129,70,120,96,133,222,56,130,70,120,65,172,210,]
    }, {
        name: 'PPN05 บ้านท้ายทะเล',
        color:'#28a745',
        data: [139,66,105,47,158,240,45,120,60,130,56,162,200,]
    }, {
        name: 'PPN06 ปตร.คลองชะอวด-แพรกเมือง',
        color:'#17a2b8',
        data: [109,50,111,67,165,215,40,110,50,140,55,153,230,]
    }]
});//end chart rainfall

</script>