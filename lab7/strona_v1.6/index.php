<!DOCTYPE html>
<html lang="pl">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="pl" />
    <meta name="Author" content="Maciej Świder" />
    <title>Historia Eksploracji Kosmosu</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
    session_start();
    include('cfg.php');
    include('admin/admin.php');

    $Admin = new Admin();

    // Wymuszanie logowania na samym początku
    if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
        echo $Admin->FormularzLogowania();
        session_destroy();
        exit; // Przerywa dalsze ładowanie strony
    }
?>

<header>
        <nav id="navbar">
            <ul>
                <li><a href="index.php?idp=1">Strona główna</a></li>
                <li><a href="index.php?idp=2">Animacje</a></li>
                <li><a href="index.php?idp=3">Komercjalizacja</a></li>
                <li><a href="index.php?idp=4">Kontakt</a></li>
                <li><a href="index.php?idp=5">Misje zalogowe</a></li>
                <li><a href="index.php?idp=6">Pierwsze misje</a></li>
                <li><a href="index.php?idp=7">Rzesza</a></li>
                <li><a href="index.php?idp=8">Skrypty</a></li>
                <li><a href="index.php?idp=9">Stacje</a></li>
                <li><a href="index.php?idp=10">Filmy</a></li>
                <li><a href="index.php?idp=-1">Lista Podstron</a></li> <!-- Dodany element nawigacyjny -->
            </ul>
        </nav>
    </header>

<div class='content'>
<?php
    include('showpage.php');

    // Obsługa różnych działań na podstawie parametru `idp`.
    $id = htmlspecialchars($_GET['idp'] ?? '1');

    switch ($id) {
        case '-1': // Lista podstron
            $Admin->ListaPodstron();
            break;
        case '-2': // Edycja strony
            echo $Admin->EditPage();
            break;
        case '-3': // Usunięcie strony
            echo $Admin->DeletePage();
            break;
        case '-4': // Tworzenie nowej strony
            echo $Admin->CreatePage();
            break;
        default: // Wyświetlenie zawartości strony
            echo PokazStrone($id);
            break;
    }
?>
</div>

<footer>
    <?php
    // Informacje o autorze
    $nr_indeksu = '169370';
    $nrGrupy = '4';
    echo 'Autor: Maciej Świder ' . $nr_indeksu . ' grupa ' . $nrGrupy . '<br/><br/>';
    ?>
</footer>
</body>
</html>
