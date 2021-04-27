<?php
 //Conectare la baza de date 
 include("conexiune.php");

 //Se verifica daca id-ul produsului a fost primit 
 if (isset($_GET['id']) && is_numeric($_GET['id']))
 {
	// preluam variabila 'id' din URL
	$id = $_GET['id'];

	// stergem inregistrarea cu id-ul produsului 
	if ($stmt = $link->prepare("DELETE FROM produse WHERE id = ? LIMIT 1"))
	{
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->close();
	}
	else{
		echo"Inregistrarea nu a putut fi stearsa";
	}
 
	$link->close();
	header("location: manipulareprod.php");
 }
 ?>