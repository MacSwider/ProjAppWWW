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
include('php/contact.php');
include('showpage.php');


$Admin = new Admin();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'], $_POST['login_pass'])) {
    if ($Admin->CheckLoginCred($_POST['login'], $_POST['login_pass'])) {
        $_SESSION['loggedin'] = true;
        header("Location: index.php");
        exit;
    } else {
        echo "<div class='error'>Nieprawidłowy login lub hasło.</div>";
    }
}


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
                <ul><a href="index.php?idp=-5">Kontakt</a></ul>
                <?php
                if(isset($_SESSION['loggedin'])) {
                echo '<ul><a href="index.php?idp=-6">Wyloguj</a></ul>';
                }else {
                echo '<ul><a href="index.php?idp=-7">Odzyskaj hasło</a></ul>';
                }
            ?>

            </ul>
        </nav>
    </header>

<div class='content'>
<?php
 // swtich opcji zarządzania stronami
 // -1 lista
 // -2 edytowanie
 // -3 usuwanie
 // -4 tworzenie
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

        case '-5':
            //Tworzy nowy obiekt klasy Contact i wyświetla formularz kontaktowy
            //po przesłaniu formularza, wyświetli komunikat o sukcesie lub błędzie
            $contact = new Contact();
            echo "<h1> Kontakt </h1>";
            echo $contact->WyslijMailKontakt("otherstory118@gmail.com");
            break;
        case '-6':
            if($Admin === null) {
                $Admin = new Admin();
            }
            echo $Admin->Wyloguj();
            break;
        case '-7':
            $Contact = new Contact();
            echo "<h2> Odzyskanie hasla </h2>";
            echo $Contact->PrzypomnijHaslo("otherstory118@gmail.com"); 	
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
