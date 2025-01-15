<!DOCTYPE html>
<html lang="pl">
<head>
    <link rel="stylesheet" href="css/style.css"> 
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="pl" />
    <meta name="Author" content="Maciej Świder" />
    <title>Historia Eksploracji Kosmosu</title>
               
</head>
<body>
<?php
session_start();
include('cfg.php');
include('admin/admin.php');
include('php/contact.php');
include('showpage.php');
include('php/categories.php');
include('php/products.php');


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

<nav id="navbar">                           
    <ul>
        <?php
        // Pobierz wszystkie podstrony z bazy danych
        $query = "SELECT * FROM page_list WHERE status = 1 ORDER BY id ASC";
        $result = $conn->query($query);
        
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<li><a href="index.php?idp=' . $row['id'] . '">' . htmlspecialchars($row['page_title']) . '</a></li>';
            }
                echo '<li><a href="index.php?idp=11">Sklep</a></li>';
                echo '<li><a href="index.php?idp=13">Koszyk</a></li>';
        }
        ?>
        <div class="admin-options">
            <?php
            if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
                echo '<ul>
                    <li><a href="index.php?idp=-1">Lista Podstron</a></li>
                    <li><a href="index.php?idp=-8">Kategorie</a></li>
                    <li><a href="index.php?idp=-13">Zarządzanie Produktami</a></li>
                    <li><a href="index.php?idp=-6">Wyloguj</a></li>
                </ul>';
            }else {
                echo '<ul>
                    <li><a href="index.php?idp=-12">Zaloguj</a></li>
                    <li><a href="index.php?idp=-7">Odzyskaj hasło</a></li>
                </ul>';
            }
            ?>
        </div>
    </ul>
</nav>

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
        $Categories = new Categories($conn);
        echo $Categories->PokazKategorie();
        break;
    case '-9':
        $Categories = new Categories($conn);
        echo $Categories->DodajKategorie();
        break;
    case '-10':
        $Categories = new Categories($conn);
        echo $Categories->EdytujKategorie();
        break;
    case '-11':
        $Categories = new Categories($conn);
        echo $Categories->UsunKategorie();
        break;
    case '-12':
        
        echo $Admin->FormularzLogowania();
        break;
        case 11: // Sklep
            $Produkty = new Produkty();
            echo $Produkty->pokazSklep();  // Changed from PokazSklep to pokazSklep
            break;
            case 12: // Panel zarządzania produktami
                if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
                $Produkty = new Produkty();
                echo $Produkty->zarzadzajProduktem();  // Changed from ZarzadzajProduktem
                } else {
                echo '<p class="error">Dostęp zabroniony. Zaloguj się jako administrator.</p>';
                }
                break;
        
        case '-13': // Admin management panel
            $Products = new Produkty();
            echo $Products->ZarzadzajProduktem();
            break;
        case 13: // Koszyk
            require_once('php/shop.php');
            pokazKoszyk();
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
