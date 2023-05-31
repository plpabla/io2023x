<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Dodawanie jednostki chorobowej</title>
</head>

<body>
<!--
<?php
// Dane do połączenia z bazą danych
$host = "dbhost";
$dbname = "dbname";
$username = "dbuser";
$password = "dbpass";

// Tworzenie połączenia z bazą danych
$pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);

// Pobieranie danych dla comboboxa
$query = "SELECT id, nazwa FROM wirus";
$statement = $pdo->prepare($query);
$statement->execute();
$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
-->
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
// 
    <select>
        <?php foreach ($rows as $row): ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['nazwa']; ?></option>
        <?php endforeach; ?>
    </select>
    <form action="adres_do_przetwarzania_danych.php" method="post">
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