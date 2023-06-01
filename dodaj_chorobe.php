<!DOCTYPE html>
<html>
<head>
  <title>Dodaj Chorobę</title>
</head>

<body>
  <h1>Dodaj Nową Chorobę</h1>

  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    // Pobranie danych z formularza
    $jednostka_chorobowa = $_POST['jednostka_chorobowa'];
    $id_wirus = $_POST['id_wirus'];
    $objawy_ogolne_miejscowe = $_POST['objawy_ogolne_miejscowe'];
    $objawy_miejscowe_ju = $_POST['objawy_miejscowe_ju'];
    $rozpoznanie = $_POST['rozpoznanie'];
    $roznicowanie = $_POST['roznicowanie'];

    // Wstawienie nowego rekordu do tabeli choroba
    $query = "INSERT INTO choroba (jednostka_chorobowa, id_wirus, objawy_ogolne_miejscowe, objawy_miejscowe_ju, rozpoznanie, roznicowanie)
              VALUES ('$jednostka_chorobowa', $id_wirus, '$objawy_ogolne_miejscowe', '$objawy_miejscowe_ju', '$rozpoznanie', '$roznicowanie')";
    $result = pg_query($conn, $query);

    if ($result) {
      echo "Dodano nową chorobę.";
    } else {
      echo "Wystąpił błąd podczas dodawania choroby.";
    }

    // Zamknięcie połączenia z bazą danych
    pg_close($conn);
  }
  ?>

  <form method="POST">
    <label for="jednostka_chorobowa">Jednostka chorobowa:</label><br>
    <input type="text" id="jednostka_chorobowa" name="jednostka_chorobowa" required><br><br>
<!--3xten
    <label for="id_wirus">ID wirusa:</label><br>
    <select id="id_wirus" name="id_wirus" required>
      <option value="">Wybierz wirusa</option>
      <option value="1">Wirus A</option>
      <option value="2">Wirus B</option>
      <option value="3">Wirus C</option>
      <!-- Dodaj tutaj pozostałe opcje dla innych wirusów -->
 <!--ten   </select><br><br>
ten-->
    <label for="wirus">Wybierz wirusa:</label>
        <select id="wirus" name="wirus">
            <?php
            foreach ($wirusy as $wirus) {
                echo "<option value='" . $wirus['id'] . "'>" . $wirus['nazwa'] . "</option>";
            }
            ?>
        </select>
        <br><br>


    <label for="objawy_ogolne_miejscowe">Objawy ogólne lub miejscowe poza jamą ustną:</label><br>
    <textarea id="objawy_ogolne_miejscowe" name="objawy_ogolne_miejscowe" required></textarea><br><br>

    <label for="objawy_miejscowe_ju">Objawy miejscowe w jamie ustnej:</label><br>
    <textarea id="objawy_miejscowe_ju" name="objawy_miejscowe_ju" required></textarea><br><br>

    <label for="rozpoznanie">Rozpoznanie:</label><br>
    <textarea id="rozpoznanie" name="rozpoznanie" required></textarea><br><br>

    <label for="roznicowanie">Roznicowanie:</label><br>
    <textarea id="roznicowanie" name="roznicowanie" required></textarea><br><br>

    <input type="submit" value="Dodaj">
  </form>
</body>
</html>
