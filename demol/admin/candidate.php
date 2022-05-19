<?php

require_once("includes/phpdefault.php");

$id = $_GET["id"];

// Get all available awards
$stmt = $pdo->prepare('SELECT * FROM table_Candidates WHERE Id = ?');
$stmt->execute([$id]);
$candidate = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_POST["updateCandidate"])){
  $stmt = $pdo->prepare('UPDATE table_Candidates SET Name = ?, Age = ?, Job = ? WHERE Id = ?');
  $stmt->execute([ $_POST["name"], $_POST["age"], $_POST["job"], $id ]);
}

if (isset($_POST["setMol"])){
  $stmt = $pdo->prepare('UPDATE table_Candidates SET Mol = 1 WHERE Id = ?');
  $stmt->execute([ $id ]);
}

if (isset($_POST["setWinner"])){
  $stmt = $pdo->prepare('UPDATE table_Candidates SET Winner = 1 WHERE Id = ?');
  $stmt->execute([ $id ]);
}

if (isset($_POST["setLoser"])){
  $stmt = $pdo->prepare('UPDATE table_Candidates SET Loser = 1 WHERE Id = ?');
  $stmt->execute([ $id ]);
}

if (isset($_POST["setIn"])){
  $stmt = $pdo->prepare('UPDATE table_Candidates SET Status = 1 WHERE Id = ?');
  $stmt->execute([ $id ]);
}

if (isset($_POST["setOut"])){
  $stmt = $pdo->prepare('UPDATE table_Candidates SET Status = 0 WHERE Id = ?');
  $stmt->execute([ $id ]);
}

?>

<?php include("includes/header.php") ?>
  
<div class="row">
                                    <div class="col-md-6">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Info</h5>
                                                <form name="formUpdateCandidate" method="post" class="">
                                                    <div class="position-relative form-group">
                                                      <label>Id</label>
                                                      <input placeholder="<?=$candidate['Id']?>" type="text" readonly class="form-control">
                                                    </div>
                                                    <div class="position-relative form-group">
                                                      <label>Name</label>
                                                      <input name="name" placeholder="<?=$candidate['Name']?>" type="text" class="form-control" value="<?=$candidate['Name']?>">
                                                    </div>
                                                    <div class="position-relative form-group">
                                                      <label>Age</label>
                                                      <input name="age" placeholder="<?=$candidate['Age']?>" type="text" class="form-control" value="<?=$candidate['Age']?>">
                                                    </div>
                                                    <div class="position-relative form-group">
                                                      <label>Job</label>
                                                      <input name="job" placeholder="<?=$candidate['Job']?>" type="text" class="form-control" value="<?=$candidate['Job']?>">
                                                    </div>
                                                    <input type="submit" name="updateCandidate" id="updateCandidate" class="mt-1 btn btn-primary" value="Save">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body">
                                              <h5 class="card-title">Stats</h5>
                                              <?php if($candidate['Mol'] == 0): ?>
                                              <p>Mol: <span class="badge badge-danger">No</span></p>
                                              <?php else: ?>
                                              <p>Mol: <span class="badge badge-success">Yes</span></p>
                                              <?php endif; ?>
                                              <?php if($candidate['Winner'] == 0): ?>
                                              <p>Winner: <span class="badge badge-danger">No</span></p>
                                              <?php else: ?>
                                              <p>Winner: <span class="badge badge-success">Yes</span></p>
                                              <?php endif; ?>
                                              <?php if($candidate['Loser'] == 0): ?>
                                              <p>Loser: <span class="badge badge-danger">No</span></p>
                                              <?php else: ?>
                                              <p>Loser: <span class="badge badge-success">Yes</span></p>
                                              <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="main-card mb-3 card">
                                            <div class="card-body">
                                              <h5 class="card-title">Actions</h5>
                                              <form method="post">
                                                <input type="submit" name="setIn" id="setIn" class="mt-1 btn btn-success" value="Set in">
                                              </form>
                                              <form method="post">
                                                <input type="submit" name="setOut" id="setOut" class="mt-1 btn btn-danger" value="Set out">
                                              </form>
                                              <form method="post">
                                                <input type="submit" name="setMol" id="setMol" class="mt-1 btn btn-info" value="Set Mol">
                                              </form>
                                              <form method="post">
                                                <input type="submit" name="setWinner" id="setWinner" class="mt-1 btn btn-info" value="Set Winner">
                                              </form>
                                              <form method="post">
                                                <input type="submit" name="setLoser" id="setLoser" class="mt-1 btn btn-info" value="Set Loser">
                                              </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

<?php include("includes/footer.php") ?>

