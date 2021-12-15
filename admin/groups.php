<?php include("includes/header.php") ?>

 <!-- Number blocks -->
 <div class="row">  
    <div class="col-md-6 col-xl-4">
      <div class="card mb-3 widget-content">
        <div class="widget-content-outer">
          <div class="widget-content-wrapper">
            <div class="widget-content-left">
              <div class="widget-heading">Groups</div>
            </div>
            <div class="widget-content-right">
              <div class="widget-numbers text-success">20</div>
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
              <div class="widget-heading">Public groups</div>
            </div>
            <div class="widget-content-right">
              <div class="widget-numbers text-warning">2</div>
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
        <div class="card-header">Groups</div>
        <div class="table-responsive">
          <table class="align-middle mb-0 table table-borderless table-striped table-hover">
            <thead>
              <tr>
                <th class="text-center">Id</th>
                <th class="text-center">Admin</th>
                <th class="text-center">Name</th>
                <th class="text-center">Public</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="text-center text-muted">5</td>
                <td class="text-center">Someone</td>
                <td>
                  <div class="widget-content p-0">
                    <div class="widget-content-wrapper">
                      <div class="widget-content-left mr-3">
                        <div class="widget-content-left">
                          <img width="40" class="rounded-circle" src="assets/images/avatars/4.jpg" alt="">
                        </div>
                      </div>
                      <div class="widget-content-left flex2">
                        <div class="widget-heading">Gang</div>
                        <div class="widget-subheading opacity-7"></div>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="text-center">
                  <div class="badge badge-success">Yes</div>
                </td>
                <td class="text-center">
                  <button onclick="location.href = 'group.php?id=1'" type="button" id="PopoverCustomT-1" class="btn btn-primary btn-sm">View</button>
                </td>
              </tr>                               
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

<?php include("includes/footer.php") ?>

