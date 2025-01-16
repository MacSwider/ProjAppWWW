<?php

// Plik zawiera funkcje do obsługi koszyka zakupowego

// Funkcja dodaje produkt do koszyka lub zwiększa jego ilość jeśli już istnieje
function dodajDoKoszyka($id_produktu, $tytul, $cena_netto, $podatek_vat, $ilosc) {
    // Upewnij się że sesja jest rozpoczęta
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Zainicjuj tablicę koszyka jeśli nie istnieje
    if (!isset($_SESSION['koszyk'])) {
        $_SESSION['koszyk'] = array();
    }

    // Oblicz cenę brutto dodając VAT do ceny netto
    $cena_brutto = $cena_netto * (1 + $podatek_vat / 100);

    // Sprawdź, czy produkt już istnieje w koszyku
    if (isset($_SESSION['koszyk'][$id_produktu])) {
        // Zwiększ ilość produktu w koszyku
        $_SESSION['koszyk'][$id_produktu]['ilosc'] += $ilosc;
    } else {
        // Dodaj nowy produkt do koszyka
        $_SESSION['koszyk'][$id_produktu] = [
            'tytul' => $tytul,
            'cena_netto' => $cena_netto,
            'podatek_vat' => $podatek_vat,
            'cena_brutto' => $cena_brutto,
            'ilosc' => $ilosc
        ];
    }
}

// Funkcja usuwa wybrany produkt z koszyka
function usunZKoszyka($id_produktu) {
    if (isset($_SESSION['koszyk'][$id_produktu])) {
        unset($_SESSION['koszyk'][$id_produktu]);
    }
}

// Funkcja aktualizuje ilość produktu w koszyku
// Jeśli nowa ilość jest <= 0, produkt jest usuwany
function aktualizujIlosc($id_produktu, $nowa_ilosc) {
    if (isset($_SESSION['koszyk'][$id_produktu])) {
        if ($nowa_ilosc > 0) {
            $_SESSION['koszyk'][$id_produktu]['ilosc'] = $nowa_ilosc;
        } else {
            usunZKoszyka($id_produktu);
        }
    }
}

// Funkcja oblicza łączną wartość wszystkich produktów w koszyku
function obliczSumeKoszyka() {
    $suma = 0;
    if (isset($_SESSION['koszyk'])) {
        foreach ($_SESSION['koszyk'] as $produkt) {
            $suma += $produkt['cena_brutto'] * $produkt['ilosc'];
        }
    }
    return $suma;
}

// Funkcja usuwa wszystkie produkty z koszyka
function wyczyscKoszyk() {
    if (isset($_SESSION['koszyk'])) {
        $_SESSION['koszyk'] = array();
    }
}

