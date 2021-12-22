<?php

require_once("includes/phpdefault.php");

$votetime = str_split(VOTE_HOUR, 2);

?>

<?php include "includes/header.php"; ?>

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
    <p class="example">Op de dag van de aflevering (zondag) kan je beginnen met stemmen om <?php echo $votetime[0] . ":" . $votetime[1]; ?>u.</p>
    <p>Op deze manier stem je dus <span>voor</span> dat de finale begint als <span>laatste</span> keer.</p>

    <h2>Hoe stemmen?</h2>
    <p>Als je op de <span>homepage</span> op de knop <span>'stemmen'</span> drukt ga je naar de stemmen pagina. <br> Daarna kan je <span>swipen</span> tussen de mogelijke kandidaten en je punten inzetten a.d.h.v. de <span>+</span> en <span>-</span> knop. <br> <span>Klaar?</span> druk op de <span>'inzenden'</span> knop. <br> Nu kan je <span>niet</span> meer stemmen tot dat de <span>volgende</span> aflevering is geweest.</p>
    <p class="example"><b>Opgelet!</b> Je kan maar 1 keer per week inzenden dus zet alle 10 punten in.</p>

    <h2>Hoe speel ik met anderen?</h2>
    <p>Als je naar de pagina <span>'Mollenjagers'</span> gaat, kan u daar personen volgen aan de hand van hun <span>gebruikersnaam</span> (Die zal je moeten vragen).
      Wanneer de <span>mol</span> bekend is, zal je jouw <span>score</span> kunnen vergelijken met iedereen die jij <span>volgt</span>. <br> Er zal ook een algemene <span>top <?php echo $top_aantal?></span> lijst te zien zijn. <br>
    </p>
    <p class="example"><b>Opgelet!</b> Als jij iemand <b>volgt</b> kan jij deze persoon zien maar andersom <b>niet</b>. <br>Deze persoon moet ook jou <b>volgen</b> als jullie elkaar willen zien. </p>

    <h2>Molboek</h2>
    <p>Op deze pagina kan je al jouw <span>ingezette</span> punten bekijken. Gerangschikt van <span>hoog</span> naar <span>laag</span>.</p>

    <h2>Ranglijst</h2>
    <p>Wanneer de <span>mol</span> bekend is zal deze pagina verschijnen en alle gebruikers hun punten <span>vergelijken</span>. Degene met de <span>meeste</span> punten op de juiste <span>mol</span> wint!</p>

<?php include "includes/footer.php"; ?>

