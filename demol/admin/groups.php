<?php

require_once("includes/phpdefault.php"); 

$stmt = $pdo->prepare('SELECT * FROM table_Groups');
$stmt->execute();
$groups_all = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT COUNT(*) FROM table_Groups');
$stmt->execute();
$groups = $stmt->fetchColumn(0);

$stmt = $pdo->prepare('SELECT COUNT(*) FROM table_Groups WHERE Private = 0');
$stmt->execute();
$groups_public = $stmt->fetchColumn(0);

?>

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
              <div class="widget-numbers text-success"><?=$groups?></div>
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
              <div class="widget-numbers text-warning"><?=$groups_public?></div>
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
                <th class="text-center">Members</th>
                <th class="text-center"></th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($groups_all as $group): ?>
              <tr>
                <td class="text-center text-muted"><?=$group["Id"]?></td>
                <td class="text-center"><?=$group["AdminId"]?></td>
                <td>
                  <div class="widget-content p-0">
                    <div class="widget-content-wrapper">
                      <div class="widget-content-left mr-3">
                        <div class="widget-content-left">
                          <img width="40" class="rounded-circle" src="assets/images/avatars/4.jpg" alt="">
                        </div>
                      </div>
                      <div class="widget-content-left flex2">
                        <div class="widget-heading"><?=$group["Name"]?></div>
                        <div class="widget-subheading opacity-7"></div>
                      </div>
                    </div>
                  </div>
                </td>
                <td></td>
                <td class="text-center">
                  <?php if($group["Private"] == 1): ?>
                  <div class="badge badge-danger">Private</div>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <button onclick="location.href = 'group.php?id=<?=$group['Id']?>'" type="button" id="PopoverCustomT-1" class="btn btn-primary btn-sm">View</button>
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

