<?php
/**
 * mmilan, website that manage e-sport teams
 * Propulsed by Arthur Mondon.
 *
 * @author     Arthur Mondon
 *
 * Contributors :
 * -
 *
 */
define('MyConst', TRUE);
require('app/config.php');

// Initialiser la session
session_start();

if(isset($_GET['blocked']) or isset($_POST['blocked'])){
    $query = $conn2->prepare("UPDATE customers 
								SET CustomersStatus = 'blocked'
                                WHERE customers.CustomersId = ?");
    $query->bindValue(1, htmlspecialchars($_SESSION["PlayerId"], ENT_QUOTES, 'UTF-8') );
    $query->execute();
    header("Location: logout.php?msg=blocked");
}
// Détruire la session.
if(session_destroy()){
    // Redirection vers la page de connexion
    if(isset($_GET['msg']) and $_GET['msg'] == "blocked"){
        header("Location: login.php?msg=blocked");
    }else{
        header("Location: login.php");
    }

}
?>