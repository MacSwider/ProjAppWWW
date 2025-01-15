<?php
class Produkty {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    public function pokazSklep() {
        $output = '<div class="shop-container" style="color: #000;">';
        $output .= '<h2>Sklep</h2>';
    
        // Obsługa dodawania do koszyka
        if (isset($_GET['action']) && $_GET['action'] === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['id_produktu'], $_POST['tytul'], $_POST['cena_netto'], $_POST['podatek_vat'], $_POST['ilosc'])) {
                $id_produktu = $_POST['id_produktu'];
                $tytul = $_POST['tytul'];
                $cena_netto = (float)$_POST['cena_netto'];
                $podatek_vat = (float)$_POST['podatek_vat'];
                $ilosc = (int)$_POST['ilosc'];
                
                // Oblicz cenę brutto
                $cena_brutto = $cena_netto * (1 + $podatek_vat / 100);
    
                // Dodaj lub zaktualizuj produkt w koszyku
                if (isset($_SESSION['koszyk'][$id_produktu])) {
                    $_SESSION['koszyk'][$id_produktu]['ilosc'] += $ilosc;
                } else {
                    $_SESSION['koszyk'][$id_produktu] = [
                        'tytul' => $tytul,
                        'cena_netto' => $cena_netto,
                        'podatek_vat' => $podatek_vat,
                        'cena_brutto' => $cena_brutto,
                        'ilosc' => $ilosc
                    ];
                }
                
                header('Location: index.php?idp=11&added=1');
                exit;
            }
        }
    
        // Komunikat o dodaniu do koszyka
        if (isset($_GET['added'])) {
            $output .= '<div class="success-message">Produkt został dodany do koszyka!</div>';
        }
    
        $query = "SELECT * FROM produkty ORDER BY data_utworzenia DESC";
        $result = $this->conn->query($query);
    
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
            $output .= '<div class="admin-link"><a href="?idp=12" class="btn-edit">Panel zarządzania produktami</a></div>';
        }
    
        $output .= '<div class="products-grid">';
    
        if($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $cena_brutto = $row['cena_netto'] * (1 + ($row['podatek_vat'] / 100));
                
                $output .= '
                <div class="product-card">
                    <div class="product-image">';
                
                if($row['zdjecie']) {
                    $image_data = base64_encode($row['zdjecie']);
                    $output .= '<img src="data:image/jpeg;base64,' . $image_data . '" alt="' . htmlspecialchars($row['tytul']) . '">';
                } else {
                    $output .= '<img src="img/no-image.png" alt="Brak zdjęcia">';
                }
                
                $output .= '
                    </div>
                    <h3>' . htmlspecialchars($row['tytul']) . '</h3>
                    <p class="description">' . htmlspecialchars($row['opis']) . '</p>
                    <div class="price-info">
                        <p>Cena netto: ' . number_format($row['cena_netto'], 2) . ' zł</p>
                        <p>Cena brutto: ' . number_format($cena_brutto, 2) . ' zł</p>
                        <p>VAT: ' . $row['podatek_vat'] . '%</p>
                    </div>
                    <div class="product-meta">
                        <p>Status: ' . ($row['status_dostepnosci'] ? 'Dostępny' : 'Niedostępny') . '</p>
                        <p>Ilość: ' . $row['ilosc_dostepnych'] . ' szt.</p>
                        <p>Kategoria: ' . htmlspecialchars($row['kategoria']) . '</p>
                    </div>
                    <form method="post" action="index.php?idp=11&action=add" class="add-to-cart-form">
                        <input type="hidden" name="id_produktu" value="' . $row['id'] . '">
                        <input type="hidden" name="tytul" value="' . htmlspecialchars($row['tytul']) . '">
                        <input type="hidden" name="cena_netto" value="' . $row['cena_netto'] . '">
                        <input type="hidden" name="podatek_vat" value="' . $row['podatek_vat'] . '">
                        <input type="number" name="ilosc" value="1" min="1" max="' . $row['ilosc_dostepnych'] . '" class="quantity-input">
                        <button type="submit" class="btn-add-to-cart">Dodaj do koszyka</button>
                    </form>
                </div>';
            }
        } else {
            $output .= '<p class="no-products">Brak produktów w sklepie.</p>';
        }
    
        $output .= '</div></div>';
        return $output;
    }

    public function zarzadzajProduktem() {
        if(!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
            return '<p class="error">Dostęp zabroniony. Zaloguj się jako administrator.</p>';
        }

        $message = '';

        // Handle form submission
        if(isset($_POST['submit'])) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : null;
            $title = $this->conn->real_escape_string($_POST['title']);
            $description = $this->conn->real_escape_string($_POST['description']);
            $price = floatval($_POST['price']);
            $vat = floatval($_POST['vat']);
            $quantity = intval($_POST['quantity']);
            $category = $this->conn->real_escape_string($_POST['category']);
            $status = isset($_POST['status']) ? 1 : 0;
            
            // Handle image upload
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['image']['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                if(in_array($ext, $allowed)) {
                    $image_data = file_get_contents($_FILES['image']['tmp_name']);
                    
                    if($id) {
                        // Update existing product with new image
                        $stmt = $this->conn->prepare("UPDATE produkty SET 
                            tytul = ?, 
                            opis = ?, 
                            cena_netto = ?,
                            podatek_vat = ?,
                            ilosc_dostepnych = ?,
                            kategoria = ?,
                            status_dostepnosci = ?,
                            zdjecie = ?
                            WHERE id = ?");
                        
                        $stmt->bind_param("ssddissbi", $title, $description, $price, $vat, $quantity, $category, $status, $image_data, $id);
                    } else {
                        // Insert new product with image
                        $stmt = $this->conn->prepare("INSERT INTO produkty 
                            (tytul, opis, cena_netto, podatek_vat, ilosc_dostepnych, kategoria, status_dostepnosci, zdjecie) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                        
                        $stmt->bind_param("ssddissb", $title, $description, $price, $vat, $quantity, $category, $status, $image_data);
                    }
                } else {
                    $message = '<div class="error">Niedozwolony format pliku. Dozwolone formaty: jpg, jpeg, png, gif</div>';
                }
            } else {
                // Update without changing the image
                if($id) {
                    $stmt = $this->conn->prepare("UPDATE produkty SET 
                        tytul = ?, 
                        opis = ?, 
                        cena_netto = ?,
                        podatek_vat = ?,
                        ilosc_dostepnych = ?,
                        kategoria = ?,
                        status_dostepnosci = ?
                        WHERE id = ?");
                    
                    $stmt->bind_param("ssddissi", $title, $description, $price, $vat, $quantity, $category, $status, $id);
                } else {
                    $stmt = $this->conn->prepare("INSERT INTO produkty 
                        (tytul, opis, cena_netto, podatek_vat, ilosc_dostepnych, kategoria, status_dostepnosci) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)");
                    
                    $stmt->bind_param("ssddiss", $title, $description, $price, $vat, $quantity, $category, $status);
                }
            }

            if(isset($stmt)) {
                if($stmt->execute()) {
                    $message = '<div class="success">Produkt został ' . ($id ? 'zaktualizowany' : 'dodany') . ' pomyślnie.</div>';
                } else {
                    $message = '<div class="error">Błąd: ' . $stmt->error . '</div>';
                }
                $stmt->close();
            }
        }

        // Handle product deletion
        if(isset($_POST['delete'])) {
            $id = intval($_POST['id']);
            if($this->conn->query("DELETE FROM produkty WHERE id = $id")) {
                $message = '<div class="success">Produkt został usunięty.</div>';
            }
        }

        // Get product for editing
        $editProduct = null;
        if(isset($_GET['edit'])) {
            $id = intval($_GET['edit']);
            $result = $this->conn->query("SELECT * FROM produkty WHERE id = $id");
            $editProduct = $result->fetch_assoc();
        }

        // Form HTML
        $output = '
        <div class="admin-panel">
            <h2>' . ($editProduct ? 'Edytuj produkt' : 'Dodaj nowy produkt') . '</h2>
            ' . $message . '
            <form method="post" class="product-form" enctype="multipart/form-data">
                ' . ($editProduct ? '<input type="hidden" name="id" value="' . $editProduct['id'] . '">' : '') . '
                
                <div class="form-group">
                    <label>Zdjęcie produktu:</label>
                    <input type="file" name="image" accept="image/*">
                    ' . ($editProduct && $editProduct['zdjecie'] ? '
                        <div class="current-image">
                            <p>Aktualne zdjęcie:</p>
                            <img src="data:image/jpeg;base64,' . base64_encode($editProduct['zdjecie']) . '" alt="Aktualne zdjęcie" style="max-width: 200px;">
                        </div>' : '') . '
                </div>
                
                <div class="form-group">
                    <label>Tytuł:</label>
                    <input type="text" name="title" required value="' . ($editProduct ? htmlspecialchars($editProduct['tytul']) : '') . '">
                </div>
                
                <div class="form-group">
                    <label>Opis:</label>
                    <textarea name="description">' . ($editProduct ? htmlspecialchars($editProduct['opis']) : '') . '</textarea>
                </div>
                
                <div class="form-group">
                    <label>Cena netto:</label>
                    <input type="number" step="0.01" name="price" required value="' . ($editProduct ? $editProduct['cena_netto'] : '') . '">
                </div>
                
                <div class="form-group">
                    <label>VAT (%):</label>
                    <input type="number" step="0.01" name="vat" required value="' . ($editProduct ? $editProduct['podatek_vat'] : '23') . '">
                </div>
                
                <div class="form-group">
                    <label>Ilość:</label>
                    <input type="number" name="quantity" required value="' . ($editProduct ? $editProduct['ilosc_dostepnych'] : '0') . '">
                </div>
                
                <div class="form-group">
                    <label>Kategoria:</label>
                    <input type="text" name="category" value="' . ($editProduct ? htmlspecialchars($editProduct['kategoria']) : '') . '">
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="status" ' . (!$editProduct || $editProduct['status_dostepnosci'] ? 'checked' : '') . '>
                        Dostępny
                    </label>
                </div>
                
                <div class="form-buttons">
                    <input type="submit" name="submit" value="' . ($editProduct ? 'Zapisz zmiany' : 'Dodaj produkt') . '" class="btn-submit">
                    ' . ($editProduct ? '<a href="?idp=12" class="btn-cancel">Anuluj</a>' : '') . '
                </div>
            </form>

            <h3>Lista produktów</h3>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Nazwa</th>
                        <th>Cena</th>
                        <th>Ilość</th>
                        <th>Status</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>';

        $result = $this->conn->query("SELECT * FROM produkty ORDER BY data_utworzenia DESC");
        while($row = $result->fetch_assoc()) {
            $output .= '
                <tr>
                    <td>' . htmlspecialchars($row['tytul']) . '</td>
                    <td>' . number_format($row['cena_netto'], 2) . ' zł</td>
                    <td>' . $row['ilosc_dostepnych'] . '</td>
                    <td>' . ($row['status_dostepnosci'] ? 'Dostępny' : 'Niedostępny') . '</td>
                    <td>
                        <a href="?idp=12&edit=' . $row['id'] . '" class="btn-edit">Edytuj</a>
                        <form method="post" style="display:inline;" onsubmit="return confirm(\'Czy na pewno chcesz usunąć ten produkt?\')">
                            <input type="hidden" name="id" value="' . $row['id'] . '">
                            <input type="submit" name="delete" value="Usuń" class="btn-delete">
                        </form>
                    </td>
                </tr>';
        }

        $output .= '
                </tbody>
            </table>
        </div>';

        return $output;
    }
}
?>