<?php
require_once '../../Config/conn.config.php';
header('Content-Type: application/json');

if(!isset($_POST['user_id'], $_POST['action'])){
    echo json_encode(['success'=>false, 'message'=>'Missing parameters']);
    exit;
}

$user_id = $_POST['user_id'];
$action = $_POST['action'];
$status = $action === 'restrict' ? 'restricted' : 'activated';

$stmt = $conn->prepare("UPDATE user_patient SET status=? WHERE user_id=?");
if($stmt->execute([$status, $user_id])){
    echo json_encode(['success'=>true, 'message'=> $action === 'restrict' ? 'User has been restricted.' : 'User has been unrestricted.']);
}else{
    echo json_encode(['success'=>false,'message'=>'Database error']);
}
?>
