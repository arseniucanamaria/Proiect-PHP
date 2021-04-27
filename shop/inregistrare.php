<?php
//Includem fisierul pentru  baza de date
require_once "conexiune.php";

//Definim variabilele si le initializam cu void
$username = $password = $confirm_password = $nume = $prenume = $email = $tara = $judet = $oras = $adresa = "";
$username_err = $password_err = $confirm_password_err = $nume_err = $prenume_err = $email_err = $tara_err = $judet_err = $oras_err = $adresa_err = "";

//Procesarea datelor din formular in momentul in care acesta este trimis
if($_SERVER["REQUEST_METHOD"] == "POST"){
//Validare Username
	if(empty(trim($_POST["username"]))){
		$username_err = "Introduceti username-ul.";
	}
	else
	{
		// SE verifica daca username-ul a mai fost folosit o data
		$sql = "SELECT id FROM utilizatori WHERE username = ?";
		
		if($stmt = $link->prepare($sql)){
			//Legam variabilele de selectul pregatit anterior ca parametrii
			$stmt->bind_param("s", $param_username);
			
			//Setam parametrii
			$param_username = trim($_POST["username"]);
			
			//Incercam sa executam statement-ul pregatit anterior
			if($stmt->execute()){
				//Retinem rezultatul 
				$stmt->store_result();
				
				if($stmt->num_rows >= 1){
					$username_err = "Acest username exista deja.";
				}
				else{
					$username = trim($_POST["username"]);
				}
			}
			else{
				echo "Oops! Something went wrong. Please try again later.";
			}
		}
		//Inchidem statement-ul
		$stmt->close();
	}
//Validare parola
	//nu s-a scris nimic in casuta alocata parolei
	if(empty(trim($_POST["password"]))){ 
		$password_err = "Introduceti parola.";
	}elseif(strlen(trim($_POST["password"])) < 6){
		$password_err = "Parola trebuie sa aiba minim 6 caractere.";
	}else{
		//parola este in regula si se trimite catre baza de date
		$password = trim($_POST["password"]);
	}
	
	
//Validare camp de confirmare a parolei
	if(empty(trim($_POST["confirm_password"]))){
		$confirm_password_err = "Confirmati parola.";
	}else{
		$confirm_password = trim($_POST["confirm_password"]);
		if(empty($password_err) && ($password != $confirm_password)){
			$confirm_password_err = "Parolele nu se potrivesc.";
		}
	}
	
	
//Validare nume 
	if(empty(trim($_POST["nume"]))){
		$nume_err = "Introduceti numele.";
	}else{
		$nume = trim($_POST["nume"]);
	}
	
	
//Validare prenume
	if(empty(trim($_POST["prenume"]))){
		$nume_err = "Introduceti prenumele.";
	}else{
		$prenume = trim($_POST["prenume"]);
	}
	
	
//Validare email 
	if(empty(trim($_POST["email"]))){
		$email_err = "Introduceti adresa de email.";
	}else{
		$email = trim($_POST["email"]);
	}
	
	
//Validare tara
	if(empty(trim($_POST["tara"]))){
		$tara_err = "Introduceti tara.";
	}else{
		$tara = trim($_POST["tara"]);
	}
	
	
//Validare judet
	if(empty(trim($_POST["judet"]))){
		$judet_err = "Introduceti judetul.";
	}else{
		$judet = trim($_POST["judet"]);
	}
	
	
//Validare oras
	if(empty(trim($_POST["oras"]))){
		$oras_err = "Introduceti orasul.";
	}else{
		$oras = trim($_POST["oras"]);
	}
	
	
//Validare adresa
	if(empty(trim($_POST["adresa"]))){
		$adresa_err = "Introduceti adresa.";
	}else{
		$adresa = trim($_POST["adresa"]);
	}
	
	
//Verificam toate erorile de input inainte de a introduce valorile in baza de date
if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($nume_err) && empty($prenume_err) && empty($email_err) && empty($tara_err) && empty($judet_err) && empty($oras_err) && empty($adresa_err)){
	
	//Pregatim statement-ul pentru INSERT
	$sql = "INSERT INTO utilizatori (username, password, nume, prenume, email, tara, judet, oras, adresa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
	
	if($stmt = $link->prepare($sql)){
		//Legam variabilele de statement-ul pregatit anterior
		$stmt->bind_param("sssssssss", $param_username, $param_password, $param_nume, $param_prenume, $param_email, $param_tara, $param_judet, $param_oras, $param_adresa);
		//Setam parametrii
		$param_username = $username;
		$param_password = password_hash($password, PASSWORD_DEFAULT); //Criptam parola
		$param_nume = $nume;
		$param_prenume = $prenume;
		$param_email = $email;
		$param_tara = $tara;
		$param_judet = $judet;
		$param_oras = $oras;
		$param_adresa = $adresa;
		
		//Incercam sa executam statement-ul pregatit anterior
		if($stmt->execute()){
			//Redirectionam utilizatorul catre pagina principala sau de login
			header("location: logare.php");
		}else{
				echo "Something went wrong. Please try again later.";
		}
	}
	//Inchidem statement-ul
	$stmt->close();
}
	//Inchidem conexiunea
	$link->close();
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Inregistrare</title>
		<link rel="stylesheet" href="/shop/bootstrap.min.css">
