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

// Pobierz identyfikator choroby z parametru URL
$id = $_GET['id'];

// Połączenie z bazą danych PostgreSQL
$conn = pg_connect(get_conn_string());

// Usunięcie choroby o podanym identyfikatorze
$query = "DELETE FROM wirus WHERE id = $id";
$result = pg_query($conn, $query);

if ($result) {
    echo "Wirus został usunięty, ale i tak warto się zaszczepić.";
} else {
    echo "Wystąpił błąd podczas usuwania wirusa.";
}

// Zamknięcie połączenia z bazą danych
pg_close($conn);
?>
