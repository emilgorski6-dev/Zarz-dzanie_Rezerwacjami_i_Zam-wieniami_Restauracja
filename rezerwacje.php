<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>RestoManager - Rezerwacje</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <nav class="sidebar">
            <h2>RestoManager</h2>
            <ul>
                <li><a href="index.php" class="active">Strona Główna</a></li>
                <li><a href="mapa.php">Mapa Sali</a></li>
                <li><a href="rezerwacje.php">Rezerwacje</a></li>
                <li><a href="menu.php">Menu / Potrawy</a></li>
                <li><a href="raporty.php">Raporty</a></li>
            </ul>
        </nav>
        
        <?php include 'connect.php'; ?>

        <main class="content">
            <header>
                <h1>Lista Rezerwacji</h1>
                <button class="btn-primary" style="width: auto;">+ Nowa Rezerwacja</button>
            </header>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Data i Godzina</th>
                        <th>Klient</th>
                        <th>Telefon</th>
                        <th>Stolik</th>
                        <th>Osób</th>
                        <th>Status</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // SQL JOIN: Łączymy Rezerwacje z Klientem i Stolikiem zgodnie z ERD
                    $sql = "SELECT r.*, k.imie, k.nazwisko, k.nr_telefonu, s.nr_stolika 
                            FROM Rezerwacje r
                            JOIN Klient k ON r.id_klient = k.id_klient
                            JOIN Stolik s ON r.id_stolik = s.id_stolik
                            ORDER BY r.data_rezerwacji ASC, r.godz_rezerwacji ASC";
                    
                    $result = mysqli_query($con, $sql);

                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Mapowanie statusów na kolory CSS
                            $status_map = [
                                'potwierdzona' => 'wolny',
                                'oczekujaca' => 'zarezerwowany',
                                'odwolana' => 'zajety',
                                'zakonczona' => 'do-sprzatania'
                            ];
                            $class = $status_map[$row['Status_rezerwacji']] ?? '';

                            echo "<tr>";
                            echo "<td>" . date('d.m.Y', strtotime($row['Data_rezerwacji'])) . " " . substr($row['Godz_rezerwacji'], 0, 5) . "</td>";
                            echo "<td>" . htmlspecialchars($row['imie'] . " " . $row['nazwisko']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nr_telefonu']) . "</td>";
                            echo "<td>Stolik #" . htmlspecialchars($row['nr_stolika']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Ilość_osob']) . "</td>";
                            echo "<td><span class='status $class' style='padding: 4px 10px; font-size: 0.75em;'>" . ucfirst($row['Status_rezerwacji']) . "</span></td>";
                            // POPRAWIONA LINIA 70 - używamy pojedynczych cudzysłowów dla atrybutów HTML
                            echo "<td><button class='secondary' style='padding: 5px; font-size: 0.8em;'>Zarządzaj</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>Brak nadchodzących rezerwacji.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>