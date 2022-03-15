<?php

require_once("includes/phpdefault.php"); 

?>

<?php include("includes/header.php") ?>

  <!-- Number blocks -->
  <div class="row">  
    <div class="col-md-6 col-xl-4">
      <div class="card mb-3 widget-content">
        <div class="widget-content-outer">
          <div class="widget-content-wrapper">
            <div class="widget-content-left">
              <div class="widget-heading">Users</div>
            </div>
            <div class="widget-content-right">
              <div class="widget-numbers text-success">1896</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-4">
      <div class="card mb-3 widget-content">
        <div class="widget-content-outer">
          <div class="widget-content-wrapper">
            <div class="widget-content-left">
              <div class="widget-heading">Groups</div>
            </div>
            <div class="widget-content-right">
              <div class="widget-numbers text-warning">520</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-4">
      <div class="card mb-3 widget-content">
        <div class="widget-content-outer">
          <div class="widget-content-wrapper">
            <div class="widget-content-left">
              <div class="widget-heading">Highest Score</div>
            </div>
            <div class="widget-content-right">
              <div class="widget-numbers text-danger">456</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Percent blocks -->
  <div class="row">
    <div class="col-md-6 col-lg-3">
      <div class="card-shadow-info mb-3 widget-chart widget-chart2 text-left card">
        <div class="widget-content">
          <div class="widget-content-outer">
            <div class="widget-content-wrapper">
              <div class="widget-content-left pr-2 fsize-1">
                <div class="widget-numbers mt-0 fsize-3 text-info">89%</div>
              </div>
              <div class="widget-content-right w-100">
                <div class="progress-bar-xs progress">
                  <div class="progress-bar bg-info" role="progressbar" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100" style="width: 89%;"></div>
                </div>
              </div>
            </div>
            <div class="widget-content-left fsize-1">
              <div class="text-muted opacity-6">Users Voted</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="card-shadow-danger mb-3 widget-chart widget-chart2 text-left card">
        <div class="widget-content">
          <div class="widget-content-outer">
            <div class="widget-content-wrapper">
              <div class="widget-content-left pr-2 fsize-1">
                <div class="widget-numbers mt-0 fsize-3 text-danger">71%</div>
              </div>
              <div class="widget-content-right w-100">
                <div class="progress-bar-xs progress">
                  <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="71" aria-valuemin="0" aria-valuemax="100" style="width: 71%;"></div>
                </div>
              </div>
            </div>
            <div class="widget-content-left fsize-1">
              <div class="text-muted opacity-6">Red Screens</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="card-shadow-success mb-3 widget-chart widget-chart2 text-left card">
        <div class="widget-content">
          <div class="widget-content-outer">
            <div class="widget-content-wrapper">
              <div class="widget-content-left pr-2 fsize-1">
                <div class="widget-numbers mt-0 fsize-3 text-success">54%</div>
              </div>
              <div class="widget-content-right w-100">
                <div class="progress-bar-xs progress">
                  <div class="progress-bar bg-success" role="progressbar" aria-valuenow="54" aria-valuemin="0" aria-valuemax="100" style="width: 54%;"></div>
                </div>
              </div>
            </div>
            <div class="widget-content-left fsize-1">
              <div class="text-muted opacity-6">Highest Suspicion</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php include("includes/footer.php") ?>

