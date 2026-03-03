<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>RestoManager - Mapa Sali</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <nav class="sidebar">
    <h2>RestoManager</h2>
    <ul>
        <li><a href="index.php">Strona Główna</a></li>
        <li><a href="mapa.php" class="active">Mapa Sali</a></li>
        <li><a href="rezerwacje.php">Rezerwacje</a></li>
        <li><a href="menu.php">Menu / Potrawy</a></li>
        <li><a href="raporty.php">Raporty</a></li>
    </ul>
</nav>
        <main class="content">
            <header><h1>Mapa Stolików</h1></header>
            <div class="legend">
                <span class="status wolny">Wolny</span>
                <span class="status zajety">Zajęty</span>
                <span class="status zarezerwowany">Rezerwacja</span>
                <span class="status do-sprzatania">Do sprzątania</span>
            </div>
            
            <?php include 'connect.php'; ?>
            
            <section class="tables-grid">
                <?php
                // POBIERANIE DANYCH O STOLIKACH - styl mysqli
                $sql = "SELECT id_stolik, nr_stolika, na_ile_osob, sala, status_stolika FROM Stolik";
                $result = mysqli_query($con, $sql);

                // Sprawdzamy czy zapytanie się udało
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Klasa CSS zależy od status_stolika z bazy zgodnie z ERD
                        echo '<div class="table-card ' . htmlspecialchars($row['status_stolika']) . '">';
                        echo '<h3>Stolik #' . htmlspecialchars($row['nr_stolika']) . '</h3>';
                        echo '<p>Miejsc: ' . htmlspecialchars($row['na_ile_osob']) . ' | Sala: ' . htmlspecialchars($row['sala']) . '</p>';
                        echo '<span class="badge">' . ucfirst(htmlspecialchars($row['status_stolika'])) . '</span>';
                        
                        // Logika przycisku dla wolnych stolików
                        if($row['status_stolika'] == 'wolny') {
                            echo '<button onclick="openOrder(' . $row['id_stolik'] . ')">Otwórz Rachunek</button>';
                        } else {
                            echo '<button class="secondary">Szczegóły</button>';
                        }
                        echo '</div>';
                    }
                } else {
                    echo "Błąd zapytania: " . mysqli_error($con);
                }
                ?>
            </section>
        </main>
    </div>
</body>
</html>