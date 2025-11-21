<?php
 include ("conexiune.php");
 $ud_id=$_POST['ud_id'];
 $ud_nume=$_POST['ud_nume'];
 $ud_prenume=$_POST['ud_prenume'];
 $ud_email=$_POST['ud_email'];
 $query="UPDATE elevi SET nume='$ud_nume', prenume='$ud_prenume', email='$ud_email' WHERE id='$ud_id'";
 $checkresult = mysqli_query($conexiune, $query);
 if ($checkresult) {
 echo "Modificare efectuata";
 } else {
 echo "Modificare neefectuata";
 }
 mysqli_close($conexiune);
 ?>