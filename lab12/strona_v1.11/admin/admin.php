<?php
include 'cfg.php';

class Admin {

    /*
     * FormularzLogowania
     * Tworzy formularz logowania do panelu admina
     * return $html - kod html formularza
     */

    function FormularzLogowania() {
        $html = '<div class="logowanie">
            <h2 class="head">Zaloguj do   panelu admina:</h2>
            <form method="post" name="LoginForm" action="'.$_SERVER['REQUEST_URI'].'">
                <table class="logowanie">
                    <tr>
                        <td class="log4_t"><label for="login">Login</label></td>
                        <td><input type="text" id="login" name="login" class="logowanie" required /></td>
                    </tr>
                    <tr>
                        <td class="log4_t"><label for="login_pass">Hasło</label></td>
                        <td><input type="password" id="login_pass" name="login_pass" class="logowanie" required /></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td><input type="submit" name="x1_submit" class="logowanie" value="zaloguj" /></td>
                    </tr>
                </table>
            </form>
        </div>
        ';
    
        return $html;
    }



    /*
     * ListaPodstron
     * Wyświetla listę stron z opcjami edycji, usunięcia i tworzenia nowych stron.
     * Pobiera dane z tabeli `page_list` w bazie danych i wyświetla je w formie tabeli.
     * Dla każdej strony istnieje możliwość edycji lub usunięcia.
     */
    function ListaPodstron() {
        global $conn;        
        $query = "SELECT id, page_title FROM page_list ORDER BY id ASC LIMIT 100" ; // Zwraca id i tytuł strony, sortuje po id ASC i ogranicza ilość wyników do 100
        $result = $conn->query($query);
        echo '<div class="podstrony">
            <h1 class="lista_stron">Lista Stron</h1>
            <table class="stronki">
                <tr class="column_names">
                    <th>ID Strony</th>
                    <th>Tytuł Strony</th>
                    <th>Edytuj</th>
                    <th>Usuń</th>
                </tr>';
        while($row = $result->fetch_assoc()) {
            echo '<tr class="el_listy">
                <td style="color: white;">' . htmlspecialchars($row['id']) . '</td>
                <td style="color: white;">' . htmlspecialchars($row['page_title']) . '</td>
                <td><a class="edit-button" href="?idp=-2&ide=' . htmlspecialchars($row['id']) . '">Edit</a></td>
                <td><a class="delete-button" href="?idp=-3&idd=' . htmlspecialchars($row['id']) . '" onclick="return confirm(\'Czy jesteś pewien?\');">Delete</a></td>
            </tr>';
        }
        echo '</table>
            <a class="create_page" href="?idp=-4">Create New Page</a>
            <a class="categories" href="?idp=-8">Kategorie w sklepie</a>
            <a class="products" href="?idp=-12">Produkty w sklepie</a>
        </div>';
    }
     


