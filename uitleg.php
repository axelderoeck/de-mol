<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
</head>
<body>
  <?php include "includes/navigation.php"; ?>

  <div class="uitlegScreen" id="main">

    <h1>Uitleg</h1>
    <h2>Het doel</h2>
    <p>De bedoeling is om zo snel mogelijk de <span>mol</span> te ontmaskeren voor dat de anderen dit doen.</p>

    <h2>Het spel</h2>
    <p>Elke week kunnen er <span>10</span> punten ingezet worden op de persoon die jij verdenkt als <span>mol</span></p>
    <p class="example">Bijvoorbeeld: <br>
      6 -> Bart <br>
      4 -> Alina
    </p>

    <h2>Wanneer kan ik stemmen?</h2>
    <p>Voor de <span>eerste</span> aflevering begint wordt er <span>niet</span> gestemd.<br>
    <span>Daarna</span> ga je elke week stemmen voor de <span>komende</span> aflevering.</p>
    <p class="example">Je kan <b>niet</b> op de dag van de aflevering zelf stemmen. <br>
    Dus vergeet niet te stemmen op ten laatste zaterdag.</p>
    <p>Op deze manier stem je dus <span>voor</span> dat de finale begint als <span>laatste</span> keer.</p>

    <h2>Hoe stemmen?</h2>
    <p>Als je op de <span>homepage</span> op de knop <span>'stemmen'</span> drukt ga je naar de stemmen pagina. <br> Daarna kan je <span>swipen</span> tussen de mogelijke kandidaten en je punten inzetten a.d.h.v. de <span>+</span> en <span>-</span> knop. <br> <span>Klaar?</span> druk op de <span>'inzenden'</span> knop. <br> Nu kan je <span>niet</span> meer stemmen tot dat de <span>volgende</span> aflevering is geweest.</p>
    <p class="example"><b>Opgelet!</b> Je kan maar 1 keer per week inzenden dus zet alle 10 punten in.</p>

    <h2>Molboek</h2>
    <p>Op deze pagina kan je al jouw <span>ingezette</span> punten bekijken. Gerangschikt van <span>hoog</span> naar <span>laag</span>.</p>

    <h2>Ranglijst</h2>
    <p>Wanneer de <span>mol</span> bekend is zal deze pagina alle gebruikers hun punten <span>vergelijken</span>. Degene met de <span>meeste</span> punten op de juiste <span>mol</span> wint!</p>
  </div>

  <script type="text/javascript" src="js/scripts.js"></script>
  <?php mysqli_close($dbconn); ?>
</body>
</html>
