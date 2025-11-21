<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exerciții PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
            color: #333;
        }
        h2 {
            color: #0066cc;
            border-bottom: 2px solid #ddd;
            padding-bottom: 5px;
        }
        .box {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .result {
            margin-top: 10px;
            padding: 10px;
            background: #f3f7ff;
            border-radius: 5px;
            line-height: 1.6;
        }
        table {
            border-collapse: collapse;
        }
        td {
            width: 40px;
            height: 40px;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Exercițiul 1: Numere pare și impare</h2>
    <div class="result">
        <?php
        echo "<b>Numere pare:</b><br>";
        $i = 1;
        while ($i <= 100) {
            if ($i % 2 == 0) echo $i . " ";
            $i++;
        }

        echo "<br><br><b>Numere impare:</b><br>";
        $i = 1;
        while ($i <= 100) {
            if ($i % 2 != 0) echo $i . " ";
            $i++;
        }
        ?>
    </div>
</div>

<div class="box">
    <h2>Exercițiul 2: Afișarea lui K de N ori</h2>
    <div class="result">
        <?php
        $K = 7; 
        $N = 5; 
        $i = 0;
        while ($i < $N) {
            echo $K . " ";
            $i++;
        }
        ?>
    </div>
</div>

<div class="box">
    <h2>Exercițiul 3: Tabla înmulțirii</h2>
    <div class="result">
        <h1>Tabla Înmulțirii</h1>

<table>
    <?php
    $size = 12;
    echo "<tr><th>x</th>";
    for ($i = 1; $i <= $size; $i++) {
        echo "<th>$i</th>";
    }
    echo "</tr>";

    for ($i = 1; $i <= $size; $i++) {
        echo "<tr>";
        echo "<td><b>$i</b></td>";
        for ($j = 1; $j <= $size; $j++) {
            echo "<td>" . ($i * $j) . "</td>";
        }
        echo "</tr>";
    }
    ?>
</table>
    </div>
</div>

<div class="box">
    <h2>Exercițiul 4: Tabla de șah</h2>
    <div class="result">
        <?php
        echo "<table border='1'>";
        for ($i = 1; $i <= 8; $i++) {
            echo "<tr>";
            for ($j = 1; $j <= 8; $j++) {
                $color = (($i + $j) % 2 == 0) ? "white" : "black";
                echo "<td style='background:$color'></td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        ?>
    </div>
</div>

<div class="box">
    <h2>Exercițiul 5: Calcul vârstă exactă</h2>
    <div class="result">
        <?php
        $birthDate = new DateTime("2006-09-02"); 
        $today = new DateTime();
        $interval = $birthDate->diff($today);
        echo "Ai " . $interval->y . " ani, " . $interval->m . " luni și " . $interval->d . " zile.";
        ?>
    </div>
</div>

<div class="box">
    <h2>Exercițiul 6: Seria 1×9+2, 12×9+3, …</h2>
    <div class="result">
        <?php
        $nr = "";
        for ($i = 1; $i <= 9; $i++) {
            $nr .= $i;
            $rezultat = $nr * 9 + ($i + 1);
            echo $nr . " × 9 + " . ($i + 1) . " = " . $rezultat . "<br>";
        }
        ?>
    </div>
</div>

<div class="box">
    <h2>Exercițiul 7: Piramida de numere</h2>
    <div class="result">
        <?php
        $n = 5; 
        for ($i = 1; $i <= $n; $i++) {
            for ($j = 1; $j <= (2*$i - 1); $j++) {
                echo $j . " ";
            }
            echo "<br>";
        }
        ?>
    </div>
</div>

</body>
</html>
