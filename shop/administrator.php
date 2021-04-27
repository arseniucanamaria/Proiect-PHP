<?php
//Includem fisierul pentru conectare
require_once "conexiune.php";

//Definim variabilele necesare 
$adminpass = "";
$adminpass_err = "";

//Procesarea datelor din formular in momentul in care acesta este trimis
if($_SERVER["REQUEST_METHOD"] == "POST"){
	//Verificam daca administratorul a introdus cheia in camp
	if(empty(trim($_POST["adminpass"]))){
		$adminpass_err = "Introduceti cheia de administrator.";
	}else{
		$adminpass = trim($_POST["adminpass"]);
	}
	//Validarea datelor de login
	if(empty($adminpass_err)){
		
		//select		
		$sql = "SELECT adminpassword FROM admin WHERE adminpassword = ?";
		
		if($statement = $link->prepare($sql)){ 
		
			$statement->bind_param("s", $param_adminpass);
			
			//Setam parametrii
			$param_adminpass = $adminpass;
			
			//EXECUTIA statement-ului pregatit anterior
			if($statement->execute()){
				$statement->store_result();
				
				//Verificam daca username-ul primit exista in baza de date, daca da verificam si parola
				if($statement->num_rows >= 1){
				
					//Legam variabila rezultata si trimitem adminul la pagina de manipulare a produselor
					$statement->bind_result($adminpass);
					header("location: manipulareprod.php");
			
				}else{
					$adminpass_err = "Cheie de administrator invalida";
				}
			}else{
				echo "Oops! Something went wrong. Please try again later.";
			}
		}
		//Inchidem statement-ul
		$statement->close();
	}
	//Inchidem conexiunea
	$link->close();
}
?>



<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Administrator Produse</title>
		<link rel="stylesheet" href="/shop/bootstrap.min.css">
</head>

<body>
<div class="jumbotron">
    <h1 style="color:purple"><center><span id="hdshowcase">Glami</span> Shop</h1><br>
	<center>
    <h5 class="whitetype">Conectare administrator</h5>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="fromgr <?php echo (!empty($adminpass_err)) ? 'has-error' : ''; ?>">
            <label class="labelform">Parola</label>
            <input type="password" name="adminpass" value="<?php echo $adminpass;?>">
            <span><?php echo $adminpass_err; ?></span>
        </div>
        <div class="butoane">
            <input type="submit" class="butonform" value="Logare">
        </div>
    </form></center>
	</div>
</body>

</html>