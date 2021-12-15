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
                                                      <label>AdminId</label>
                                                      <input name="adminId" placeholder="with a placeholder" type="text" class="form-control">
                                                    </div>
                                                    <div class="position-relative form-group">
                                                      <label>Name</label>
                                                      <input name="name" placeholder="with a placeholder" type="text" class="form-control">
                                                    </div>
                                                    <div class="position-relative form-group">
                                                      <label>Description</label>
                                                      <input name="description" placeholder="with a placeholder" type="text" class="form-control">
                                                    </div>
                                                    <div class="position-relative form-group">
                                                      <label>Private</label>
                                                      <input name="private" placeholder="with a placeholder" type="text" class="form-control">
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
                                              <p>Members: <span class="badge badge-info">20</span></p>
                                            </div>
                                        </div>
                                        <div class="main-card mb-3 card">
                                            <div class="card-body">
                                              <h5 class="card-title">Actions</h5>
                                              <button class="mt-1 btn btn-danger">Delete Group</button>
                                            </div>
                                        </div>
                                    </div>
                                  
                                </div>

<?php include("includes/footer.php") ?>

