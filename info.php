<?php

require_once("includes/phpdefault.php");

$stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Id = ?');
$stmt->execute([ $_SESSION["Id"] ]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);

$votetime = str_split(VOTE_HOUR, 2);

?>

<?php include "includes/header.php"; ?>

    <h1>Uitleg</h1>

    <h2>Het spel</h2>
    <p>Je start met <span>10</span> punten en na elke aflevering heb je een week tijd om jouw punten in te zetten op wie jij denkt de <span>mol</span> te zijn (je kan ook de inzet spreiden).</p>
    <p>Als jouw verdachten een <span>groen</span> scherm kregen dan krijg jij die inzet dubbel terug <span>(10x2)</span>, als je punten hebt ingezet op de afvaller die week dan ben je die punten kwijt.</p>

    <h2>Wanneer kan ik stemmen?</h2>
    <p>Voor elke <span>aflevering</span> begint kan je stemmen tot en met de dag <span>ervoor</span>.</p>
    <p class="example">Op de dag van de aflevering (zondag) kan je beginnen met stemmen om <?php echo $votetime[0] . ":" . $votetime[1]; ?>u.</p>

    <h2>Hoe stemmen?</h2>
    <p>Als je op de <span>homepage</span> op de knop <span>'stemmen'</span> drukt ga je naar de stem pagina. <br> Daarna kan je <span>swipen</span> tussen de mogelijke kandidaten en je beschikbare punten inzetten a.d.h.v. de <span>slider</span>. <br> <span>Klaar?</span> druk op de <span>'inzenden'</span> knop. <br> Nu kan je <span>niet</span> meer stemmen tot dat de <span>volgende</span> aflevering is geweest.</p>
    <p class="example"><b>Opgelet!</b> Je kan maar 1 keer per week inzenden.</p>

    <h2>Hoe speel ik met anderen?</h2>
    <p>Als je naar de pagina <span>'vrienden'</span> gaat, kan je daar vrienden toevoegen aan de hand van hun <span>friendcode</span>.</p>
    <p class="example">Jouw persoonlijke friendcode om aan jouw vrienden te geven: <span><?=$account["Friendcode"]?></span>.</p>
    <p>Je kan ook <span>groepen</span> aanmaken, <span>privé</span> of <span>publiek</span>. Alle gebruikers in deze groep zullen elkaars score kunnen vergelijken met elkaar.</p>

    <h2>Molboek</h2>
    <p>Op deze pagina kan je al jouw <span>ingezette</span> punten bekijken. Gerangschikt van <span>hoog</span> naar <span>laag</span>.</p>

    <h2>Scores</h2>
    <p>Op deze pagina kan je elkaars huidige <span>score</span> zien in de vorm van een toplijst gesorteerd op: <span>iedereen</span>, <span>vrienden</span> en <span>groepen</span>.</p>

<?php include "includes/footer.php"; ?>

