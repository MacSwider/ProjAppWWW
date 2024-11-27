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

   
    if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
        echo $Admin->FormularzLogowania();
        session_destroy();
        exit; 
    }
?>

<header>
        <nav id="navbar">
            <ul>
                <li><a href="index.php?idp=1">Strona główna</a></li>
                <li><a href="index.php?idp=2">Rzesza</a></li>
                <li><a href="index.php?idp=3">Pierwsze misje</a></li>
                <li><a href="index.php?idp=4">Misje zalogowe</a></li>
                <li><a href="index.php?idp=5">Stacje</a></li>
                <li><a href="index.php?idp=6">Komercjalizacja</a></li>
                <li><a href="index.php?idp=7">Kontakt</a></li>
                <li><a href="index.php?idp=8">Animacje</a></li>
                <li><a href="index.php?idp=9">Filmy</a></li>
                <li><a href="index.php?idp=10">Skrypty</a></li>
                <li><a href="index.php?idp=-1">Lista Podstron</a></li> 
            </ul>
        </nav>
    </header>

<div class='content'>
<?php
    include('showpage.php');

   
    $id = htmlspecialchars($_GET['idp'] ?? '1');

    switch ($id) {
        case '-1': 
            $Admin->ListaPodstron();
            break;
        case '-2': 
            echo $Admin->EditPage();
            break;
        case '-3': 
            echo $Admin->DeletePage();
            break;
        case '-4': 
            echo $Admin->CreatePage();
            break;
        default: 
            echo PokazStrone($id);
            break;
    }
?>
</div>

<footer>
    <?php
    
    $nr_indeksu = '169370';
    $nrGrupy = '4';
    echo 'Autor: Maciej Świder ' . $nr_indeksu . ' grupa ' . $nrGrupy . '<br/><br/>';
    ?>
</footer>
</body>
</html>
