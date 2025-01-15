<!DOCTYPE html>
<html lang="pl">
<head>
    <!-- Dołączenie arkusza stylów -->
    <link rel="stylesheet" href="css/style.css"> 
    <!-- Ustawienia kodowania i języka -->
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="pl" />
    <meta name="Author" content="Maciej Świder" />
    <title>Historia Eksploracji Kosmosu</title>
               
</head>
<body>
<?php
// Rozpoczęcie sesji i dołączenie wymaganych plików
session_start();
include('cfg.php');
include('admin/admin.php');
include('php/contact.php');
include('showpage.php');
include('php/categories.php');
include('php/products.php');


$Admin = new Admin();

// Obsługa logowania użytkownika
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

<!-- Pasek nawigacyjny -->
<nav id="navbar">                           
    <ul>
        <?php
        // Pobierz wszystkie podstrony z bazy danych
        $query = "SELECT * FROM page_list WHERE status = 1 ORDER BY id ASC";
        $result = $conn->query($query);
        
        // Wyświetlanie linków do podstron
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<li><a href="index.php?idp=' . $row['id'] . '">' . htmlspecialchars($row['page_title']) . '</a></li>';
            }
                echo '<li><a href="index.php?idp=11">Sklep</a></li>';
                echo '<li><a href="index.php?idp=13">Koszyk</a></li>';
        }
        ?>
        <!-- Panel opcji administratora -->
        <div class="admin-options">
            <?php
            // Wyświetlanie różnych opcji w zależności od stanu zalogowania
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

<!-- Główna zawartość strony -->
<div class='content'>
<?php
// Pobranie ID strony z URL
$id = htmlspecialchars($_GET['idp'] ?? '1');

// Router - wybór odpowiedniej akcji na podstawie ID strony
switch ($id) {
    case '-1': // Lista podstron (tylko dla zalogowanych)
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
            $Admin->ListaPodstron();
        } else {
            echo "<div class='error'>Musisz się zalogować, aby uzyskać dostęp do tej strony.</div>";
        }
        break;
    case '-2': // Edycja strony
        echo $Admin->EditPage();
        break;
    case '-3': // Usuwanie strony
        echo $Admin->DeletePage();
        break;
    case '-4': // Tworzenie nowej strony
        echo $Admin->CreatePage();
        break;
    case '-5': // Formularz kontaktowy
        $contact = new Contact();
        echo "<h1> Kontakt </h1>";
        echo $contact->WyslijMailKontakt("otherstory118@gmail.com");
        break;
    case '-6': // Wylogowanie
        echo $Admin->Wyloguj();
        break;
    case '-7': // Odzyskiwanie hasła
        $Contact = new Contact();
        echo "<h2> Odzyskanie hasła </h2>";
        echo $Contact->PrzypomnijHaslo("otherstory118@gmail.com"); 	
        break;        
    case '-8': // Zarządzanie kategoriami
        $Categories = new Categories($conn);
        echo $Categories->PokazKategorie();
        break;
    case '-9': // Dodawanie kategorii
        $Categories = new Categories($conn);
        echo $Categories->DodajKategorie();
        break;
    case '-10': // Edycja kategorii
        $Categories = new Categories($conn);
        echo $Categories->EdytujKategorie();
        break;
    case '-11': // Usuwanie kategorii
        $Categories = new Categories($conn);
        echo $Categories->UsunKategorie();
        break;
    case '-12': // Formularz logowania
        echo $Admin->FormularzLogowania();
        break;
    case 11: // Sklep
        $Produkty = new Produkty();
        echo $Produkty->pokazSklep();
        break;
    case 12: // Panel zarządzania produktami (tylko dla zalogowanych)
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
            $Produkty = new Produkty();
            echo $Produkty->zarzadzajProduktem();
        } else {
            echo '<p class="error">Dostęp zabroniony. Zaloguj się jako administrator.</p>';
        }
        break;
    case '-13': // Panel administracyjny produktów
        $Products = new Produkty();
        echo $Products->ZarzadzajProduktem();
        break;
    case 13: // Koszyk
        require_once('php/shop.php');
        pokazKoszyk();
        break;
    default: // Wyświetlenie standardowej strony
        echo PokazStrone($id);
        break;
}
?>
</div>

<!-- Stopka strony -->
<footer>
    <?php
    $nr_indeksu = '169370';
    $nrGrupy = '4';
    echo 'Autor: Maciej Świder ' . $nr_indeksu . ' grupa ' . $nrGrupy . '<br/><br/>';
    ?>
</footer>
</body>
</html>
