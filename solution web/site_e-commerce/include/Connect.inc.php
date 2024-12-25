<?php
try{
 $user = 'R2024MYSAE3002';
 $pass = 'ST47twn53Bp9zM';
 $conn = new PDO('mysql:host=localhost;dbname=R2024MYSAE3002;charset=UTF8'  
				,$user, $pass, array(PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION));
}
catch (PDOException $e){
  echo "Erreur: ".$e->getMessage()."<br>";
  die() ;
}
?>