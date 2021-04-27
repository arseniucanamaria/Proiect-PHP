<?php
    //Initializam sesiunea
    session_start();
	
    //Resetam toate variabilele din sesiune
    $_SESSION = array();
	
    //Distrugem sesiunea
    session_destroy();
	
    //Redirectionam catre pagina index
    header("location:index.php");
    exit;
?>