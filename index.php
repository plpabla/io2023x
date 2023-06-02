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
    <h1>Jeden wirus może powodować wiele chorób</h1>
    <h2>Choroba może być powodowana tylko przez jednego wirusa!!</h2>
    <img src="doc/wirusy.png" width=500/><br>
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
    <a href="wirusy.php">Link do wirusów</a>
    <br><br>
  <?php
      
    // Połączenie z bazą danych PostgreSQL
    $conn = pg_connect(get_conn_string());

    // Pobranie danych z tabeli choroba, wraz z nazwą wirusa
    /* nieskuteczna próba powiązania tabel
    $query = "SELECT c.id, c.choroba, w.nazwa, c.objawy_ogolne, c.objawy_ju, c.rozpoznanie, c.roznicowanie
              FROM choroba c
              JOIN wirus w ON c.id_wirus = w.id";
              */
 //   $query = "SELECT id, id_wirus, choroba, objawy_ogolne, objawy_ju, rozpoznanie, roznicowanie FROM choroba ORDER BY id";
            $query = "SELECT c.id, c.choroba, w.nazwa AS nazwa_wirusa, c.objawy_ogolne, c.objawy_ju, c.rozpoznanie, c.roznicowanie
            FROM choroba c
            JOIN wirus w ON c.id_wirus = w.id
            ORDER BY c.id";

    $result = pg_query($conn, $query);
              
    // Sprawdzenie, czy są dostępne dane
    if (pg_num_rows($result) > 0) {
      echo '<table>
              <thead>
                <tr>
                  <th>Lp.</th>
                  <th>Jednostka chorobowa</th>
                  <th>Czynnik etiologiczny</th>
                  <th>Objawy ogólne lub miejscowe poza jamą ustną</th>
                  <th>Objawy miejscowe w jamie ustnej</th>
                  <th>Rozpoznanie</th>
                  <th>Różnicowanie</th>
                  <th>Edytuj</th>
                  <th>Usuń</th>
                </tr>
              </thead>
              <tbody>';

              $lp = 1; // Zmienna licznikowa

      // Iterujesz przez wyniki zapytania i generujesz wiersze tabeli HTML
      while ($row = pg_fetch_assoc($result)) {
        echo "<tr>";
//        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $lp . "</td>";
        echo "<td>" . $row['choroba'] . "</td>";
//        echo "<td>" . $row['nazwa_wirusa'] . "</td>";
        echo "<td><a href='wirusy.php?nazwa=" . urlencode($row['nazwa_wirusa']) . "'>" . $row['nazwa_wirusa'] . "</a></td>";
//        echo "<td>" . $wirusy[$row['id_wirus']]['nazwa'] . "</td>";
        echo "<td>" . $row['objawy_ogolne'] . "</td>";
        echo "<td>" . $row['objawy_ju'] . "</td>";
        echo "<td>" . $row['rozpoznanie'] . "</td>";
        echo "<td>" . $row['roznicowanie'] . "</td>";
        echo "<td><a href='edytuj_chorobe.php?id={$row['id']}'>Edytuj</a></td>";
        echo "<td><a href='usun_chorobe.php?id={$row['id']}'>Usuń</a></td>";
        echo "</tr>";
        
              $lp++; // Inkrementacja zmiennej licznikowej
      }

        echo '</tbody>
            </table>';
    } else {
      echo "Brak dostępnych danych.";
    }

    // Zamknięcie połączenia z bazą danych
    pg_close($conn);
  ?>
</body>
</html>

