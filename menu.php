<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>RestoManager - Menu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <nav class="sidebar">
            <h2>RestoManager</h2>
            <ul>
                <li><a href="mapa.php">Mapa Sali</a></li>
                <li><a href="rezerwacje.php">Rezerwacje</a></li>
                <li><a href="menu.php" class="active">Menu / Potrawy</a></li>
                <li><a href="raporty.php">Raporty</a></li>
            </ul>
        </nav>
        
        <?php include 'connect.php'; ?>

        <main class="content">
            <header>
                <h1>Karta Dań</h1>
            </header>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nazwa</th>
                        <th>Kategoria</th>
                        <th>Cena</th>
                        <th>Dieta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Korzystamy z połączenia $con z pliku connect.php
                    // Pobieramy dane z tabeli Potrawa zgodnie z Twoim schematem
                    $sql = "SELECT nazwa, kategoria, cena_aktualna, czy_wege FROM Potrawa ORDER BY kategoria";
                    $result = mysqli_query($con, $sql);

                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Logika dla potraw wegetariańskich na podstawie kolumny czy_wege
                            $wege = $row['czy_wege'] ? '<span class="status wolny" style="padding: 2px 10px; font-size: 0.7em;">Wege</span>' : '';
                            
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['nazwa']) . "</td>
                                    <td>" . htmlspecialchars($row['kategoria']) . "</td>
                                    <td>" . number_format($row['cena_aktualna'], 2, ',', ' ') . " zł</td>
                                    <td>$wege</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Błąd pobierania menu: " . mysqli_error($con) . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>