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
                                                      <label>Name</label>
                                                      <input name="name" placeholder="with a placeholder" type="text" class="form-control">
                                                    </div>
                                                    <div class="position-relative form-group">
                                                      <label>Age</label>
                                                      <input name="age" placeholder="with a placeholder" type="text" class="form-control">
                                                    </div>
                                                    <div class="position-relative form-group">
                                                      <label>Job</label>
                                                      <input name="job" placeholder="with a placeholder" type="text" class="form-control">
                                                    </div>
                                                    <div class="position-relative form-group">
                                                      <label>Status</label>
                                                      <input name="status" placeholder="with a placeholder" type="text" class="form-control">
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
                                              <p>Mol: <span class="badge badge-danger">No</span></p>
                                              <p>Winner: <span class="badge badge-danger">No</span></p>
                                              <p>Loser: <span class="badge badge-danger">No</span></p>
                                            </div>
                                        </div>
                                        <div class="main-card mb-3 card">
                                            <div class="card-body">
                                              <h5 class="card-title">Actions</h5>
                                              <button class="mt-1 btn btn-danger">Delete Candidate</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

<?php include("includes/footer.php") ?>

