<?php include("includes/header.php") ?>
  
<div class="row">
                                    <div class="col-md-6">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Info</h5>
                                                <form class="">
                                                    <div class="position-relative form-group">
                                                      <label>Id</label>
                                                      <input placeholder="with a placeholder" type="text" readonly class="form-control">
                                                    </div>
                                                    <div class="position-relative form-group">
                                                      <label>Email</label>
                                                      <input name="email" placeholder="with a placeholder" type="email" class="form-control">
                                                    </div>
                                                    <div class="position-relative form-group">
                                                      <label>Username</label>
                                                      <input name="username" placeholder="with a placeholder" type="text" class="form-control">
                                                    </div>
                                                    <div class="position-relative form-group">
                                                      <label>Firstname</label>
                                                      <input name="firstname" placeholder="with a placeholder" type="text" class="form-control">
                                                    </div>
                                                    <div class="position-relative form-group">
                                                      <label>Friendcode</label>
                                                      <input name="email" placeholder="with a placeholder" type="text" class="form-control">
                                                    </div>
                                                    <button class="mt-1 btn btn-primary">Save</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body">
                                              <h5 class="card-title">Stats</h5>
                                              <p>Score: <span class="badge badge-info">20</span></p>
                                              <p>Voted: <span class="badge badge-success">True</span></p>
                                              <p>Seen Results: <span class="badge badge-success">True</span></p>
                                              <p>Screen: <span class="badge badge-danger">Red</span></p>
                                              <p>Friends: <span class="badge badge-info">6</span></p>
                                              <p>Groups: <span class="badge badge-info">1</span></span></p>
                                              <p>Awards: <span class="badge badge-info">4</span></span></p>
                                              <p>Admin: <span class="badge badge-danger">False</span></p>
                                            </div>
                                        </div>
                                        <div class="main-card mb-3 card">
                                            <div class="card-body">
                                              <h5 class="card-title">Actions</h5>
                                              <button class="mt-1 btn btn-danger">Delete User</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

<?php include("includes/footer.php") ?>

