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
              <div class="widget-heading">Voted</div>
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
              <div class="widget-heading">Seen Results</div>
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
        <div class="card-header">Users</div>
        <div class="table-responsive">
          <table class="align-middle mb-0 table table-borderless table-striped table-hover">
            <thead>
              <tr>
                <th class="text-center">Id</th>
                <th class="text-center">Info</th>
                <th class="text-center">Friendcode</th>
                <th class="text-center">Score</th>
                <th class="text-center">Voted</th>
                <th class="text-center">Screen</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="text-center text-muted">345</td>
                <td>
                  <div class="widget-content p-0">
                    <div class="widget-content-wrapper">
                      <div class="widget-content-left mr-3">
                        <div class="widget-content-left">
                          <img width="40" class="rounded-circle" src="assets/images/avatars/4.jpg" alt="">
                        </div>
                      </div>
                      <div class="widget-content-left flex2">
                        <div class="widget-heading">Djoloman #123456</div>
                        <div class="widget-subheading opacity-7">(john) johndoe@gmail.com</div>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="text-center">#123456</td>
                <td class="text-center">2251</td>
                <td class="text-center">
                  <div class="badge badge-success">Yes</div>
                </td>
                <td class="text-center">
                  <div class="badge badge-danger">Red</div>
                </td>
                <td class="text-center">
                  <button onclick="location.href = 'user.php?id=1'" type="button" id="PopoverCustomT-1" class="btn btn-primary btn-sm">View</button>
                </td>
              </tr>                               
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

<?php include("includes/footer.php") ?>

