<?php
include ("conexiune.php");
$sql=mysqli_query($conexiune, "SELECT * FROM `elevi`");
echo "<table border=1>";
echo "<tr><td>Id</td><td>Nume</td><td>Prenume</td><td>Email</td><td>Telefon</td></tr>";
while ($row=mysqli_fetch_row($sql)) {
echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td></tr>";
}
echo "</table>";
mysqli_close($conexiune);
?>