<?php
require_once "./Database/DB.php";
require_once "./Models/Message.php";
require_once "./Repositories/MessageRepository.php";

use Database\DB;
use Repositories\MessageRepository;

$connection = (new DB())->getConnection();
$message_repository = new MessageRepository($connection);
$msg = $message_repository->deleteMessage($_GET['id']);
$message_repository->close();

header(sprintf("Location: index.php?msg=%s", $msg ? "刪除成功" : "刪除失敗"));
exit();