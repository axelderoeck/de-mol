<?php include("includes/header.php") ?>

  <!-- Number blocks -->
  <div class="row">  
    <div class="col-md-6 col-xl-4">
      <div class="card mb-3 widget-content">
        <div class="widget-content-outer">
          <div class="widget-content-wrapper">
            <div class="widget-content-left">
              <div class="widget-heading">Accounts</div>
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
              <div class="widget-heading">Gestemd</div>
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
              <div class="widget-heading">Scherm Gezien</div>
            </div>
            <div class="widget-content-right">
              <div class="widget-numbers text-danger">456</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Table -->
  <div class="row">
    <div class="col-md-12">
      <div class="main-card mb-3 card">
        <div class="card-header">Active Users</div>
        <div class="table-responsive">
          <table class="align-middle mb-0 table table-borderless table-striped table-hover">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th>Name</th>
                <th class="text-center">City</th>
                <th class="text-center">Status</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="text-center text-muted">#345</td>
                <td>
                  <div class="widget-content p-0">
                    <div class="widget-content-wrapper">
                      <div class="widget-content-left mr-3">
                        <div class="widget-content-left">
                          <img width="40" class="rounded-circle" src="assets/images/avatars/4.jpg" alt="">
                        </div>
                      </div>
                      <div class="widget-content-left flex2">
                        <div class="widget-heading">John Doe</div>
                        <div class="widget-subheading opacity-7">Web Developer</div>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="text-center">Madrid</td>
                <td class="text-center">
                  <div class="badge badge-warning">Pending</div>
                </td>
                <td class="text-center">
                  <button type="button" id="PopoverCustomT-1" class="btn btn-primary btn-sm">Details</button>
                </td>
              </tr>                               
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Percent blocks -->
  <div class="row">
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
              <div class="text-muted opacity-6">Income Target</div>
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
              <div class="text-muted opacity-6">Expenses Target</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="card-shadow-warning mb-3 widget-chart widget-chart2 text-left card">
        <div class="widget-content">
          <div class="widget-content-outer">
            <div class="widget-content-wrapper">
              <div class="widget-content-left pr-2 fsize-1">
                <div class="widget-numbers mt-0 fsize-3 text-warning">32%</div>
              </div>
              <div class="widget-content-right w-100">
                <div class="progress-bar-xs progress">
                  <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="32" aria-valuemin="0" aria-valuemax="100" style="width: 32%;"></div>
                </div>
              </div>
            </div>
            <div class="widget-content-left fsize-1">
              <div class="text-muted opacity-6">Spendings Target</div>
            </div>
          </div>
        </div>
      </div>
    </div>
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
              <div class="text-muted opacity-6">Totals Target</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php include("includes/footer.php") ?>

