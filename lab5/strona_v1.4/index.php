<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kosmos - Strona Główna</title>
    <link rel="stylesheet" href="../strona/css/style.css">
</head>
<body>
    <header>
        <h1>Historia Eksploracji Kosmosu</h1>
    </header>

    <?php
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

    if ($_GET['idp'] == '') $strona = 'html/glowna.html'; 
    elseif ($_GET['idp'] == 'animacje') $strona = 'html/animacje.html';
    elseif ($_GET['idp'] == 'glowna') $strona = 'html/glowna.html';
    elseif ($_GET['idp'] == 'komercjalizacja') $strona = 'html/komercjalizacja.html';
    elseif ($_GET['idp'] == 'kontakt') $strona = 'html/kontakt.html';
    elseif ($_GET['idp'] == 'misje-zalogowe') $strona = 'html/misje-zalogowe.html';
    elseif ($_GET['idp'] == 'pierwsze-misje') $strona = 'html/pierwsze-misje.html';
    elseif ($_GET['idp'] == 'rzesza') $strona = 'html/rzesza.html';
    elseif ($_GET['idp'] == 'skrypty') $strona = 'html/skrypty.html';
    elseif ($_GET['idp'] == 'stacje') $strona = 'html/stacje.html';
    elseif ($_GET['idp'] =='filmy') $strona = 'html/filmy.html';
    else $strona = 'html/glowna.html';  
    ?>

    <header>
        <nav id="navbar">
            <ul>
                <li><a href="index.php?idp=glowna">Strona główna</a></li>
                <li><a href="index.php?idp=animacje">Animacje</a></li>
                <li><a href="index.php?idp=komercjalizacja">Komercjalizacja</a></li>
                <li><a href="index.php?idp=kontakt">Kontakt</a></li>
                <li><a href="index.php?idp=misje-zalogowe">Misje zalogowe</a></li>
                <li><a href="index.php?idp=pierwsze-misje">Pierwsze misje</a></li>
                <li><a href="index.php?idp=rzesza">Rzesza</a></li>
                <li><a href="index.php?idp=skrypty">Skrypty</a></li>
                <li><a href="index.php?idp=stacje">Stacje</a></li>
                <li><a href="index.php?idp=filmy">Filmy</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <!-- Wczytanie odpowiedniej strony na podstawie zmiennej $strona -->
        <?php include($strona); ?> 
    </div>

    <footer>
        &copy; 2024 Historia Kosmosu

        <?php
        $nr_indeksu = '169370';
        $nrGrupy = '4';
        echo 'Autor: Maciej Świder ' . $nr_indeksu . ' grupa ' . $nrGrupy . ' <br /><br />';
        ?>
    </footer>
</body>
</html>
