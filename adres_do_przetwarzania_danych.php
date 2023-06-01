<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pobranie danych z formularza
    $jednostka_chorobowa = $_POST['jednostka_chorobowa'];
    $objawy_ogolne_miejscowe = $_POST['objawy_ogolne_miejscowe'];
    $objawy_miejscowe_ju = $_POST['objawy_miejscowe_ju'];
    $rozpoznanie = $_POST['rozpoznanie'];
    $roznicowanie = $_POST['roznicowanie'];

    // Sprawdzanie czy pola formularza nie są puste
    if (empty($jednostka_chorobowa) || empty($objawy_ogolne_miejscowe) || empty($objawy_miejscowe_ju) || empty($rozpoznanie) || empty($roznicowanie)) {
        echo "Wypełnij wszystkie pola formularza.";
    } else {
        // Połączenie z bazą danych PostgreSQL
        function get_conn_string()
        {
            $ini = parse_ini_file("php.ini");
            $host = $ini["dbhost"];
            $db = $ini["dbname"];
            $usr = $ini["dbuser"];
            $pass = $ini["dbpass"];
            $conn_string = "host=$host port=5432 dbname=$db user=$usr password=$pass";
            return $conn_string;
        }

        $conn = pg_connect(get_conn_string());

        // Zabezpieczenie przed SQL Injection
        $jednostka_chorobowa = pg_escape_string($jednostka_chorobowa);
        $objawy_ogolne_miejscowe = pg_escape_string($objawy_ogolne_miejscowe);
        $objawy_miejscowe_ju = pg_escape_string($objawy_miejscowe_ju);
        $rozpoznanie = pg_escape_string($rozpoznanie);
        $roznicowanie = pg_escape_string($roznicowanie);

        // Wstawienie danych choroby do bazy danych
        $query = "INSERT INTO choroba (jednostka_chorobowa, objawy_ogolne_miejscowe, objawy_miejscowe_ju, rozpoznanie, roznicowanie) 
                  VALUES ('$jednostka_chorobowa', '$objawy_ogolne_miejscowe', '$objawy_miejscowe_ju', '$rozpoznanie', '$roznicowanie')";
        $result = pg_query($conn, $query);

        if ($result) {
            echo "Choroba została dodana do bazy danych.";
        } else {
            echo "Wystąpił błąd podczas dodawania choroby do bazy danych.";
        }

        // Zamknięcie połączenia z bazą danych
        pg_close($conn);
    }
}
?>
