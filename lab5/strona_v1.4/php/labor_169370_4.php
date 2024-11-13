<?php
session_start();

$nr_indeksu = '169370';  
$nrGrupy = '4';  
echo 'Maciej Świder '.$nr_indeksu.' grupa '.$nrGrupy.'<br /><br />';

echo 'Zastosowanie metody include() <br />';
// Wczytanie pliku testinclude.php
include('testinclude.php');  
echo 'Zmienne ściągnięte metodą include(): ' .$interest. ', '.$color.'<br /><br />';

echo 'Użycie metody require_once <br />';
// Wczytanie pliku testrequire.php
require_once('testrequire.php');  
echo 'Zmienne ściągnięte metodą require_once(): ' .$someVariable. '<br /><br />';

// a) Metoda include(), require_once() 
echo 'a) Zastosowanie metod include() oraz require_once() <br />';
echo 'Zmienna ściągnięta metodą include(): ' .$interest. ', '.$color.'<br />';
echo 'Zmienna ściągnięta metodą require_once(): ' .$someVariable. '<br /><br />';

// b) Warunki if, else, elseif, switch
echo 'b) Warunki if, else, elseif, switch <br />';
$number = 5;

if ($number > 10) {
    echo 'Liczba jest większa od 10.<br />';
} elseif ($number == 10) {
    echo 'Liczba jest równa 10.<br />';
} else {
    echo 'Liczba jest mniejsza od 10.<br />';
}

// Użycie switch
switch ($number) {
    case 1:
        echo 'Liczba to 1.<br />';
        break;
    case 5:
        echo 'Liczba to 5.<br />';
        break;
    default:
        echo 'Liczba nie jest ani 1, ani 5.<br />';
}

// c) Pętla while() i for()
echo 'c) Pętla while() i for() <br />';

// Pętla while
$i = 0;
echo 'Wynik pętli while:<br />';
while ($i < 5) {
    echo 'To jest iteracja nr: ' . $i . '<br />';
    $i++;
}

// Pętla for
echo 'Wynik pętli for:<br />';
for ($j = 0; $j < 5; $j++) {
    echo 'To jest iteracja nr: ' . $j . '<br />';
}

// d) Typy zmiennych $_GET, $_POST, $_SESSION
echo 'd) Typy zmiennych $_GET, $_POST, $_SESSION <br />';

// Przykładowe dane w $_GET
$_GET['name'] = 'Maciej';
$_GET['age'] = 25;
echo 'Zmienna $_GET: imię - ' . $_GET['name'] . ', wiek - ' . $_GET['age'] . '<br />';

// Przykładowe dane w $_POST
$_POST['email'] = 'maciej@example.com';
echo 'Zmienna $_POST: email - ' . $_POST['email'] . '<br />';

// Przykładowe dane w $_SESSION
$_SESSION['loggedIn'] = true;
echo 'Zmienna $_SESSION: zalogowany - ' . ($_SESSION['loggedIn'] ? 'tak' : 'nie') . '<br />';

?>