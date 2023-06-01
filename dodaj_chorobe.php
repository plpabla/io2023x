<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Dodawanie jednostki chorobowej</title>
</head>

<body>
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
    ?>
        <label for="wirus" style="width: 250px; height: 30px;">Wirus lub rodzina wirusów:</label>
        <select id="wirus" name="wirus" style="width: 250px; height: 30px;">
        <?php
            $conn = pg_connect(get_conn_string());
            $query = "SELECT id_wirus, choroba, objawy_ogolne, objawy_ju, rozpoznanie, roznicowanie FROM choroba";
            $result = pg_query($conn, $query);
            
            while ($row = pg_fetch_assoc($result)) {
                $id_wirus = $row['id_wirus'];
                $query2 = "SELECT nazwa FROM wirus WHERE id=${id_wirus}";
                $res_wir = pg_fetch_row(pg_query($conn, $query2));
                
                echo '<option value="' . $row['nazwa'] . '">' . $row['nazwa'] . '</option>';
            }

            pg_close($conn);
            ?>
        </select>

        <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobierz wartości wprowadzone przez użytkownika
    $jednostka_chorobowa = $_POST["jednostka_chorobowa"];
    $objawy_ogolne_miejscowe = $_POST["objawy_ogolne_miejscowe"];
    $objawy_miejscowe_ju = $_POST["objawy_miejscowe_ju"];
    $rozpoznanie = $_POST["rozpoznanie"];
    $roznicowanie = $_POST["roznicowanie"];

    // Połączenie z bazą danych
    $conn = pg_connect(get_conn_string());

    // Przygotowanie i wykonanie zapytania SQL
    $query = "INSERT INTO tabela (jednostka_chorobowa, objawy_ogolne_miejscowe, objawy_miejscowe_ju, rozpoznanie, roznicowanie)
              VALUES ('$jednostka_chorobowa', '$objawy_ogolne_miejscowe', '$objawy_miejscowe_ju', '$rozpoznanie', '$roznicowanie')";
    $result = pg_query($conn, $query);

    // Sprawdzenie, czy zapytanie zostało wykonane poprawnie
    if ($result) {
        echo "Dane zostały zapisane do bazy danych.";
    } else {
        echo "Wystąpił błąd podczas zapisywania danych.";
    }

    // Zamknięcie połączenia z bazą danych
    pg_close($conn);
}
?>

    <form action="adres_do_przetwarzania_danych.php" method="post">
        <label for="jednostka_chorobowa" style="width: 250px; height: 30px;">Jednostka chorobowa:</label>
        <input type="text" id="jednostka_chorobowa" name="jednostka_chorobowa" style="width: 250px; height: 30px" required><br>

        <label for="objawy_ogolne_miejscowe" style="width: 300px; height: 30px;">Objawy ogólne i miejscowe poza j.u.:</label><br>
        <textarea id="objawy_ogolne_miejscowe" name="objawy_ogolne_miejscowe" style="width: 300px; height: 150px;" required></textarea><br>

        <label for="objawy_miejscowe_ju" style="width: 300px; height: 30px;">Objawy miejscowe w j.u.:</label><br>
        <textarea id="objawy_miejscowe_ju" name="objawy_miejscowe_ju" style="width: 300px; height: 150px;" required></textarea><br>

        <label for="rozpoznanie" style="width: 300px; height: 30px;">Rozpoznanie:</label><br>
        <textarea id="rozpoznanie" name="rozpoznanie" style="width: 300px; height: 80px;" required></textarea><br>

        <label for="roznicowanie" style="width: 300px; height: 30px;">Różnicowanie:</label><br>
        <textarea id="roznicowanie" name="roznicowanie" style="width: 300px; height: 80px;" required></textarea><br>

        <input type="submit" value="Wyślij">
    </form>

</body>

</html>
