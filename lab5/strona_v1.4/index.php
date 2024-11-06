<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kosmos - Strona Główna</title>
    <link rel="stylesheet" href="../strona/css/style.css">
</head>
<body>
    <header>
        <h1>Historia Eksploracji Kosmosu</h1>
    </header>

    <nav>
        <ul>
            <li><a href="index.php">Strona Główna</a></li>
            <li><a href="html/rzesza.html">III Rzesza w Kosmosie</a></li>
            <li><a href="html/pierwsze-misje.html">Pierwsze Misje Kosmiczne</a></li>
            <li><a href="html/misje-zalogowe.html">Misje Załogowe</a></li>
            <li><a href="html/stacje.html">Stacje Kosmiczne</a></li>
            <li><a href="html/komercjalizacja.html">Komercjalizacja Kosmosu</a></li>
            <li><a href="html/kontakt.html">Kontakt</a></li>
            <li><a href="html/skrypty.html">Skrypty (Lab2)</a></li>
            <li><a href="html/animacje.html">Animacje (Lab3)</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>Witamy na stronie o eksploracji kosmosu!</h2>
        <p class="text-content">
        Poniższa strona została stworzona w ramach zajęć z Programowania Aplikacji WWW i porusza temat historii lotów kosmicznych. 
        Wykonane z użyciem HTML i CSS
        </p>
        
        <p>
        Przyciski testowe. Brak zaimplementowanej funkcji
        <button class="button" disabled>Przycisk 1</button>
        <button class="button" disabled>Przycisk 2</button>  
        </p>    
    </div> 

    <footer>
        &copy; 2024 Historia Kosmosu
    </footer>

    <?php
    $nr_indeksu = '169370';
    $nrGrupy = '4';
    echo 'Autor: Maciej Świder ' . $nr_indeksu . ' grupa ' . $nrGrupy . ' <br /><br />';
    ?>
</body>
</html>
