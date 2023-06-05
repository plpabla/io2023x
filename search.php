<?php
// Połączenie z bazą danych PostgreSQL
$conn = pg_connect(get_conn_string());

// Pobranie wartości wyszukiwania z pola tekstowego
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Zaktualizuj zapytanie SQL z warunkiem WHERE
$query = "SELECT DISTINCT c.choroba, w.nazwa AS nazwa_wirusa
          FROM choroba c
          JOIN wirus w ON c.id_wirus = w.id
          WHERE c.choroba ILIKE $1
          OR w.nazwa ILIKE $1
          OR c.objawy_ogolne ILIKE $1
          OR c.objawy_ju ILIKE $1
          OR c.rozpoznanie ILIKE $1
          OR c.roznicowanie ILIKE $1
          ORDER BY c.choroba, w.nazwa";

// Przygotowanie zapytania przy użyciu parametrów
$stmt = pg_prepare($conn, "search_query", $query);

// Wykonanie zapytania z przekazanymi parametrami
$result = pg_execute($conn, "search_query", array("%" . $search . "%"));

// Utworzenie tablicy wyników
$results = array();

// Iteracja przez wyniki zapytania i dodanie ich do tablicy wyników
while ($row = pg_fetch_assoc($result)) {
    $results[] = array(
        'choroba' => $row['choroba'],
        'nazwa_wirusa' => $row['nazwa_wirusa'],
        'objawy_ogolne' => $row['objawy_ogolne'],
        'objawy_ju' => $row['objawy_ju'],
        'rozpoznanie' => $row['rozpoznanie'],
        'roznicowanie' => $row['roznicowanie']
    );
}

// Zamknięcie połączenia z bazą danych
pg_close($conn);

// Ustawienie nagłówka Content-Type na application/json
header('Content-Type: application/json');

// Zwrócenie wyników w formacie JSON
echo json_encode($results);
?>
