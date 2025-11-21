<?php
 include ("conexiune.php");
 $nume=$_POST['nume'];
 $sql=mysqli_query($conexiune, "SELECT * FROM `elevi` WHERE nume LIKE '%$nume%'");
 echo "<table border=\"1\">";
 echo "<tr><td>ID</td><td>Nume</td><td>Prenume</td></tr>";
 while ($row=mysqli_fetch_row($sql)) {
 echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td></tr>";
 }
 echo "</table>";
 mysqli_close($conexiune);
 ?>
 <p>
 <form method="POST" action="deleted.php">
 ID-ul inregistrarii ce va fi stearsă: <input type="text" name="id" size="3"><br>
 <input type="submit" value="Trimite">
 </form>