<?php  
  foreach (glob("includes/*.php") as $filename) {
    require_once($filename);
}?>
 
 <?php
session_start();
if (!isset($_POST['functionname']) || !isset($_POST['arguments'])) {
  return;
}

if(!is_login()){
  return;
}

// Extract arguments from the POST variables:
$item_id = (int) $_POST['arguments'][0];
$user_id = $_SESSION['user'];
// echo $item_id;

$res = "fail";
if ($_POST['functionname'] == "add_to_watchlist") {
  // TODO: Update database and return success/failure.
  $sql = "INSERT INTO `watchlist`(`userId`, `auctionId`) VALUES (?,?)";
  try{
    prepare_bind_excecute($sql, 'si', $user_id, $item_id);
    $res = "success";
  } catch(DBException){
    $res = 'dupulicate data';
  }
}
else if ($_POST['functionname'] == "remove_from_watchlist") {
  // TODO: Update database and return success/failure.
  $sql = "DELETE FROM `watchlist` WHERE auctionId = ?";
  try{
    prepare_bind_excecute($sql, 'i', $item_id);
    $res = "success";
  } catch(DBException){}
}

// Note: Echoing from this PHP function will return the value as a string.
// If multiple echo's in this file exist, they will concatenate together,
// so be careful. You can also return JSON objects (in string form) using
// echo json_encode($res).
echo $res;

?>