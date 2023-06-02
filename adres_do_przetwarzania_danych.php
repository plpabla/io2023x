<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pobranie danych z formularza
    $choroba = $_POST['choroba'];
    $objawy_ogolne = $_POST['objawy_ogolne'];
    $objawy_ju = $_POST['objawy_ju'];
    $rozpoznanie = $_POST['rozpoznanie'];
    $roznicowanie = $_POST['roznicowanie'];

    // Sprawdzanie czy pola formularza nie są puste
    if (empty($choroba) || empty($objawy_ogolne) || empty($objawy_ju) || empty($rozpoznanie) || empty($roznicowanie)) {
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
        $choroba = pg_escape_string($choroba);
        $objawy_ogolne = pg_escape_string($objawy_ogolne);
        $objawy_ju = pg_escape_string($objawy_ju);
        $rozpoznanie = pg_escape_string($rozpoznanie);
        $roznicowanie = pg_escape_string($roznicowanie);

        // Wstawienie danych choroby do bazy danych
        $query = "INSERT INTO choroba (choroba, objawy_ogolne, objawy_ju, rozpoznanie, roznicowanie) 
                  VALUES ('$choroba', '$objawy_ogolne', '$objawy_ju', '$rozpoznanie', '$roznicowanie')";
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
