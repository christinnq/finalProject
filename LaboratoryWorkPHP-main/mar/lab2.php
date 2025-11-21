<!DOCTYPE html>
<html lang="ro">
<head>
<meta charset="UTF-8">
<title>Tabla înmulțirii</title>
<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: white;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 30px;
    }
    .wrapper {
        background: white;
        border-left: 250px solid #1fa41f;   
        border-top: 10px solid #1fa41f;   
        border-right: 10px solid #1fa41f; 
        border-bottom: 10px solid #1fa41f;
        padding: 20px 40px;
        position: relative;
    }
    h1 {
        text-align: center;
        font-size: 36px;
        font-weight: bold;
        margin: 0 0 20px 0;
    }
    table {
        border-collapse: collapse;
        margin: auto;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }
    th, td {
        border: 1px solid #000;
        width: 45px;
        height: 45px;
        text-align: center;
        font-size: 18px;
    }
    th {
        background: #f08080;
        font-weight: bold;
    }
    td:first-child, tr:first-child td {
        background: #f08080;
        font-weight: bold;
    }
    td {
        background: #fff;
    }
    .apple {
        position: absolute;
        top: -5px;
        left: -70px;
        width: 120px;
    }
    .books {
        position: absolute;
        top: 250px;
        left: -350px;
        width: 500px;
    }
</style>
</head>
<body>
<div class="wrapper">
    <img src="mar.png" class="apple" alt="mar">
    <img src="books.png" class="books" alt="carti">

    <h1>Tabla înmulțirii</h1>
    <table>
        <tr>
            <td><b>x</b></td>
            <?php for ($i = 0; $i <= 12; $i++): ?>
                <td><b><?= $i ?></b></td>
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
