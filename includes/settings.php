<?php
  $seizoen_start = "03/21/2021"; // maand/dag/jaar   echte: 03/21/2021
  $seizoen_eind = "05/23/2021"; // maand/dag/jaar    echte: 05/23/2021
  $aantal_kandidaten = 10; // hoeveel kandidaten in het spel
  $top_aantal = 10; // algemene ranglijst top ... tonen
  $admins = array(7, 10); // array van alle admin's hun ID

  // bericht tonen op de home page
  // bericht settings
  // 'true' is aan, 'false' is uit
  // 'info' = blauw, 'succes' = groen, 'warning' = rood
  $bericht = true;
  $melding = (object) [
    'soort' => 'warning',
    'tekst' => "Opgelet! Aangezien we niet zeker zijn dat deze 10 kandidaten mogen beginnen, vraag ik om na de 1ste aflevering even geduld te hebben zodat ik de 'juiste' kandidaten kan bijwerken. Bedankt voor het begrip en veel plezier!"
  ];

?>