// Funkcja wyświetla zawartość koszyka w formie tabeli
// Zawiera funkcje do aktualizacji ilości, usuwania produktów
// i przejścia do finalizacji zamówienia
function pokazKoszyk() {
    if (!isset($_SESSION['koszyk'])) {
        $_SESSION['koszyk'] = array();
    }

    echo '<div class="cart-container">';
    echo '<h2>Koszyk</h2>';

    if (empty($_SESSION['koszyk'])) {
        echo '<p class="empty-cart">Twój koszyk jest pusty.</p>';
    } else {
        echo '<div class="cart-items">';
        $suma_netto = 0;
        $suma_brutto = 0;

        foreach ($_SESSION['koszyk'] as $id => $produkt) {
            $cena_netto_total = $produkt['cena_netto'] * $produkt['ilosc'];
            $cena_brutto_total = $produkt['cena_brutto'] * $produkt['ilosc'];
            $suma_netto += $cena_netto_total;
            $suma_brutto += $cena_brutto_total;

            
        }

        echo '<div class="cart-summary">';
        echo '<h3>Podsumowanie</h3>';
        echo '<p>Suma netto: ' . number_format($suma_netto, 2) . ' zł</p>';
        echo '<p>Suma brutto: ' . number_format($suma_brutto, 2) . ' zł</p>';
        echo '</div>';

        echo '<form method="post" class="clear-cart">';
        echo '<button type="submit" name="wyczysc_koszyk">Wyczyść koszyk</button>';
        echo '</form>';
    }

    echo '</div>';

    // Obsługa usuwania produktu z koszyka
    if (isset($_POST['usun_z_koszyka']) && isset($_POST['remove_id'])) {
        $id = $_POST['remove_id'];
        if (isset($_SESSION['koszyk'][$id])) {
            unset($_SESSION['koszyk'][$id]);
            header('Location: index.php?idp=13');
            exit;
        }
    }
    // Obsługa czyszczenia koszyka
    if (isset($_POST['wyczysc_koszyk'])) {
        $_SESSION['koszyk'] = array();
        echo "<script>window.location.href = 'index.php?idp=13';</script>";
        exit;
    }
}

    // Wyświetl tabelę z produktami w koszyku
    echo '<div class="cart-container">
        <h2>Twój koszyk</h2>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Produkt</th>
                    <th>Cena netto</th>
                    <th>VAT</th>
                    <th>Cena brutto</th>
                    <th>Ilość</th>
                    <th>Suma</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>';

    // Wyświetl każdy produkt w osobnym wierszu
    foreach ($_SESSION['koszyk'] as $id_produktu => $produkt) {
        echo '<tr>
            <td>' . htmlspecialchars($produkt['tytul']) . '</td>
            <td>' . number_format($produkt['cena_netto'], 2) . ' zł</td>
            <td>' . $produkt['podatek_vat'] . '%</td>
            <td>' . number_format($produkt['cena_brutto'], 2) . ' zł</td>
            <td>
                <form method="post" action="index.php?idp=13&action=update">
                    <input type="hidden" name="id_produktu" value="' . $id_produktu . '">
                    <input type="number" name="ilosc" value="' . $produkt['ilosc'] . '" min="1" class="quantity-input">
                    <button type="submit" class="update-btn">Aktualizuj</button>
                </form>
            </td>
            <td>' . number_format($produkt['cena_brutto'] * $produkt['ilosc'], 2) . ' zł</td>
            <td>
                <form method="post" action="index.php?idp=13&action=remove">
                    <input type="hidden" name="id_produktu" value="' . $id_produktu . '">
                    <button type="submit" class="remove-btn">Usuń</button>
                </form>
            </td>
        </tr>';
    }

    // Wyświetl podsumowanie i przyciski akcji
    echo '</tbody>
        <tfoot>
            <tr>
                <td colspan="5"><strong>Suma całkowita:</strong></td>
                <td colspan="2"><strong>' . number_format(obliczSumeKoszyka(), 2) . ' zł</strong></td>
            </tr>
        </tfoot>
    </table>
    
    <div class="cart-actions">
        <form method="post" action="index.php?idp=13&action=clear">
            <button type="submit" class="clear-btn">Wyczyść koszyk</button>
        </form>
        <a href="index.php?idp=11" class="button">Kontynuuj zakupy</a>
        <a href="index.php?idp=14" class="button checkout-btn">Przejdź do kasy</a>
    </div>
    </div>';

    // Obsługa akcji (aktualizacja, usuwanie, czyszczenie koszyka)
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'update':
                if (isset($_POST['id_produktu'], $_POST['ilosc'])) {
                    aktualizujIlosc($_POST['id_produktu'], (int)$_POST['ilosc']);
                    echo "<script>window.location.href='index.php?idp=13';</script>";
                }
                break;
            case 'remove':
                if (isset($_POST['id_produktu'])) {
                    usunZKoszyka($_POST['id_produktu']);
                    echo "<script>window.location.href='index.php?idp=13';</script>";
                }
                break;
            case 'clear':
                wyczyscKoszyk();
                echo "<script>window.location.href='index.php?idp=13';</script>";
                break;
        }
    }

?>