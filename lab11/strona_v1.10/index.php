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
include('php/categories.php');

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
?>

<header>
    <nav id="navbar">                           
        <ul>
            <li><a href="index.php?idp=1">Strona główna</a></li>
            <li><a href="index.php?idp=2">Rzesza</a></li>
            <li><a href="index.php?idp=3">Pierwsze misje</a></li>           
            <li><a href="index.php?idp=4">Misje załogowe</a></li>
            <li><a href="index.php?idp=5">Stacje</a></li>
            <li><a href="index.php?idp=6">Komercjalizacja</a></li>
            <li><a href="index.php?idp=7">Kontakt</a></li>
            <li><a href="index.php?idp=8">Animacje</a></li>
            <li><a href="index.php?idp=9">Filmy</a></li>
            <li><a href="index.php?idp=10">Skrypty</a></li>
            <div class="admin-options">
                <?php
                if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
                    echo '<ul><li><a href="index.php?idp=-1">Lista Podstron</a></li></ul>';
                    echo '<ul><li><a href="index.php?idp=-8">Kategorie</a></li></ul>';
                    echo '<ul><li><a href="index.php?idp=-6">Wyloguj</a></li></ul>';
                } else {
                    echo '<ul><li><a href="index.php?idp=-12">Zaloguj</a></li></ul>';
                    echo '<ul><li><a href="index.php?idp=-7">Odzyskaj hasło</a></li></ul>';
                }
                ?>
            </div>
        </ul>
    </nav>
</header>

<div class='content'>
<?php
$id = htmlspecialchars($_GET['idp'] ?? '1');

switch ($id) {
    case '-1': 
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
            $Admin->ListaPodstron();
        } else {
            echo "<div class='error'>Musisz się zalogować, aby uzyskać dostęp do tej strony.</div>";
        }
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
        $contact = new Contact();
        echo "<h1> Kontakt </h1>";
        echo $contact->WyslijMailKontakt("otherstory118@gmail.com");
        break;
    case '-6':
        echo $Admin->Wyloguj();
        break;
    case '-7':
        $Contact = new Contact();
        echo "<h2> Odzyskanie hasła </h2>";
        echo $Contact->PrzypomnijHaslo("otherstory118@gmail.com"); 	
        break;        
    case '-8':
        $Categories = new Categories();
        echo $Categories->PokazKategorie();
        break;
    case '-9':
        $Categories = new Categories();
        echo $Categories->DodajKategorie();
        break;
    case '-10':
        $Categories = new Categories();
        echo $Categories->EdytujKategorie();
        break;
    case '-11':
        $Categories = new Categories();
        echo $Categories->UsunKategorie();
        break;
    case '-12':
        echo $Admin->FormularzLogowania();
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
