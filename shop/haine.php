<?php
    session_start(); 
    
    require_once "conexiune.php";

    if (isset($_POST["add"])){  //variabile adaugate
        if (isset($_SESSION["cart"])){
            $item_array_id = array_column($_SESSION["cart"],"produse_id");
             
            if (!in_array($_GET["id"],$item_array_id)){
                $count = count($_SESSION["cart"]);
                $item_array = array(
                    'produse_id' => $_GET["id"],
                    'item_nume' => $_POST["hidden_name"],
                    'produse_pret' => $_POST["hidden_price"],
                    'item_quantity' => $_POST["quantity"],
                );
                $_SESSION["cart"][$count] = $item_array;
                echo '<script>window.location="Cart.php"</script>';
            }else{
                echo '<script>alert("Produsul este deja in cos, modificati cantitatea")</script>';
                echo '<script>window.location="Cart.php"</script>';
            }
        }else{
            $item_array = array(
                'produse_id' => $_GET["id"],
                'item_nume' => $_POST["hidden_name"],
                'produse_pret' => $_POST["hidden_price"],
                'item_quantity' => $_POST["quantity"],
            );
            $_SESSION["cart"][0] = $item_array;
        }
    }
    if (isset($_GET["action"])){
        if ($_GET["action"] == "delete"){
            foreach ($_SESSION["cart"] as $keys => $value){
                if ($value["produse_id"] == $_GET["id"]){
                    unset($_SESSION["cart"][$keys]);
                    echo '<script>window.location="Cart.php"</script>';
                }
            }
        }
    }
?>

<!doctype html>
<html>
<style>
.button {
  background-color:  #008CBA; 
  color: white;
  border: none;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cosul tau</title>
 		
</head>

<body>
		<div  style="float:left;">	
			<button class="button" onclick="location.href='haine.php'">Haine</button>
			<button class="button" onclick="location.href='pantofi.php'">Pantofi</button>
			<button class="button" onclick="location.href='cart.php'">Toate produsele</button>
		</div>
		
		<div  style="float:right;">		
                <a href="index.php" class="btn btn-link" style="color:blue; font-size:130%">Logout</a>
        </div><br><br>

        <div class="container-fluid">
            <h2 style="color:purple; font-size:230%;"><center>Glami Shop</center></h2>
		</div>
     
			
			
       <br><br><br><br>
        <?php
		
			                                     	//AFISARE PRODUSE PE PAGINA
            $query = "SELECT * FROM produse WHERE categorie = 'haine' ";
            $result = mysqli_query($link,$query);
            if(mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <div class="col ">

                        <form method="post" action="Cart.php?action=add&id=<?php echo $row["id"]; ?>">

                        <div class="input-group mb-3">
                                <img src="<?php echo $row["id"].".jpg"?>" style="width:10%; height:15% float:left;"/>

                                <h5 style="color:grey; font-size:120%" class="text-info"><?php echo $row["nume"]; ?></h5> 								
                                <h5 style="color:red; font-size:110%" class="text-danger"><?php echo $row["pret"] , " lei";?></h5>
				<h5 style="color:turquoise; font-size:100%" class="text-info"><?php echo $row["descriere"]; ?></h5>
                                <input type="hidden" name="hidden_name" value="<?php echo $row["nume"]; ?>">
                                <input type="hidden" name="hidden_price" value="<?php echo $row["pret"]; ?>">
                                <input type="text" name="quantity" class="form-control" value="1">
                                <button type="submit" name="add"  class="btn btn-outline-secondary">Adauga in cos</button>
                            </div>
                        </form>
                    </div><br><br>
                    <?php
                }
            }
        ?>

	<hr width: 100%; margin: auto; margin-top: 55%; margin-bottom: 55%; border-color: purple; border-height: 2px;></hr>
	<hr width: 100%; margin: auto; margin-top: 55%; margin-bottom: 55%; border-color: purple; border-height: 2px;></hr>

        <div style="clear: both"></div>
       <center> <h3  style="color: orange; font-size:180%" class="title2">Detalii Cos</h3><br>

        <div class="table-responsive">
            <table border="1" class="table table-bordered">
            <tr>
                <th width="30%">Nume Produs</th>
                <th width="10%">Cantitate</th>
                <th width="13%">Detalii pret</th>
                <th width="10%">Pretul total</th>
                <th width="17%">Elimina Produs</th>
            </tr>

            <?php
                if(!empty($_SESSION["cart"])){
                    $total = 0;
                    foreach ($_SESSION["cart"] as $key => $value) {
                        ?>
                        <tr>
                            <td><?php echo $value["item_nume"]; ?></td>
                            <td><?php echo $value["item_quantity"]; ?></td>
                            <td><?php echo $value["produse_pret"], " lei"; ?></td>
                            <td><?php echo number_format($value["item_quantity"] * $value["produse_pret"], 2) , " lei" ;?></td>
                            <td><a href="Cart.php?action=delete&id=<?php echo $value["produse_id"]; ?>"><span
                                        class="text-danger">Elimina Produs</span></a></td>

                        </tr>
                        <?php
                        $total = $total + ($value["item_quantity"] * $value["produse_pret"]);
                    }
                        ?>
                        <tr>
                            <td colspan="3" align="right">Total</td>
                            <th align="right"><?php echo number_format($total, 2) , " lei"; ?></th>
                            <td></td>
                        </tr>
                        <?php
                    }
                ?>
            </table></center>
        </div>
		
</body>
</html>