<?php

require_once("includes/phpdefault.php"); 

$stmt = $pdo->prepare('SELECT * FROM table_Users');
$stmt->execute();
$accounts_all = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT COUNT(*) FROM table_Users');
$stmt->execute();
$accounts = $stmt->fetchColumn(0);

$stmt = $pdo->prepare('SELECT COUNT(*) FROM table_Users WHERE Voted = 1');
$stmt->execute();
$accounts_voted = $stmt->fetchColumn(0);

$stmt = $pdo->prepare('SELECT COUNT(*) FROM table_Users WHERE SeenResults = 1');
$stmt->execute();
$accounts_seen = $stmt->fetchColumn(0);

$stmt = $pdo->prepare('SELECT COUNT(*) FROM table_Users WHERE Screen = 1');
$stmt->execute();
$accounts_red = $stmt->fetchColumn(0);

?>

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
              <div class="widget-numbers text-success"><?=$accounts?></div>
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
              <div class="widget-numbers text-warning"><?=$accounts_voted?></div>
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
              <div class="widget-numbers text-danger"><?=$accounts_seen?></div>
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
            <?php foreach($accounts_all as $account): ?>
              <tr>
                <td class="text-center text-muted"><?=$account["Id"]?></td>
                <td>
                  <div class="widget-content p-0">
                    <div class="widget-content-wrapper">
                      <div class="widget-content-left mr-3">
                        <div class="widget-content-left">
                          <img width="40" class="rounded-circle" src="assets/images/avatars/4.jpg" alt="">
                        </div>
                      </div>
                      <div class="widget-content-left flex2">
                        <div class="widget-heading"><?=$account["Username"]?> #<?=$account["Friendcode"]?></div>
                        <div class="widget-subheading opacity-7"><?=$account["Name"]?></div>
                        <div class="widget-subheading opacity-7"><?=$account["Email"]?></div>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="text-center">#<?=$account["Friendcode"]?></td>
                <td class="text-center"><?=$account["Score"]?></td>
                <td class="text-center">
                  <?php if($account["Voted"] == 1): ?>
                  <div class="badge badge-success">Yes</div>
                  <?php else: ?>
                  <div class="badge badge-danger">No</div>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <?php if($account["Screen"] == 1): ?>
                  <div class="badge badge-danger"><i class="fas fa-fingerprint"></i></div>
                  <?php else: ?>
                  <div class="badge badge-success"><i class="fas fa-fingerprint"></i></div>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <button onclick="location.href = 'user.php?id=<?=$account['Id']?>'" type="button" id="PopoverCustomT-1" class="btn btn-primary btn-sm">View</button>
                </td>
              </tr> 
            <?php endforeach; ?>                              
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

<?php include("includes/footer.php") ?>

