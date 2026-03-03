<?php include 'connect.php'; ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>RestoManager - Pulpit</title>
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

        <main class="content">
            <header>
                <h1>Pulpit Dnia</h1>
                <div class="user-info">Pracownik: Jan Kowalski (Kelner)</div>
            </header>

            <?php
            // 1. Statystyki ogólne
            $today = date('Y-m-d');
            
            // Dzisiejszy utarg (z opłaconych rachunków)
            $sql_rev = "SELECT SUM(Wartość_calkowita) as suma FROM Rachunek WHERE DATE(Czas_utworzenia) = '$today' AND Status_rachunku = 'oplacony'";
            $res_rev = mysqli_query($con, $sql_rev);
            $rev = mysqli_fetch_assoc($res_rev)['suma'] ?? 0;

            // Liczba dzisiejszych rezerwacji
            $sql_rez = "SELECT COUNT(*) as ile FROM Rezerwacje WHERE Data_rezerwacji = '$today' AND Status_rezerwacji != 'odwolana'";
            $res_rez = mysqli_query($con, $sql_rez);
            $rez_count = mysqli_fetch_assoc($res_rez)['ile'] ?? 0;

            // Stoliki do sprzątania
            $sql_clean = "SELECT COUNT(*) as ile FROM stolik WHERE Status_stolika = 'do-sprzatania'";
            $res_clean = mysqli_query($con, $sql_clean);
            $clean_count = mysqli_fetch_assoc($res_clean)['ile'] ?? 0;
            ?>

            <section class="stats-cards">
                <div class="stat-card">
                    <h3>Dzisiejszy Utarg</h3>
                    <p><?php echo number_format($rev, 2, ',', ' '); ?> zł</p>
                </div>
                <div class="stat-card">
                    <h3>Rezerwacje na dziś</h3>
                    <p><?php echo $rez_count; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Stoliki do sprzątania</h3>
                    <p style="color: var(--info);"><?php echo $clean_count; ?></p>
                </div>
            </section>

            <section class="dashboard-lists" style="display: flex; gap: 20px; margin-top: 30px;">
                <div class="data-table-container" style="flex: 2;">
                    <h2>Nadchodzące rezerwacje</h2>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Godzina</th>
                                <th>Klient</th>
                                <th>Stolik</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_list = "SELECT r.Godz_rezerwacji, k.Nazwisko, s.nr_stolika 
                                         FROM Rezerwacje r 
                                         JOIN Klient k ON r.Id_klient = k.Id_klient 
                                         JOIN stolik s ON r.Id_stolik = s.Id_stolik 
                                         WHERE r.Data_rezerwacji = '$today' AND r.Status_rezerwacji = 'potwierdzona'
                                         ORDER BY r.Godz_rezerwacji ASC LIMIT 5";
                            $res_list = mysqli_query($con, $sql_list);
                            while($row = mysqli_fetch_assoc($res_list)) {
                                echo "<tr>
                                        <td>".substr($row['Godz_rezerwacji'], 0, 5)."</td>
                                        <td>".htmlspecialchars($row['Nazwisko'])."</td>
                                        <td>#".$row['nr_stolika']."</td>
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="stat-card" style="flex: 1;">
                    <h2>Zajętość sali</h2>
                    <canvas id="occupancyChart" style="max-height: 200px;"></canvas>
                    <p style="font-size: 0.9em; color: var(--text-muted); margin-top: 10px;">
                        Przejdź do <strong>Mapy Sali</strong>, aby zarządzać.
                    </p>
                </div>
            </section>
        </main>
    </div>
</body>
</html>