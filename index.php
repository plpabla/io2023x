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

    <a href="dodaj_chorobe.php">Link do dodania nowej choroby</a>
    <br><br>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Wirus.nazwa - to bedzie link</th>
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
      
        // Tu wykonujesz połączenie z bazą danych PostgreSQL i wykonujesz zapytanie do tabeli "choroba" aby pobrać dane
        // Przykładowy kod PHP dla takiego połączenia i zapytania:
        $conn = pg_connect(get_conn_string());
        $query = "SELECT id, id_wirus, choroba, objawy_ogolne, objawy_ju, rozpoznanie, roznicowanie FROM choroba";
        $result = pg_query($conn, $query);
       
        // Iterujesz przez wyniki zapytania i generujesz wiersze tabeli HTML
        while ($row = pg_fetch_assoc($result)) {
          // Dla każdego wiersza pobierz też dane wirusa, które chcesz wyświetlić
          $id_wirus = $row['id_wirus'];
          $query2 = "SELECT nazwa FROM wirus WHERE id=${id_wirus}";
          $res_wir = pg_fetch_row(pg_query($conn, $query2));

          echo "<tr>";
          echo "<td>" . $row['id'] . "</td>";
          echo "<td>" . $res_wir[0] . "</td>";
          echo "<td>" . $row['choroba'] . "</td>";
          echo "<td>" . $row['objawy_ogolne'] . "</td>";
          echo "<td>" . $row['objawy_ju'] . "</td>";
          echo "<td>" . $row['rozpoznanie'] . "</td>";
          echo "<td>" . $row['roznicowanie'] . "</td>";
          echo "<td> link </td>";
          echo "<td> link </td>";
          echo "</tr>";
        }
        
        // Zamykasz połączenie
        pg_close($conn);
      
      ?>
    </tbody>
  </table>
</body>
</html>
