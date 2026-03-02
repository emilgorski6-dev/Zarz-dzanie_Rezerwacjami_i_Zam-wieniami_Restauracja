<?php 
// Wczytujemy Twoje połączenie mysqli_connect
include 'connect.php'; 
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RestoManager - Panel Obsługi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <nav class="sidebar">
            <h2>RestoManager</h2>
            <ul>
                <li><a href="index.php" class="active">Mapa Sali</a></li>
                <li><a href="rezerwacje.php">Rezerwacje</a></li>
                <li><a href="menu.php">Menu / Potrawy</a></li>
                <li><a href="raporty.php">Raporty</a></li>
            </ul>
        </nav>

        <main class="content">
            <header>
                <h1>Podgląd Stolików</h1>
                <div class="user-info">Pracownik: Jan Kowalski (Kelner)</div>
            </header>

            <div class="legend">
                <span class="status wolny">Wolny</span>
                <span class="status zajety">Zajęty</span>
                <span class="status zarezerwowany">Rezerwacja</span>
                <span class="status do-sprzatania">Do sprzątania</span>
            </div>

            <section class="tables-grid">
                <?php
                // Pobieranie danych o stolikach zgodnie ze schematem
                $sql = "SELECT id_stolik, nr_stolika, na_ile_osob, sala, status_stolika FROM stolik";
                $result = mysqli_query($con, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        // Tworzymy kartę stolika. Klasa CSS zależy od 'status_stolika'
                        echo '<div class="table-card ' . htmlspecialchars($row['status_stolika']) . '">';
                        echo '<h3>Stolik #' . htmlspecialchars($row['nr_stolika']) . '</h3>';
                        echo '<p>Miejsc: ' . htmlspecialchars($row['na_ile_osob']) . ' | Sala: ' . htmlspecialchars($row['sala']) . '</p>';
                        
                        // Przycisk zmieniający działanie w zależności od statusu
                        if($row['status_stolika'] == 'wolny') {
                            echo '<button onclick="location.href=\'zamowienie.php?stolik=' . $row['id_stolik'] . '\'">Otwórz Rachunek</button>';
                        } else {
                            echo '<button class="secondary">Szczegóły</button>';
                        }
                        echo '</div>';
                    }
                } else {
                    echo "<p>Brak stolików w bazie danych.</p>";
                }
                ?>
            </section>
        </main>
    </div>
</body>
</html>