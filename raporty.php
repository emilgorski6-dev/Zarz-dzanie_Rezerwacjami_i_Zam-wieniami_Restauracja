<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>RestoManager - Raporty</title>
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
                <h1>Raporty i Sprzedaż</h1>
            </header>

            <?php
            // Pobieranie statystyk ogólnych z tabeli Rachunek
            $sql_stats = "SELECT SUM(Wartość_calkowita) as total_revenue, COUNT(id_rachunek) as total_orders FROM Rachunek WHERE Status_rachunku = 'oplacony'";
            $res_stats = mysqli_query($con, $sql_stats);
            $stats = mysqli_fetch_assoc($res_stats);
            
            $total_revenue = $stats['total_revenue'] ?? 0;
            $total_orders = $stats['total_orders'] ?? 0;
            ?>

            <div class="stats-cards">
                <div class="stat-card">
                    <h3>Całkowity Utarg</h3>
                    <p><?php echo number_format($total_revenue, 2, ',', ' '); ?> zł</p>
                </div>
                <div class="stat-card">
                    <h3>Zrealizowane Zamówienia</h3>
                    <p><?php echo $total_orders; ?></p>
                </div>
            </div>

            <h2>Historia Rachunków</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data utworzenia</th>
                        <th>Wartość całkowita</th>
                        <th>Napiwek</th>
                        <th>Płatność</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Łączymy Rachunek z tabelą Platnosc, aby pobrać rodzaj płatności
                    $sql_list = "SELECT r.*, p.rodzaj_platnosci 
                                 FROM Rachunek r 
                                 LEFT JOIN Platnosc p ON r.id_platnosci = p.id_platnosc 
                                 ORDER BY r.czas_utworzenia DESC";
                    $result = mysqli_query($con, $sql_list);

                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $status_class = ($row['Status_rachunku'] == 'oplacony') ? 'wolny' : 'zajety';
                            
                            echo "<tr>
                                    <td>#{$row['id_rachunek']}</td>
                                    <td>" . date('d.m.Y H:i', strtotime($row['Czas_utworzenia'])) . "</td>
                                    <td>" . number_format($row['Wartość_calkowita'], 2, ',', ' ') . " zł</td>
                                    <td>" . number_format($row['Napiwek'], 2, ',', ' ') . " zł</td>
                                    <td>" . htmlspecialchars($row['rodzaj_platnosci'] ?? '---') . "</td>
                                    <td><span class='status $status_class' style='padding: 4px 10px; font-size: 0.75em;'>" . ucfirst($row['Status_rachunku']) . "</span></td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>Brak zarejestrowanych rachunków.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>