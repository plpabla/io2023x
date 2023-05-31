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

    <form action="adres_do_przetwarzania_danych.php" method="post">
        <label for="wirus">Wirus:</label>
        <select id="wirus" name="wirus">
            <?php
            $conn = pg_connect(get_conn_string());
            $query = "SELECT id_wirus, choroba, objawy_ogolne, objawy_ju, rozpoznanie, roznicowanie FROM choroba";
            $result = pg_query($conn, $query);

            while ($row = pg_fetch_assoc($result)) {
                $id_wirus = $row['id_wirus'];
                $query2 = "SELECT nazwa FROM wirus WHERE id_wirus=$id_wirus";
                $res_wir = pg_query($conn, $query2);
                $wirus = pg_fetch_assoc($res_wir);

                echo '<option value="' . $wirus['nazwa'] . '">' . $wirus['nazwa'] . '</option>';
            }

            pg_close($conn);
            ?>
        </select>

        <br>

        <label for="jednostka_chorobowa">Jednostka chorobowa:</label>
        <input type="text" id="jednostka_chorobowa" name="jednostka_chorobowa" required><br>

        <label for="objawy_ogolne_miejscowe">Objawy ogólne i miejscowe poza j.u.:</label>
        <textarea id="objawy_ogolne_miejscowe" name="objawy_ogolne_miejscowe" required></textarea><br>

        <label for="objawy_miejscowe_ju">Objawy miejscowe w j.u.:</label>
        <textarea id="objawy_miejscowe_ju" name="objawy_miejscowe_ju" required></textarea><br>

        <label for="rozpoznanie">Rozpoznanie:</label>
        <textarea id="rozpoznanie" name="rozpoznanie" required></textarea><br>

        <label for="roznicowanie">Różnicowanie:</label>
        <textarea id="roznicowanie" name="roznicowanie" required></textarea><br>

        <input type="submit" value="Wyślij">
    </form>

</body>

</html>
