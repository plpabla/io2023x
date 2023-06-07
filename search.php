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

// Rozbijanie wyszukiwanej frazy na poszczególne słowa                      @
$searchTerms = explode(" ", $search);
$searchTerms = array_filter($searchTerms); // Usunięcie pustych elementów

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

          $conditions = array();
foreach ($searchTerms as $term) {
    $condition = "c.choroba ILIKE '%" . pg_escape_string($term) . "%'
                  OR w.nazwa ILIKE '%" . pg_escape_string($term) . "%'
                  OR c.objawy_ogolne ILIKE '%" . pg_escape_string($term) . "%'
                  OR c.objawy_ju ILIKE '%" . pg_escape_string($term) . "%'
                  OR c.rozpoznanie ILIKE '%" . pg_escape_string($term) . "%'
                  OR c.roznicowanie ILIKE '%" . pg_escape_string($term) . "%'";
    $conditions[] = $condition;
}

$query .= implode(" AND ", $conditions);
$query .= " ORDER BY c.choroba";

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
    foreach ($searchTerms as $term) {
        $suggestion = '';
        if (stripos($row['choroba'], $term) !== false) {
            $suggestion = $row['choroba'];
        } elseif (stripos($row['nazwa_wirusa'], $term) !== false) {
            $suggestion = $row['nazwa_wirusa'];
        } elseif (stripos($row['objawy_ogolne'], $term) !== false) {
            $suggestion = $row['objawy_ogolne'];
        } elseif (stripos($row['objawy_ju'], $term) !== false) {
            $suggestion = $row['objawy_ju'];
        } elseif (stripos($row['rozpoznanie'], $term) !== false) {
            $suggestion = $row['rozpoznanie'];
        } elseif (stripos($row['roznicowanie'], $term) !== false) {
            $suggestion = $row['roznicowanie'];
        }
        
        if (!empty($suggestion)) {
            $suggestions[] = $suggestion;
            break; // Zatrzymaj się po znalezieniu pierwszej sugestii dla danego słowa
        }
    }
}


// Ustawienie nagłówka Content-Type na application/json
header('Content-Type: application/json');

// Zwrócenie wyników w formacie JSON
echo json_encode($suggestions);

// Zamknięcie połączenia z bazą danych
pg_close($conn);
?>