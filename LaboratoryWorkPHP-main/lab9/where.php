<?php
include ("conexiune.php");
$nume1=$_POST['nume1'];
$sql=mysqli_query($conexiune, "SELECT * FROM `elevi` WHERE nume LIKE '%$nume1%'");
echo "<table border=1>";
echo "<tr><td>ID</td><td>Nume</td><td>Prenume</td></tr>";
while ($row=mysqli_fetch_row($sql)) {
echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td></tr>";
 }
 echo "</table>";
 mysqli_close($conexiune);
 ?>