<!DOCTYPE html>
<html lang="ro">
<head>
<meta charset="UTF-8">
<title>Tabla înmulțirii</title>
<style>
    body {
        background: #0a0; 
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        margin: 20px;
    }
    .container {
        background: white;
        border: 3px solid green;
        padding: 10px;
        position: relative;
    }
    .container h1 {
        text-align: center;
        font-size: 36px;
        margin-bottom: 20px;
    }
    table {
        border-collapse: collapse;
        margin: auto;
    }
    th, td {
        border: 1px solid #000;
        width: 40px;
        height: 40px;
        text-align: center;
        font-size: 16px;
    }
    th {
        background: #f88;
        font-weight: bold;
    }
    td:first-child, tr:first-child td {
        background: #f88;
        font-weight: bold;
    }
    .apple {
        position: absolute;
        top: -10px;
        left: -80px;
        width: 70px;
    }
    .books {
        position: absolute;
        top: 60px;
        left: -90px;
        width: 80px;
    }
</style>
</head>
<body>
<div class="container">
    <img src="mar.png" class="apple" alt="mar">
    <img src="books.png" class="books" alt="carti">

    <h1>Tabla înmulțirii</h1>
    <table>
        <tr>
            <th>x</th>
            <?php for ($i = 0; $i <= 12; $i++): ?>
                <th><?= $i ?></th>
            <?php endfor; ?>
        </tr>
        <?php for ($i = 0; $i <= 12; $i++): ?>
            <tr>
                <td><b><?= $i ?></b></td>
                <?php for ($j = 0; $j <= 12; $j++): ?>
                    <td><?= $i * $j ?></td>
                <?php endfor; ?>
            </tr>
        <?php endfor; ?>
    </table>
</div>
</body>
</html>
