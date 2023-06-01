<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Połączenie z bazą danych PostgreSQL
    $dbconn = pg_connect("host=$host dbname=$db user=$usr password=$pass");

    // Pobranie danych z formularza
    $jednostka_chorobowa = $_POST['jednostka_chorobowa'];
    $objawy_ogolne_miejscowe = $_POST['objawy_ogolne_miejscowe'];
    $objawy_miejscowe_ju = $_POST['objawy_miejscowe_ju'];
    $rozpoznanie = $_POST['rozpoznanie'];
    $roznicowanie = $_POST['roznicowanie'];

    // Zabezpieczenie przed SQL Injection
    $jednostka_chorobowa = pg_escape_string($jednostka_chorobowa);
    $objawy_ogolne_miejscowe = pg_escape_string($objawy_ogolne_miejscowe);
    $objawy_miejscowe_ju = pg_escape_string($objawy_miejscowe_ju);
    $rozpoznanie = pg_escape_string($rozpoznanie);
    $roznicowanie = pg_escape_string($roznicowanie);

    // Wykonanie zapytania INSERT do bazy danych
    $query = "INSERT INTO choroba (jednostka_chorobowa, objawy_ogolne_miejscowe, objawy_miejscowe_ju, rozpoznanie, roznicowanie) 
              VALUES ('$jednostka_chorobowa', '$objawy_ogolne_miejscowe', '$objawy_miejscowe_ju', '$rozpoznanie', '$roznicowanie')";
    $result = pg_query($dbconn, $query);

    // Sprawdzenie wyniku zapytania
    if ($result) {
        // Przekierowanie użytkownika na stronę index.php po pomyślnym dodaniu rekordu
        pg_close($dbconn);
        header("Location: index.php?message=Baza+wirus%C3%B3w+zosta%C5%82a+zaktualizowana");
        exit();
    } else {
        echo "Wystąpił błąd podczas dodawania rekordu.";
    }

    // Zamknięcie połączenia z bazą danych
    pg_close($dbconn);
}
?>