</head>
<body>
	<br></br>

	<div class="wrapper">
	<h1 style="color:purple"><center><span id="showcaseform">Glami</span> <span class="whitetype">Shop</span></h1>
	
<br><br><center>
	<h3 class="whitetype">Formular de inregistrare</h3>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<div class="fromgr <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
		<label class="labelform">Nume utilizator</label>
		<input type="text" name="username"  value="<?php echo $username;?>">
		<span><?php echo $username_err; ?></span>
		</div>
		<div class="fromgr <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
		<label class="labelform">Parola</label>
		<input type="password" name="password"  value="<?php echo $password;?>">
		<span><?php echo $password_err; ?></span>
		</div>
		<div class="fromgr <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
		<label class="labelform">Confirma parola</label>
		<input type="password" name="confirm_password"  value="<?php echo $confirm_password;?>">
		<span><?php echo $confirm_password_err; ?></span>
		</div>
		<div class="fromgr <?php echo (!empty($nume_err)) ? 'has-error' : ''; ?>">
		<label class="labelform">Nume</label>
		<input type="text" name="nume"  value="<?php echo $nume;?>">
		<span><?php echo $nume_err; ?></span>
		</div>
		<div class="fromgr <?php echo (!empty($prenume_err)) ? 'has-error' : ''; ?>">
		<label class="labelform">Prenume</label>
		<input type="text" name="prenume"  value="<?php echo $prenume;?>">
		<span><?php echo $prenume_err; ?></span>
		</div>
		<div class="fromgr <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
		<label class="labelform">Email</label>
		<input type="text" name="email"  value="<?php echo $email;?>">
		<span><?php echo $email_err; ?></span>
		</div>
		<div class="fromgr <?php echo (!empty($tara_err)) ? 'has-error' : ''; ?>">
		<label class="labelform">Tara</label>
		<input type="text" name="tara"  value="<?php echo $tara;?>">
		<span><?php echo $tara_err; ?></span>
		</div>
		<div class="fromgr <?php echo (!empty($judet_err)) ? 'has-error' : ''; ?>">
		<label class="labelform">Judet</label>
		<input type="text" name="judet"  value="<?php echo $judet;?>">
		<span><?php echo $judet_err; ?></span>
		</div>
		<div class="fromgr <?php echo (!empty($oras_err)) ? 'has-error' : ''; ?>">
		<label class="labelform">Oras</label>
		<input type="text" name="oras"  value="<?php echo $oras;?>">
		<span><?php echo $oras_err; ?></span>
		</div>
		<div class="fromgr <?php echo (!empty($adresa_err)) ? 'has-error' : ''; ?>">
		<label class="labelform">Detalii adresa</label>
		<input type="text" name="adresa"  value="<?php echo $adresa;?>">
		<span><?php echo $adresa_err; ?></span>
		<br></br>
		</div>
		<div class="butoane">
		<input type="submit" class="butonform" value="Inregistrare">
		<input type="reset" class="butonform" value="Reseteaza">
		</div>
	</form>
	</div>
</center>
</body>
</html>