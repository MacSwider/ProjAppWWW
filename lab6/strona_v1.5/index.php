<!DOCTYPE html>
<html lang="pl">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />  
    <meta http-equiv="Content-Language" content="pl" /> 
    <meta name="Author" content="Tomasz Szewa" />
    <title>Moje hobby to gry planszowe</title>
    <?php
    if (isset($_GET['idp']) && $_GET['idp'] == 'poligon') {
    echo '<link rel="stylesheet" href="css/style.css">';   
    } else {
    echo '<<link rel="stylesheet" href="css/style.css">';     
    }
    ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" type="text/javascript"></script>
    <script src="js/kolorujtlo.js" type="text/javascript"></script>
    <script src="js/timedate.js" type="text/javascript"></script>
</head>
<body onload="startclock()">
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

<div class='content'>
    <?php
    if(file_exists($strona)){
        include($strona);
    } else {
        echo "<p>Podstrona nie istnieje.</p>";
    }
    ?>
</div>

<footer>

        <?php
        $nr_indeksu = '169370';
        $nrGrupy = '4';

        echo 'Autor: Maciej Świder '.$nr_indeksu.' grupa '.$nrGrupy.'<br/><br/>';
        ?>

</footer>
</body>
</html>