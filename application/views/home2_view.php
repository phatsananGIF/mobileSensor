<style>

.mybox_name {
    position: absolute;
    -webkit-border-radius: 50px;
    padding: 5px;
    font-size: 10px;
    z-index:1;
}

.mypop {
    position:absolute;
    background-color:#84878a;
    color:white;
    /*font-size:0.1em;*/
    padding:5px;
    -webkit-border-radius:10px;
    z-index:1;
    font-size: 1.3vw;
    /*font-size: 0.8vh;*/
}

.dot {
    height: 2ex;
    width: 2ex;
    border-radius: 50%;
    display: inline-block;
}

</style>



<div class="container-fluid">

        <ol class="breadcrumb" style="background-color: #f7f7f7;">
            <div class="row">
                <div class="col-6"> ข้อมูลโดยรวม </div>
                <div align="right" class="col-6">
                    <button id="foo" type="button" class="btn btn-outline-secondary btn-sm">download image</button>
                </div>
            </div>        
        </ol>
        
        
        <div id="my-node" class="card-body" style="background-color: #dddfe2;padding: 0px 0px; position:relative;">

            <img src="<?=base_url()?>asset/img/line10.png" width="100%" height="100%">
            <div id='one_date' style="top:17.5%; left:41%; background-color:#ecd858; color:#000;" class="mypop"></div>
            <div id="ipop_PPN01" style="top:66%; left:0%;" class="mypop"></div>
            <div id="ipop_PPN02" style="top:68%; left:35%;" class="mypop"></div>
            <div id="ipop_PPN04" style="top:58%; left:50%;" class="mypop"></div>
            <div id="ipop_PPN05" style="top:37%; left:75%;" class="mypop"></div>
            <div id="ipop_PPN06" style="top:58%; left:75%;" class="mypop"></div>
            <div id="ipop_PPN08" style="top:30%; left:10%;" class="mypop"></div>
            <div id="ipop_PPN09" style="top:85%; left:4%;" class="mypop"></div>
            <div id="ipop_PPN10" style="top:45%; left:14%;" class="mypop"></div>
            <div id="ipop_PPN11" style="top:17%; left:0%;" class="mypop"></div>
            <div id="ipop_PPN12" style="top:37%; left:52%;" class="mypop"></div>

            <div style="top:90%; left:75%; background-color:#f8f9fa; color:#000;  font-size:1vw" class="mypop">
                <div style="text-align:center;">สัญลักษณ์</div>
                <table>
                    <tr>
                        <td>ระบบสื่อสารปกติ</td>
                        <td><span class="dot" style="background-color:green;"></span></td>
                        <td>สถานี</td>
                        <td><span class="dot" style="background-color:green;"></span></td>
                        <td>ระดับน้ำปกติ</td>
                    </tr>
                    <tr>
                        <td>ขัดข้อง>15นาที</td>
                        <td><i class="fa fa-wifi fa-lg" style="color:orange;"></i></td>
                        <td>สถานี</td>
                        <td><i class="fa fa-circle fa-lg" style="color:orange;"></i></td>
                        <td>ระดับน้ำสูง</td>
                    </tr>
                    <tr>
                        <td>ขัดข้อง>60นาที</td>
                        <td><i class="fa fa-wifi fa-lg" style="color:red;"></i></td>
                        <td>สถานี</td>
                        <td><i class="fa fa-circle fa-lg" style="color:red;"></i></td>
                        <td>ระดับน้ำวิกฤติ </td>
                    </tr>
                </table>            
                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
            </div>

        </div>
   
    <div id="previewImage" style="display: none;"> </div>

</div>
<!-- /.container-fluid-->




<script type="text/javascript">

    var getCanvas;

    $(function() {
        view_Communications();
        
    })


    function view_Communications(){

        console.log('view_Communications');

        $.ajax({
            type:"POST",
            url:'<?=base_url("home2/getdata_com")?>',
            cache:false,
            dataType:"JSON",
            async:true,
            success:function(result_com){
                console.log(result_com);

                //--foreach--//
                var sitecode = "";
                result_com.forEach(function(entry) {

                    $('#one_date').html('<b>'+entry.sensor_dt+'</b>');//วันที่

                    var dataCourtStatus = '';

                    var myElem = document.getElementById('name_'+entry.sitecode);
                        if (myElem === null){
                            dataCourtStatus += '<div id="name_'+entry.sitecode+'"> <span class="dot" style="background-color:'+entry.network_color+';"></span> <strong>'+entry.sitecode+' '+entry.sitename+'</strong> <span class="dot" style="background-color:'+entry.sensor_color+';"></span>  </div>';
                        }
                        
                        $(dataCourtStatus).appendTo("#ipop_"+entry.sitecode);
                });



                var data_all_sensor = <?php echo json_encode($data_all_sensor); ?>;
                //--foreach--//
                data_all_sensor.forEach(function(entry) {
                    $(entry.data_all_site).appendTo("#ipop_"+entry.sitecode);
                });

                    
                html2canvas(document.querySelector("#my-node")).then(canvas => {
                    $("#previewImage").html(canvas);
                    getCanvas = canvas;
                });

                
            }//end success
        });//end $.ajax 


    }//f.view_Communications




    var btn = document.getElementById('foo');
    btn.onclick = function() {

        canvas = getCanvas;
        canvas.toBlob(function(blob) {
            saveAs(blob, "my-diagram.png");
        });

    }



</script>
