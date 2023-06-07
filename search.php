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

// Rozbijanie wyszukiwanej frazy na poszczególne słowa
$searchTerms = explode(" ", $search);
$searchTerms = array_filter($searchTerms); // Usunięcie pustych elementów


// Zaktualizuj zapytanie SQL z warunkiem WHERE
$query = "SELECT DISTINCT c.choroba, w.nazwa AS nazwa_wirusa, c.objawy_ogolne, c.objawy_ju, c.rozpoznanie, c.roznicowanie
          FROM choroba c
          JOIN wirus w ON c.id_wirus = w.id
          WHERE " . implode(" OR ", $conditions) . "
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
    $suggestion = array(
        'choroba' => $row['choroba'],
        'nazwa_wirusa' => $row['nazwa_wirusa'],
        'objawy_ogolne' => $row['objawy_ogolne'],
        'objawy_ju' => $row['objawy_ju'],
        'rozpoznanie' => $row['rozpoznanie'],
        'roznicowanie' => $row['roznicowanie']
    );

    $suggestions[] = $suggestion;
}

// Ustawienie nagłówka Content-Type na application/json
header('Content-Type: application/json');

// Zwrócenie wyników w formacie JSON
echo json_encode($suggestions);

// Zamknięcie połączenia z bazą danych
pg_close($conn);
?>