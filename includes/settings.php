<?php
  $seizoen_start = "03/21/2021"; // maand/dag/jaar   echte: 03/21/2021
  $seizoen_eind = "05/23/2021"; // maand/dag/jaar    echte: 05/23/2021
  $aantal_kandidaten = 10; // hoeveel kandidaten in het spel
  $top_aantal = 20; // algemene ranglijst top ... tonen
  $admins = array(7); // array van alle admin's hun ID
  $stemmen_dag = 'Sun'; // dag waarop gestemd wordt
  $stemmen_uur = "2200"; // vanaf dit uur mag er gestemd worden

  // bericht tonen op de home page
  // bericht settings
  // 'true' is aan, 'false' is uit
  // 'info' = blauw, 'succes' = groen, 'warning' = rood
  $bericht = true;
  $melding = (object) [
    'soort' => 'succes',
    'tekst' => "De kandidaten zijn geupdate, de mollenjacht is begonnen. Veel plezier!"
  ];

?>
