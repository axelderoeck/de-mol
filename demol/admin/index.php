<?php

require_once("includes/phpdefault.php"); 

$stmt = $pdo->prepare('SELECT COUNT(*) FROM table_Users');
$stmt->execute();
$accounts = $stmt->fetchColumn(0);

$stmt = $pdo->prepare('SELECT COUNT(*) FROM table_Groups');
$stmt->execute();
$groups = $stmt->fetchColumn(0);

$stmt = $pdo->prepare('SELECT IFNULL(SUM(table_Scores.Score), 0) + IFNULL(table_Users.Score, 0) AS "TotalScore"
FROM table_Users
LEFT JOIN table_Scores
ON table_Users.Id = table_Scores.UserId
GROUP BY table_Users.Id
ORDER BY TotalScore DESC
LIMIT 1');
$stmt->execute();
$highest_score = $stmt->fetchColumn(0);

$stmt = $pdo->prepare('SELECT COUNT(*) FROM table_Users WHERE Voted = 1');
$stmt->execute();
$accounts_voted = $stmt->fetchColumn(0);

$stmt = $pdo->prepare('SELECT COUNT(*) FROM table_Users WHERE Screen = 1');
$stmt->execute();
$accounts_red = $stmt->fetchColumn(0);

$stmt = $pdo->prepare('SELECT COUNT(*) FROM table_Groups WHERE Private = 1');
$stmt->execute();
$groups_private = $stmt->fetchColumn(0);

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
              <div class="widget-heading">Groups</div>
            </div>
            <div class="widget-content-right">
              <div class="widget-numbers text-warning"><?=$groups?></div>
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
              <div class="widget-numbers text-danger"><?=$highest_score?></div>
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
                <div class="widget-numbers mt-0 fsize-3 text-info"><?=round(($accounts_voted / $accounts) * 100)?>%</div>
              </div>
              <div class="widget-content-right w-100">
                <div class="progress-bar-xs progress">
                  <div class="progress-bar bg-info" role="progressbar" aria-valuenow="<?=round(($accounts_voted / $accounts) * 100)?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=round(($accounts_voted / $accounts) * 100)?>%;"></div>
                </div>
              </div>
            </div>
            <div class="widget-content-left fsize-1">
              <div class="text-muted opacity-6">Users Voted: <?=$accounts_voted?></div>
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
                <div class="widget-numbers mt-0 fsize-3 text-danger"><?=round(($accounts_red / $accounts) * 100)?>%</div>
              </div>
              <div class="widget-content-right w-100">
                <div class="progress-bar-xs progress">
                  <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="<?=round(($accounts_red / $accounts) * 100)?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=round(($accounts_red / $accounts) * 100)?>%;"></div>
                </div>
              </div>
            </div>
            <div class="widget-content-left fsize-1">
              <div class="text-muted opacity-6">Red Screens: <?=$accounts_red?></div>
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
                <div class="widget-numbers mt-0 fsize-3 text-success"><?=round(($groups_private / $groups) * 100)?>%</div>
              </div>
              <div class="widget-content-right w-100">
                <div class="progress-bar-xs progress">
                  <div class="progress-bar bg-success" role="progressbar" aria-valuenow="<?=round(($groups_private / $groups) * 100)?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=round(($groups_private / $groups) * 100)?>%;"></div>
                </div>
              </div>
            </div>
            <div class="widget-content-left fsize-1">
              <div class="text-muted opacity-6">Private Groups: <?=$groups_private?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php include("includes/footer.php") ?>

