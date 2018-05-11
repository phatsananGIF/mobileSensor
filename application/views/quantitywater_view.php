<div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">ปริมาณน้ำ <?= $ppn ?></li>
      </ol>


      <!-- data1 -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-industry"></i> <?= $title1 ?></div>

          <div class="list-group list-group-flush small">
              <div class="table-responsive">
                <table class="table">
                  <tbody>
                    <?= $datatable1 ?>
                  </tbody>
                </table>
              </div> 
          </div>
      </div>

      <!-- data2 -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-industry"></i> <?= $title2 ?></div>

          <div class="list-group list-group-flush small">
              <div class="table-responsive">
                <table class="table">
                  <tbody>
                    <?= $datatable2 ?>
                  </tbody>
                </table>
              </div> 
          </div>
      </div>

      <!-- data3 -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-industry"></i> <?= $title3 ?></div>

          <div class="list-group list-group-flush small">
              <div class="table-responsive">
                <table class="table">
                  <tbody>
                    <?= $datatable3 ?>
                  </tbody>
                </table>
              </div> 
          </div>
      </div>



</div>
<!-- /.container-fluid-->