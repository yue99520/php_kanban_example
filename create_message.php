<?php
require_once "./Database/DB.php";
require_once "./Models/Message.php";
require_once "./Repositories/MessageRepository.php";

use Database\DB;
use Models\Message;
use Repositories\MessageRepository;

$connection = (new DB())->getConnection();
$message_repository = new MessageRepository($connection);
$message = new Message();
$message->setName($_POST['name']);
$message->setContent($_POST['content']);
$msg = $message_repository->createMessage($message);
$message_repository->close();

header(sprintf("Location: index.php?msg=%s", $msg ? "留言成功" : "留言失敗"));
exit();