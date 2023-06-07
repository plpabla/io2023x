<?php
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

// Połączenie z bazą danych PostgreSQL
$conn = pg_connect(get_conn_string());

// Pobranie wartości wyszukiwania z pola tekstowego
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Zaktualizuj zapytanie SQL z warunkiem WHERE
$query = "SELECT DISTINCT c.choroba, w.nazwa AS nazwa_wirusa, c.objawy_ogolne, c.objawy_ju, c.rozpoznanie, c.roznicowanie
          FROM choroba c
          JOIN wirus w ON c.id_wirus = w.id
          WHERE c.choroba ILIKE '%" . pg_escape_string($search) . "%'
          OR w.nazwa ILIKE '%" . pg_escape_string($search) . "%'
          OR c.objawy_ogolne ILIKE '%" . pg_escape_string($search) . "%'
          OR c.objawy_ju ILIKE '%" . pg_escape_string($search) . "%'
          OR c.rozpoznanie ILIKE '%" . pg_escape_string($search) . "%'
          OR c.roznicowanie ILIKE '%" . pg_escape_string($search) . "%'
          ORDER BY c.choroba";

// Pobranie danych z tabeli choroba
$result = pg_query($conn, $query);

//sprawdzenie wyniku zapytania
if (!$result) {
    echo pg_last_error($conn);
    exit;
}

// Przygotowanie tablicy na sugestie
$suggestions = array();

while ($row = pg_fetch_assoc($result)) {
    if ($row['choroba']) {
        $words = explode(" ", $row['choroba']);
        foreach ($words as $word) {
            $suggestions[] = $word;
        }
    }
    if ($row['nazwa_wirusa']) {
        $words = explode(" ", $row['nazwa_wirusa']);
        foreach ($words as $word) {
            $suggestions[] = $word;
        }
    }
    if ($row['objawy_ogolne']) {
        $words = explode(" ", $row['objawy_ogolne']);
        foreach ($words as $word) {
            $suggestions[] = $word;
        }
    }
    if ($row['objawy_ju']) {
        $words = explode(" ", $row['objawy_ju']);
        foreach ($words as $word) {
            $suggestions[] = $word;
        }
    }
    if ($row['rozpoznanie']) {
        $words = explode(" ", $row['rozpoznanie']);
        foreach ($words as $word) {
            $suggestions[] = $word;
        }
    }
    if ($row['roznicowanie']) {
        $words = explode(" ", $row['roznicowanie']);
        foreach ($words as $word) {
            $suggestions[] = $word;
        }
    }
}

// Usunięcie duplikatów z tablicy sugestii
$suggestions = array_unique($suggestions);

// Ustawienie nagłówka Content-Type na application/json
header('Content-Type: application/json');

// Zwrócenie wyników w formacie JSON
echo json_encode($suggestions);

// Zamknięcie połączenia z bazą danych
pg_close($conn);
?>