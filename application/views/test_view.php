
<div class="container-fluid">
<div id="html-content-holder" style="background-color: #F0F0F1; color: #00cc65; width: 500px;
    padding-left: 25px; padding-top: 10px;">
    <strong>Codepedia.info</strong><hr/>
    <h3 style="color: #3e4b51;">
        Html to canvas, and canvas to proper image
    </h3>
    <p style="color: #3e4b51;">
        <b>Codepedia.info</b> is a programming blog. Tutorials focused on Programming ASP.Net,
        C#, jQuery, AngularJs, Gridview, MVC, Ajax, Javascript, XML, MS SQL-Server, NodeJs,
        Web Design, Software</p>
    <p style="color: #3e4b51;">
        <b>html2canvas</b> script allows you to take "screenshots" of webpages or parts
        of it, directly on the users browser. The screenshot is based on the DOM and as
        such may not be 100% accurate to the real representation.
    </p>
    <div style="top:90%; left:75%; background-color:#f8f9fa; color:#000;  " class="mypop">
        <div>ทดสอบ มีข้อความภาษาไทย ไก่</div>
        <table>
            <tr>
                <td>ระบบสื่อสารปกติ</td>
                <td><i class="fa fa-wifi fa-lg" style="color:green;"></i></td>
                <td>สถานี</td>
                <td><i class="fa fa-circle fa-lg" style="color:green;"></i></td>
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
                <td>ระดับน้ำวิกฤติ</td>
            </tr>
        </table>            
    </div>
</div>
    <a id="btn-Convert-Html2Image" href="#">Download 1</a>
    <a id="btn_Download2" href="#">Download 2</a>
    <br/>
    <div id="previewImage" >
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function(){

        /*
        var element = $("#html-content-holder"); // global variable
        var getCanvas; // global variable

        html2canvas(element, {
            onrendered: function (canvas) {
                $("#previewImage").html(canvas);
                getCanvas = canvas;
            }
        });


        
        */



        html2canvas(document.querySelector("#html-content-holder")).then(canvas => {
            $("#previewImage").html(canvas);
            getCanvas = canvas;
        });
       
        


        $("#btn-Convert-Html2Image").on('click', function () {
            var imgageData = getCanvas.toDataURL("image/png");
            // Now browser starts downloading it instead of just showing it
            var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
            $("#btn-Convert-Html2Image").attr("download", "your_pic_name.png").attr("href", newData);
        });

        $("#btn_Download2").on('click', function () {
            var canvas = getCanvas;
            canvas.toBlob(function(blob) {
                saveAs(blob, "pretty image.png");
            });
        });

        


    });
</script>