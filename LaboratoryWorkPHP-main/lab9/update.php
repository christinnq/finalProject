<?php
 include ("conexiune.php");
 $result = mysqli_query($conexiune, "SELECT * FROM elevi");
  
 while($row = mysqli_fetch_assoc($result)) {
  
 ?>
 <form action="updated.php" method="post">
  
 <input type="hidden" name="ud_id" value="<?php echo $row['id'];?>">
 Nume: <input type="text" name="ud_nume" value="<?php echo $row['nume'];?>">
 Prenume: <input type="text" name="ud_prenume" value="<?php echo $row['prenume'];?>">
 Email: <input type="text" name="ud_email" value="<?php echo $row['email'];?>">
 <input type="Submit" value="Modifica">
 </form>
 <?php
 }
 ?>