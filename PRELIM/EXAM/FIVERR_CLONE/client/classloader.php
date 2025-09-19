<?php  
require_once 'classes/Database.php';
require_once 'classes/Offer.php';
require_once 'classes/Proposal.php';
require_once 'classes/User.php';
require_once 'classes/Category.php'; // New
require_once 'classes/Subcategory.php'; // New

$databaseObj= new Database();
$offerObj = new Offer();
$proposalObj = new Proposal();
$userObj = new User();
$categoryObj = new Category(); // New
$subcategoryObj = new Subcategory(); // New

$userObj->startSession();
?>