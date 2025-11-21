<?php
 include ("conexiune.php");
 $id=$_POST['id'];
 $sql=mysqli_query($conexiune, "DELETE FROM elevi WHERE id='$id'");
 if (!$sql) {
 die(mysqli_error());
 } else {
 echo "Datele au fost șterse";
 }
 mysqli_close($conexiune);
 ?>