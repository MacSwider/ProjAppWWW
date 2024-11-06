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
            <li><a href="index.php?page=rzesza">III Rzesza w Kosmosie</a></li>
            <li><a href="index.php?page=pierwsze-misje">Pierwsze Misje Kosmiczne</a></li>
            <li><a href="index.php?page=misje-zalogowe">Misje Załogowe</a></li>
            <li><a href="index.php?page=stacje">Stacje Kosmiczne</a></li>
            <li><a href="index.php?page=komercjalizacja">Komercjalizacja Kosmosu</a></li>
            <li><a href="index.php?page=kontakt">Kontakt</a></li>
            <li><a href="index.php?page=skrypty">Skrypty (Lab2)</a></li>
            <li><a href="index.php?page=animacje">Animacje (Lab3)</a></li>
        </ul>
    </nav>

    

    <div class="container">
        <?php
        
            if (isset($_GET['page'])) {
                $page = $_GET['page'];

                $valid_pages = ['rzesza', 'pierwsze-misje', 'misje-zalogowe', 'stacje', 'komercjalizacja', 'kontakt', 'skrypty', 'animacje'];
                if (in_array($page, $valid_pages)) {
                    include("html/{$page}.php");
                } else {
                    echo "<p>Strona nie istnieje.</p>";
                }
            } else {
  
                echo "<h2>Witamy na stronie o eksploracji kosmosu!</h2>";
                echo "<p>Poniższa strona została stworzona w ramach zajęć z Programowania Aplikacji WWW i porusza temat historii lotów kosmicznych.</p>";
            }
        ?>
    </div>
    
     
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
        $nr_indeksu = '1234567';
        $nrGrupy = 'X';
        echo 'Autor: Maciej Świder '.$nr_indeksu.' 169370 '.$nrGrupy.' 4';
    ?>

</body>
</html>
