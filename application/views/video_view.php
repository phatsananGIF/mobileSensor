<div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">วีดีโอ</li>
      </ol>
      
      
      <!-- Area Cards -->
      <div class="card mb-3" style="margin-left: 16px;margin-right: 16px;">

        <div class="card-header" id="H-card" >
          <div class="row">

          <div class="col-xs-6 col-md-4">
              <select class="form-control" id="selected_sitecode" >
                <?php foreach($rsquery as $side){ ?>
                  <option value="<?php echo  $side['sitecode']."_".$side['location'];?>"><?php echo  $side['sitename']." ".$side['location'];?></option>
                <?php } ?>
              </select>
          </div>

          <div class="col-xs-6 col-md-4">
              <button type="button" onclick="viewImg()" class="btn btn-primary " >View</button>
          </div>

          </div><!-- row -->   
        </div>

        <div class="card-body" >
          <div id='video' style="display: none;" ></div>
          <img id="image" src=""  style="width:100%; position:relative;"/>

        </div>

      </div><!-- End Area Cards -->
          


</div>
<!-- /.container-fluid-->


<script type="text/javascript">



var img = document.getElementById('image');
var images = {};
var index = 1;
var start = 1
var limit = 300
var ready = false;
var inDev  = document.getElementById('video');
var myloadId = "";
var myvideoId = "";
var count = 0;
var selected_sitecode = "";

function viewImg(){
  count = 0;
  clearInterval(myloadId);
  clearInterval(myvideoId);
      img.src = "<?=base_url()?>video/amc_loading.gif";
      selected_sitecode = document.getElementById("selected_sitecode").value;
      console.log(selected_sitecode);
      site = selected_sitecode;

      dataI={"selected_sitecode":selected_sitecode};
      $.ajax({
        type:"POST",
        url:'<?=base_url("videoview/getfile")?>',
        cache:false,
        dataType:"JSON",
        data:dataI,
        async:true,
        success:function(result){
            console.log(result);
            
            if(result!="no"){
              start = Number(result);
              myloadId = setInterval(loadId, 5000);

            }else{
              clearInterval(myloadId);
              clearInterval(myvideoId);
              img.src = "<?=base_url()?>video/no.jpg";
            }
            
        }//end success
      });//end $.ajax 
      
      
}//f.viewImg


function loadImages(start,end){
   console.log(start + ' , ' +  end);
  for (var i = start; i < end ; i++) {
   var name = "<?=base_url()?>video/"+selected_sitecode+"/" + i + ".jpg";
   var img1= new Image();
       img1.id = i
       img1.src = name;    
       inDev.appendChild(img1);
       images[i.toString()]=img1;
       //console.log(img1);    
   }

   count ++;
   console.log(count);
  if(count==1){
      myvideoId = setInterval(videoId, 1000/30);
  }

}//f.loadImages

function loadId(){
     loadImages(start,limit + start);
     start+=limit
}//f.loadId



function videoId(){
    if(typeof images[index.toString()] != 'undefined') {
          
          var x = images[index.toString()].complete;  
          var z = images[index.toString()].width;
          // console.log(images[index.toString()].width);
          //console.log(index + ' = ' + x);
          if(x && z > 0 ) {
              img.src=images[index.toString()].src;
              inDev.removeChild( images[index.toString()]);
              delete images[index.toString()]
              index+=1;
          }else{
              clearInterval(myloadId);
              img.src="video/no.jpg";
              console.log('File not found index ' + index );
              start=index;
              images = {};
              document.getElementById('video').innerHTML = "";
              clearInterval(myvideoId);
        }
    }
    if(index >= 99999) { 
      clearInterval(myloadId);
      clearInterval(myvideoId);
    }
      
}//f.videoId










/*
    var index = 0;
    var count = 0;
    var site = "";
    var img = document.getElementById('imageShow');
    var myVar = "";

    function viewImg(){
      clearInterval(myVar);
      img.src = "<?=base_url()?>video/amc_loading.gif";
      var selected_sitecode = document.getElementById("selected_sitecode").value;
      console.log(selected_sitecode);
      site = selected_sitecode;

      dataI={"selected_sitecode":selected_sitecode};
      $.ajax({
        type:"POST",
        url:'<?=base_url("videoview/getfile")?>',
        cache:false,
        dataType:"JSON",
        data:dataI,
        async:true,
        success:function(result){
            console.log(result);
            if(result!="no"){
              index = result;
              count = 0;
              myVar = setInterval(myTimer, 1000/30);
            }else{
              clearInterval(myVar);
              img.src = "<?=base_url()?>video/no.jpg";
            }
            
        }//end success
      });//end $.ajax 

      
    }//f.viewImg


    function myTimer() {
      index++;
      count++;

      var name = index + ".jpg"
      img.src = "<?=base_url()?>video/"+site+"/thumb"+name;
      console.log(count);
  
      if(index == 1000){
        index = 0;
        count = 0;
      }
    }//f.myTimer
    */

    



</script>

