<?php
//Initializam sesiunea
session_start();

//Verificam daca utilizatorul este deja logat, daca da o sa il trimitem la pagina cu produse
if(isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] === true)){
	header("location: cart.php");
	exit;
}

	//Includem fisierul pentru conectare
	require_once "conexiune.php";

	//Definim variabilele necesare si le initializam cu void
	$username = $password = "";
	$username_err = $password_err = "";
	
	//Procesarea datelor din formular in momentul in care acesta este trimis
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		//Verificam daca username este necompletat
		if(empty(trim($_POST["username"]))){
			$username_err = "Introduceti username-ul.";
	}else{
		$username = trim($_POST["username"]);
	}
	
	//Verificam daca password este necompletat
	if(empty(trim($_POST["password"]))){
		$password_err = "Introduceti parola.";
	}else{
		$password = trim($_POST["password"]);
	}
	
	//Validarea datelor de login
	if(empty($username_err) && empty($password_err)){
		
		//Pregatim un select
		$sql = "SELECT id, username, password FROM utilizatori WHERE username = ?";
		$stmt = "";
		if($stmt = $link->prepare($sql)){
			$stmt->bind_param("s", $param_username);
			//Setam parametrii
			$param_username = $username;
			//Incercam executia statement-ului pregatit anterior
			if($stmt->execute()){
				$stmt->store_result();
			//Verificam daca ursername-ul primit exista in baza de date, daca da verificam si parola
			if($stmt->num_rows >= 1){
				//Legam variabila rezultata
				$stmt->bind_result($id, $username, $hashed_password);
				
				if($stmt->fetch()){
					if(password_verify($password, $hashed_password)){
						session_start();
						//Retinem datele in variabilele de sesiune
						$_SESSION["loggedin"] = true;
						$_SESSION["id"] = $id;
						$_SESSION["username"] = $username;
						//Redirectionam userul catre pagina cu produsele
						header("location: cart.php");
						
					}else{
						//Afisam un mesaj prin care anuntam utilizatorul ca parola introdusa este gresita
						$password_err = "Parola introdusa nu este valida.";
					}
				}
			}else{
				//Afisam un messaj prin care anuntam utilizatorul ca userul nu a fost gasit in baza de date
				$username_err = "Username-ul nu exista.";
			}
			}else{
				echo "Oops! Something went wrong. Please try again later.";
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
	<title>Login</title> 
		<link rel="stylesheet" href="/shop/bootstrap.min.css">
</head>
<body>
	<div class="jumbotron">
	<h1 style="color:purple"><center><span id="showcaseform">Glami</span> <span class="whitetype">Shop</span></h1>
	<h3 class="whitetype">Login</h3>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<div class="fromgr <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
		<label  >Nume utilizator</label>
		<input type="text" name="username"  value="<?php echo $username;?>">
		<span><?php echo $username_err; ?></span>
		</div>
		<div class="fromgr <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
		<label >Parola</label>
		<input type="password" name="password"  value="<?php echo $password;?>">
		<span><?php echo $password_err; ?></span>
		</div>
	 
		<button type="submit" class="btn btn-primary"> Logare</button>
 
	</form> 
	<a class="btn btn-link" href="administrator.php">Esti administrator? Click aici.</a>
	</div>
</body>
</html>