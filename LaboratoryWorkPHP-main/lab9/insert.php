<?php
include ("conexiune.php");
$nume=$_POST['nume'];
$prenume=$_POST['prenume'];
$email=$_POST['email'];
$telefon=$_POST['telefon'];
$query="INSERT INTO `elevi` (nume, prenume, email, telefon) VALUES ('$nume','$prenume','$email', '$telefon')";
if (!mysqli_query($conexiune, $query)) {
die(mysqli_error($conexiune));
} else {
echo "datele au fost introduse";
}
mysqli_close($conexiune);
?>