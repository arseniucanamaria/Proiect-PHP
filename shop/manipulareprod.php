<?php
//Includem fisierul pentru conectare la baza de date
include("conexiune.php");

if(isset($_POST['submit']))
{
	//Preluam datele din formular
	$nume=htmlentities($_POST['nume'],ENT_QUOTES);
	$pret=htmlentities($_POST['pret'],ENT_QUOTES);
	$descriere=htmlentities($_POST['descriere'],ENT_QUOTES);

	//verificam daca datele sunt completate
	if($nume=='' || $pret=='' || $descriere=='')
	{
		//Daca nu sunt completate toate datele spunem adminului ca trebuie completate toate campurile
		$error='Va rugam sa completati toate campurile!';
	}
	else
	{
		//Se realizeaza inseararea datelor in tabela produse
		if($stmt=$link->prepare("INSERT INTO produse (nume, pret, descriere) VALUES (?,?,?)"))
		{
		
			$stmt->bind_param("sss",$nume,$pret,$descriere);
			$stmt->execute();
			$stmt->close();
		}
	
		//eroare la inserare
		else
		{
			echo"<br>";
			echo"Inserarea datelor nu a putut fi realizata.";
		}
    }
}

 //Se preiau produsele din baza de date
 if ($result = $link->query("SELECT * FROM produse ORDER BY id")){ 
	// Afisare inregistrari pe ecran
	if ($result->num_rows > 0)
	{
	 ?> 
		<p style="color:green; font-size:150%">Produsele disponibile din magazin</p>
		
		<table border=1 class='tabelprod'>
		<tr style="color:maroon"><th>ID</th><th>Nume produs</th><th>Pret produs (lei)</th><th>Descriere</th><th>Sterge</th></tr>
		<?PHP
		while ($row = $result->fetch_object())
		{
			//Definirea unei noi linii pentru fiecare inregistrare
			echo "<tr>";
			echo "<td>" . $row->id . "</td>";
			echo "<td>" . $row->nume . "</td>";
			echo "<td>" . $row->pret . "</td>";
			echo "<td>" . $row->descriere . "</td>";
			echo "<td><a href='stergereprod.php?id=" . $row->id ."'>Sterge Produsul</a></td>";
			echo "</tr>";
		}

		echo "</table>";
	}
 
	//Daca tabelul nu contine inregistrari afisam o eroare
	else{
		echo "<span class='nuinreg'>Nu sunt inregistrari in tabelul produse!</span>";
	}
 }
 
	// eroare in caz de insucces in interogare
	else{
		echo "Error: " . $link->error(); 
	}

			$link->close();
	?>
	
	
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Manipulare produse</title>
	<link rel="stylesheet" href="/shop/bootstrap.min.css">
</head>
<body>
	<?php if(isset($error)){echo"<div style='padding:4px; border:2px solid red;background-color: red; color: white;'>".$error."</div>"."<br/>";}?> <br>
 	<center> <h3 style="color:red" class="whitetype">Introducere produse in baza de date</h3>
	<form action="" method="post">
		<label class="labelform">Nume</label>
		<input type="text" name="nume" value=""><br>
		<label class="labelform">Pret produs</label>
		<input type="text" name="pret" value=""><br>
		<label class="labelform">Descriere produs</label>
		<input type="text" name="descriere" value=""><br>
		<input type="submit" name="submit" value="Adauga" class="butonform">
	</form>
		<a href="index.php" id="mprd"><h3 id="mergiprod">Mergi la pagina principala</h3><a></center>
</body>
</html>