<?php include("includes/header.php") ?>

  <!-- Table -->
  <div class="row">
    <div class="col-md-12">
      <div class="main-card mb-3 card">
        <div class="card-header">Candidates</div>
        <div class="table-responsive">
          <table class="align-middle mb-0 table table-borderless table-striped table-hover">
            <thead>
              <tr>
                <th class="text-center">Id</th>
                <th class="text-center">Name</th>
                <th class="text-center">Age</th>
                <th class="text-center">Job</th>
                <th class="text-center">Status</th>
                <th class="text-center">Winner</th>
                <th class="text-center">Loser</th>
                <th class="text-center">Mol</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="text-center text-muted">1</td>
                <td>
                  <div class="widget-content p-0">
                    <div class="widget-content-wrapper">
                      <div class="widget-content-left mr-3">
                        <div class="widget-content-left">
                          <img width="40" class="rounded-circle" src="assets/images/avatars/4.jpg" alt="">
                        </div>
                      </div>
                      <div class="widget-content-left flex2">
                        <div class="widget-heading">Bram</div>
                        <div class="widget-subheading opacity-7"></div>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="text-center">35</td>
                <td class="text-center">Dopper</td>
                <td class="text-center">
                  <div class="badge badge-success">In</div>
                </td>
                <td class="text-center">
                  <div class="badge badge-danger">No</div>
                </td>
                <td class="text-center">
                  <div class="badge badge-danger">No</div>
                </td>
                <td class="text-center">
                  <div class="badge badge-danger">No</div>
                </td>
                <td class="text-center">
                  <button onclick="location.href = 'candidate.php?id=1'" type="button" id="PopoverCustomT-1" class="btn btn-primary btn-sm">View</button>
                </td>
              </tr>                               
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

<?php include("includes/footer.php") ?>

