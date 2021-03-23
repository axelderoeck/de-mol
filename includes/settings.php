<?php
  // ALGEMENE SETTINGS
    $admins = array(7, 10); // array van alle admin's hun ID
    $stemmen_dag = 'Sun'; // dag waarop gestemd wordt
    $stemmen_uur = "2200"; // vanaf dit uur mag er gestemd worden

  // SPEL SETTINGS
    $seizoen_start = "03/21/2021"; // maand/dag/jaar   echte: 03/21/2021
    $seizoen_eind = "05/23/2021"; // maand/dag/jaar    echte: 05/23/2021
    $aantal_kandidaten = 10; // hoeveel kandidaten in het spel
    $top_aantal = 20; // algemene ranglijst top ... tonen

  // STYLE VERSIES - CACHING
    $styleversion = 2; // versie van css als ik dit wil forceren te updaten bij iedereen

  // ALERT SETTINGS
    $bericht = true;
    $melding = (object) [
      'soort' => 'succes', // 'info' = blauw, 'succes' = groen, 'warning' = rood
      'tekst' => "De kandidaten zijn geupdated, de mollenjacht is begonnen. Veel plezier!"
    ];

  // AWARDS SETTINGS
    // VAST
      $award_weetniets = 5;
      $award_tunnelvisie = 8;
      $award_allin = 9;
      $award_gilles = 10;
    // JAARLIJKS - elk jaar aanpassen, nieuwe edities maken
      $award_winnaar = 1;
      $award_topper = 2;
      $award_deelnemer = 4;

?>
