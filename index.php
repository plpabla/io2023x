<!DOCTYPE html>
<html>
<head>
  <title>Tabela Choroba</title>
  <style>
    table {
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid black;
      padding: 8px;
    }
  </style>
</head>

<body>
    To się wysypuje? <br>
    <?php
    $ini = parse_ini_file("php.ini");
    $host = $ini["dbhost"];
    $db = $ini["dbname"];
    $usr = $ini["dbuser"];
    $pass = $ini["dbpass"];
    echo "ustawienia <br>";
    $conn = pg_connect("host=$host port=5432 dbname=$db user=$usr password=$pass");
    if($conn)
    {
      echo "connected <br>";
    } else
    {
      echo "not connected :( <br>";
    };

    $query = "SELECT * FROM choroba;";
    echo "zapytanie $query <br>";
    $res = pg_query($conn, $query);

    $row = pg_fetch_assoc($res);
    print_r($row);
    echo "<br>";
    ?>

    <a href="dodaj_chorobe.php">Link do dodania nowej choroby</a>
    <br>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>ID Wirus</th>
        <th>Choroba</th>
        <th>Objawy Ogólne</th>
        <th>Objawy JU</th>
        <th>Rozpoznanie</th>
        <th>Roznicowanie</th>
        <th>Edytuj</th>
        <th>Usuń</th>
      </tr>
    </thead>
    <tbody>
      <?php
      /*
        // Tu wykonujesz połączenie z bazą danych PostgreSQL i wykonujesz zapytanie do tabeli "choroba" aby pobrać dane
        // Przykładowy kod PHP dla takiego połączenia i zapytania:
        $conn = pg_connect("host=adres_hosta dbname=nazwa_bazy_danych user=uzytkownik password=haslo");
        $query = "SELECT id, id_wirus, choroba, objawy_ogolne, objawy_ju, rozpoznanie, roznicowanie FROM choroba";
        $result = pg_query($conn, $query);
        
        // Iterujesz przez wyniki zapytania i generujesz wiersze tabeli HTML
        while ($row = pg_fetch_assoc($result)) {
          echo "<tr>";
          echo "<td>" . $row['id'] . "</td>";
          echo "<td>" . $row['id_wirus'] . "</td>";
          echo "<td>" . $row['choroba'] . "</td>";
          echo "<td>" . $row['objawy_ogolne'] . "</td>";
          echo "<td>" . $row['objawy_ju'] . "</td>";
          echo "<td>" . $row['rozpoznanie'] . "</td>";
          echo "<td>" . $row['roznicowanie'] . "</td>";
          echo "</tr>";
        }
        
        // Zamykasz połączenie
        pg_close($conn);
      */
      ?>
    </tbody>
  </table>
</body>
</html>