    /*
     * CheckLogin
     * Sprawdza, czy użytkownik jest zalogowany poprzez sesję lub dane logowania z formularza.
     * Użytkownik jest zalogowany, jeśli istnieje sesja 'loggedin' i ma wartość true.
     * Jeśli nie ma sesji, to sprawdza, czy dane logowania z formularza są poprawne.
     */
     function CheckLogin() {
        // Sprawdza, czy sesja 'loggedin' istnieje i jest ustawiona na true
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
            return 1; 
        }
        // Sprawdza, czy dane logowania zostały przesłane przez formularz
        if(isset($_POST['login']) && isset($_POST['login_pass'])){
            // Weryfikuje dane logowania i zwraca wynik
            return $this->CheckLoginCred($_POST['login'], $_POST['login_pass']);
        } else {
            return 0; // Zwraca 0, jeśli użytkownik nie jest zalogowany
        }
    }



    /*
     * CheckLoginCred
     * Sprawdza, czy dane logowania z formularza są poprawne.
     * $login - login z formularza
     * $pass - hasło z formularza
     */

     function CheckLoginCred($login, $pass){
        if($login == admin && $pass == pass){
            $_SESSION['loggedin'] = true;
            return 1;
        } else {
            echo "Logowanie się nie powiodło.";
            return 0;
        }
    }


    /*
     * LoginAdmin
     * Wyświetla panel admina z listą stron, jeśli użytkownik jest zalogowany,
     * w przeciwnym razie wyświetla formularz logowania.
     */
    function LoginAdmin() {
        $status_login = $this->CheckLogin();
        if($status_login == 1) {
            return $this->ListaPodstron();
        } else {
            return $this->FormularzLogowania();
        }
    }



    /*
     * EditPage
     * Funkcja umożliwia edytowanie istniejącej strony w serwisie.
     * Sprawdza, czy użytkownik jest zalogowany, a następnie weryfikuje, 
     * czy ID strony do edycji zostało podane.
     * Jeśli formularz edycji został przesłany, aktualizuje dane strony w bazie danych.
     * W przeciwnym wypadku, wyświetla formularz edycji z aktualnymi danymi strony.
     * Jeśli użytkownik nie jest zalogowany, wyświetla formularz logowania.
     */
    function EditPage() {
        // Sprawdza, czy użytkownik jest zalogowany
        $status_login = $this->CheckLogin();
        if($status_login == 1){
            if(isset($_GET['ide'])){
                // Sprawdza, czy formularz edycji został przesłany
                if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_title'], $_POST['edit_content'])) {
                    // Aktualizuje dane strony w bazie danych
                    $title = $GLOBALS['conn']->real_escape_string($_POST['edit_title']);
                    $content = $GLOBALS['conn']->real_escape_string($_POST['edit_content']);
                    $active = isset($_POST['edit_active']) ? 1 : 0;
                    $id = intval($_GET['ide']);

                    // Tworzy zapytanie do bazy danych o aktualizację strony
                    $query = "UPDATE page_list SET page_title='$title', page_content='$content', status='$active' WHERE id='$id' LIMIT 1";

                    // Wykonuje zapytanie i sprawdza, czy się powiodło
                    if($GLOBALS['conn']->query($query) === TRUE){
                        echo 'Strona zaktualizowana';
                        // Przekierowuje na panel admina
                        header("Location: ?idp=-1");
                        exit;
                    } else {
                        echo "Nie powiodlo się" . $GLOBALS['conn']->error;
                    }
                } else {
                    // Pobiera dane strony z bazy danych
                    $query = "SELECT * FROM page_list WHERE id=" . intval($_GET['ide']) . " LIMIT 1";
                    $result = $GLOBALS['conn']->query($query);

                    // Sprawdza, czy dane strony zostały pobrane
                    if($result && $result->num_rows > 0) {
                        // Pobiera dane strony z bazy danych
                        $row = $result->fetch_assoc();
                        // Wyświetla formularz edycji z aktualnymi danymi strony
                        return '
                            <div class="edit-container">
                                <h3 class="edit-title">Edytuj stronę</h3>
                                <form method="post" action="' . $_SERVER['REQUEST_URI'] . '">
                                    <div class="form-group">
                                        <label for="edit_title">Tytuł:</label><br>
                                        <input type="text" id="edit_title" name="edit_title" value="' . htmlspecialchars($row['page_title']) . '" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_content">Zawartość:</label><br>
                                        <textarea id="edit_content" name="edit_content" required>' . htmlspecialchars($row['page_content']) . '</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_active">Aktywna:</label><br>
                                        <input type="checkbox" id="edit_active" name="edit_active" ' . ($row['status'] ? 'checked' : '') . ' />
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" class="submit-button" value="Zapisz zmiany" />
                                    </div>
                                </form>
                            </div>';
                            
                    } else {
                        return "nie ma strony edycja";
                    }
                }
            } else {
                return "Nie znaleziono id";
            }
        } else {
            // Wyświetla formularz logowania, jeśli nie jest zalogowany
            return $this->FormularzLogowania();
        }
    }



    /*
     * CreatePage
     * Wyświetla formularz dodawania nowej strony, jeśli użytkownik jest zalogowany.
     * Jeśli formularz został wysłany, dodaje nową stronę do bazy danych i przekierowuje na panel admina.
     * Jeśli nie jest zalogowany, wyświetla formularz logowania.
     */
    function CreatePage(){
        // Sprawdza, czy użytkownik jest zalogowany
        if($this->CheckLogin() == 1){
            echo '<h3 class="create_page"> Nowa strona </h3>';
            if(isset($_POST['create_title'], $_POST['create_content'])){
                // Konwertuje dane z formularza do postaci bezpiecznej dla bazy danych
                $title = $GLOBALS['conn']->real_escape_string($_POST['create_title']);
                $content = $GLOBALS['conn']->real_escape_string($_POST['create_content']);
                $active = isset($_POST['create_active']) ? 1 : 0;
                
                // Wykonuje zapytanie do bazy danych o dodanie nowej strony
                if($GLOBALS['conn']->query("INSERT INTO page_list (page_title, page_content, status) VALUES ('$title', '$content', '$active')") === TRUE){
                    // Przekierowuje na panel admina
                    header("Location: ?idp=-1");
                    exit;
                }
            }
            // Wyświetla formularz dodawania nowej strony
            return '
            <div class="create-container">
            <form method="post" action="' . $_SERVER['REQUEST_URI'] . '">
                <div class="form-group">
                    <label for="create_title">Tytuł:</label>
                    <input type="text" id="create_title" name="create_title" required />
                </div>
                <div class="form-group">
                    <label for="create_content">Zawartość:</label>
                    <textarea id="create_content" name="create_content" required></textarea>
                </div>
                <div class="form-group">
                    <label for="create_active">Aktywna:</label>
                    <input type="checkbox" id="create_active" name="create_active" />
                </div>
                
                <div class="form-group">
                    <input type="submit" class="submit-button" value="Dodaj stronę" />
                </div>
            </form>
        </div>';
        } else {
            // Wyświetla formularz logowania, jeśli nie jest zalogowany
            return $this->FormularzLogowania(); 
        }
    }

    function DeletePage() {
        // Sprawdza, czy użytkownik jest zalogowany
        $status_login = $this->CheckLogin(); 
    
        if ($status_login == 1) { 

            if (isset($_GET['idd'])) {
                $id = intval($_GET['idd']); 
                // Tworzy zapytanie do bazy danych o usunięcie strony
                $query = "DELETE FROM page_list WHERE id='$id' LIMIT 1";

                // Wykonuje zapytanie i sprawdza, czy się powiodło
                if ($GLOBALS['conn']->query($query) === TRUE) {
                    echo "Strona została usunięta pomyślnie.";
                    // Przekierowuje na panel admina
                    header("Location: ?idp=-1"); 
                    exit;
                } else {
                    echo "Błąd podczas usuwania: " . $GLOBALS['conn']->error;
                }
            } else {
                echo "Nie podano ID strony do usunięcia.";
            }
        } else {
            // Wyświetla formularz logowania, jeśli nie jest zalogowany
            return $this->FormularzLogowania(); 
        }
    }


    function ZarzadzajProduktami() {
        if($this->CheckLogin() != 1) {
            return $this->FormularzLogowania();
        }
    
        $message = '';
        $form = '';
        
        // Obsługa usuwania produktu
        if(isset($_POST['delete_product'])) {
            $id = intval($_POST['delete_id']);
            $query = "DELETE FROM produkty WHERE id = $id";
            if($GLOBALS['conn']->query($query)) {
                $message = '<div class="success-message">Produkt został usunięty pomyślnie.</div>';
            } else {
                $message = '<div class="error-message">Błąd podczas usuwania produktu: ' . $GLOBALS['conn']->error . '</div>';
            }
        }
        
        // Obsługa dodawania/edytowania produktu
        if(isset($_POST['submit_product'])) {
            $title = $GLOBALS['conn']->real_escape_string($_POST['title']);
            $description = $GLOBALS['conn']->real_escape_string($_POST['description']);
            $price_net = floatval($_POST['price_net']);
            $vat = floatval($_POST['vat']);
            $quantity = intval($_POST['quantity']);
            $category = $GLOBALS['conn']->real_escape_string($_POST['category']);
            $size = $GLOBALS['conn']->real_escape_string($_POST['size']);
            $expiry_date = $_POST['expiry_date'] ? "'" . $_POST['expiry_date'] . "'" : "NULL";
            $status = isset($_POST['status']) ? 1 : 0;
    
            if(isset($_POST['edit_id'])) {
                // Aktualizacja istniejącego produktu
                $id = intval($_POST['edit_id']);
                $query = "UPDATE produkty SET 
                         tytul = '$title',
                         opis = '$description',
                         cena_netto = $price_net,
                         podatek_vat = $vat,
                         ilosc_dostepnych = $quantity,
                         status_dostepnosci = $status,
                         kategoria = '$category',
                         gabaryt = '$size',
                         data_wygasniecia = $expiry_date
                         WHERE id = $id";
            } else {
                // Dodawanie nowego produktu
                $query = "INSERT INTO produkty 
                         (tytul, opis, cena_netto, podatek_vat, ilosc_dostepnych, 
                          status_dostepnosci, kategoria, gabaryt, data_wygasniecia) 
                         VALUES 
                         ('$title', '$description', $price_net, $vat, $quantity,
                          $status, '$category', '$size', $expiry_date)";
            }
    
            if($GLOBALS['conn']->query($query)) {
                $message = '<div class="success-message">Produkt został ' . 
                          (isset($_POST['edit_id']) ? 'zaktualizowany' : 'dodany') . ' pomyślnie.</div>';
            } else {
                $message = '<div class="error-message">Błąd podczas ' . 
                          (isset($_POST['edit_id']) ? 'aktualizacji' : 'dodawania') . 
                          ' produktu: ' . $GLOBALS['conn']->error . '</div>';
            }
        }
    
        // Formularz edycji jeśli wybrano produkt
        if(isset($_GET['edit'])) {
            $id = intval($_GET['edit']);
            $query = "SELECT * FROM produkty WHERE id = $id";
            $result = $GLOBALS['conn']->query($query);
            $product = $result->fetch_assoc();
    
            if($product) {
                $form = $this->ProductForm($product);
            }
        } else {
            // Formularz dodawania nowego produktu
            $form = $this->ProductForm();
        }
    
        // Lista wszystkich produktów
        $query = "SELECT * FROM produkty ORDER BY data_utworzenia DESC";
        $result = $GLOBALS['conn']->query($query);
    
        $output = '
        <div class="products-management">
            <h2>Zarządzanie Produktami</h2>
            ' . $message . '
            
            <div class="product-form-section">
                <h3>' . (isset($_GET['edit']) ? 'Edytuj Produkt' : 'Dodaj Nowy Produkt') . '</h3>
                ' . $form . '
            </div>
    
            <div class="products-list">
                <h3>Lista Produktów</h3>
                <table class="products-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tytuł</th>
                            <th>Cena Netto</th>
                            <th>VAT</th>
                            <th>Ilość</th>
                            <th>Kategoria</th>
                            <th>Status</th>
                            <th>Data utworzenia</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>';
    
        while($row = $result->fetch_assoc()) {
            $output .= '
                <tr>
                    <td>' . $row['id'] . '</td>
                    <td>' . htmlspecialchars($row['tytul']) . '</td>
                    <td>' . number_format($row['cena_netto'], 2) . ' zł</td>
                    <td>' . $row['podatek_vat'] . '%</td>
                    <td>' . $row['ilosc_dostepnych'] . '</td>
                    <td>' . htmlspecialchars($row['kategoria']) . '</td>
                    <td>' . ($row['status_dostepnosci'] ? 'Dostępny' : 'Niedostępny') . '</td>
                    <td>' . $row['data_utworzenia'] . '</td>
                    <td class="actions">
                        <a href="?idp=-13&edit=' . $row['id'] . '" class="edit-btn">Edytuj</a>
                        <form method="post" style="display: inline;" onsubmit="return confirm(\'Czy na pewno chcesz usunąć ten produkt?\');">
                            <input type="hidden" name="delete_id" value="' . $row['id'] . '">
                            <input type="submit" name="delete_product" value="Usuń" class="delete-btn">
                        </form>
                    </td>
                </tr>';
        }
    
        $output .= '
                    </tbody>
                </table>
            </div>
        </div>';
    
        return $output;
    }
    
    private function ProductForm($product = null) {
        $form = '
        <form method="post" action="" class="product-form">
            ' . ($product ? '<input type="hidden" name="edit_id" value="' . $product['id'] . '">' : '') . '
            
            <div class="form-group">
                <label for="title">Tytuł:</label>
                <input type="text" name="title" id="title" value="' . ($product ? htmlspecialchars($product['tytul']) : '') . '" required>
            </div>
            
            <div class="form-group">
                <label for="description">Opis:</label>
                <textarea name="description" id="description">' . ($product ? htmlspecialchars($product['opis']) : '') . '</textarea>
            </div>
            
            <div class="form-group">
                <label for="price_net">Cena netto:</label>
                <input type="number" name="price_net" id="price_net" step="0.01" value="' . ($product ? $product['cena_netto'] : '') . '" required>
            </div>
            
            <div class="form-group">
                <label for="vat">VAT (%):</label>
                <input type="number" name="vat" id="vat" step="0.01" value="' . ($product ? $product['podatek_vat'] : '23') . '" required>
            </div>
            
            <div class="form-group">
                <label for="quantity">Ilość dostępnych:</label>
                <input type="number" name="quantity" id="quantity" value="' . ($product ? $product['ilosc_dostepnych'] : '0') . '" required>
            </div>
            
            <div class="form-group">
                <label for="category">Kategoria:</label>
                <input type="text" name="category" id="category" value="' . ($product ? htmlspecialchars($product['kategoria']) : '') . '">
            </div>
            
            <div class="form-group">
                <label for="size">Gabaryt:</label>
                <input type="text" name="size" id="size" value="' . ($product ? htmlspecialchars($product['gabaryt']) : '') . '">
            </div>
            
            <div class="form-group">
                <label for="expiry_date">Data wygaśnięcia:</label>
                <input type="datetime-local" name="expiry_date" id="expiry_date" 
                       value="' . ($product && $product['data_wygasniecia'] ? date('Y-m-d\TH:i', strtotime($product['data_wygasniecia'])) : '') . '">
            </div>
            
            <div class="form-group">
                <label for="status">Dostępny:</label>
                <input type="checkbox" name="status" id="status" ' . (!$product || $product['status_dostepnosci'] ? 'checked' : '') . '>
            </div>
            
            <div class="form-group">
                <input type="submit" name="submit_product" value="' . ($product ? 'Zapisz zmiany' : 'Dodaj produkt') . '" class="submit-btn">
                ' . ($product ? '<a href="?idp=-13" class="cancel-btn">Anuluj</a>' : '') . '
            </div>
        </form>';
    
        return $form;
    }

    /*
     * Wyloguj
     * Funkcja wylogowuje zalogowanego użytkownika, usuwając zmienną sesyjną 'loggedin'.
     * Po wylogowaniu przekierowuje na stronę główną.
     */
    function Wyloguj() {
        if(isset($_SESSION['loggedin'])) {
            unset($_SESSION['loggedin']);
        }
        header('Location: ?idp=1'); // przekierowanie na stronę głowną
        exit;
    }

}

?>