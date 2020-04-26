<?php

require_once "./Database/DB.php";
require_once "./Models/Message.php";
require_once "./Repositories/MessageRepository.php";

use Database\DB;
use Models\Message;
use Repositories\MessageRepository;

?>
<body>
<h1><a href="index.php">Ernie's 留言板ヽ(●´∀`●)ﾉ</a></h1>
<?php
if (isset($_GET['msg'])) {
    echo $_GET['msg'];
} else {
    echo "以下開放留言！";
}
?>
<hr>
<table>
    <tr>
        <td>Id</td>
        <td>Name</td>
        <td>Message</td>
        <td></td>
        <td></td>
    </tr>
    <?php
    if (isset($_GET['edit'])) {
        inEditState($_GET['id']);
    } else if (isset($_GET['delete'])) {
        inConfirmDeleteState($_GET['id']);
    } else if (isset($_GET['create'])) {
        inCreateState();
    } else {
        inViewState();
    }
    ?>
</table>
</body>
<?php

function inViewState($edit_option = true, $delete_option = true, $create_option = true)
{
    $connection = (new DB())->getConnection();
    $message_repository = new MessageRepository($connection);
    $messages = $message_repository->getAll();
    $html = "";

    foreach ($messages as $message) {
        if ($message instanceof Message) {

            $editButton = $edit_option ?
                sprintf("<a href='index.php?edit=true&&id=%d'>編輯</a>", $message->getId())
                : "";

            $deleteButton = $delete_option ?
                sprintf("<a href='index.php?delete=true&&id=%d'>刪除</a>", $message->getId())
                : "";
            $html .= sprintf(
                "<tr><td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> </tr>",
                $message->getId(),
                $message->getName(),
                $message->getContent(),
                $editButton,
                $deleteButton
            );
        }
    }
    $html .= $create_option ? "<tr><td></td><td></td><td><a href='index.php?create=true'>建立留言</a></td><td></td></tr>" : "";
    $message_repository->close();
    echo $html;
}

function inCreateState()
{
    inViewState(false, false, false);
    $html = sprintf(
        "<form action = 'create_message.php' method='post'>
                    <tr>
                        <td></td>
                        <td><input type='text' name='name'></td>
                        <td><input type='text' name='content'></td>
                        <td><input type='submit' value='儲存'></td>
                        <td><a href='index.php'>取消</a></td>
                    </tr>
                    </form>
     ");
    echo $html;
}

function inEditState($id)
{
    $connection = (new DB())->getConnection();
    $message_repository = new MessageRepository($connection);
    $messages = $message_repository->getAll();

    foreach ($messages as $message) {
        if ($message instanceof Message) {
            if ($message->getId() != $id) {
                $html = sprintf(
                    "<tr><td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> </tr>",
                    $message->getId(),
                    $message->getName(),
                    $message->getContent(),
                    "",
                    ""
                );
                echo $html;
            } else {
                $html = sprintf('<form action="update_message.php" method="POST">
                                        <tr>
                                            <td>%s<input type="hidden" name="id" value="%s"></td>
                                            <td><input type="text" name="name" value="%s"></td>
                                            <td><input type="text" name="content" value="%s"></td>
                                            <td><input type="submit" value="儲存"></td>
                                            <td><a href="index.php">取消</td>
                                        </tr>
                                        </form>',
                    $message->getId(),
                    $message->getId(),
                    $message->getName(),
                    $message->getContent());
                echo $html;
            }
        }
    }
}

function inConfirmDeleteState($id)
{
    $connection = (new DB())->getConnection();
    $message_repository = new MessageRepository($connection);
    $messages = $message_repository->getAll();

    foreach ($messages as $message) {
        if ($message instanceof Message) {
            $editButton = $id === $message->getId() ? sprintf("<a href='delete_message.php?id=%s'>確認刪除</a>", $message->getId()) : "";
            $deleteButton = $id === $message->getId() ? sprintf("<a href='index.php'>取消</a>") : "";
            $html = sprintf(
                "<tr><td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> </tr>",
                $message->getId(),
                $message->getName(),
                $message->getContent(),
                $editButton,
                $deleteButton
            );
            echo $html;
        }
    }
}

?>
