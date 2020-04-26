<?php

namespace Repositories;

use Models\Message;
use mysqli;

class MessageRepository
{
    private $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getAll()
    {
        $SELECT_ALL_MESSAGES = "SELECT * FROM messages";
        $messages = array();
        $query_result = $this->connection->query($SELECT_ALL_MESSAGES);
        for ($i = 0; $i < $query_result->num_rows; $i++) {
            $row = $query_result->fetch_row();
            $message = $this->setMessage($row[0], $row[1], $row[2]);
            $messages[] = $message;
        }
        return $messages;
    }

    private function setMessage($id, $name, $content): Message
    {
        $message = new Message();
        $message->setId($id);
        $message->setName($name);
        $message->setContent($content);
        return $message;
    }

    public function getMessageById($id)
    {
        $SELECT_MESSAGE_BY_ID = sprintf(
            "SELECT * FROM messages WHERE id == %s",
            $id);

        $query_result = $this->connection->query($SELECT_MESSAGE_BY_ID);
        if ($query_result->num_rows != 0) {
            $row = $query_result->fetch_row();
            return $this->setMessage($row[0], $row[1], $row[2]);
        } else {
            return null;
        }
    }

    public function createMessage(Message $message)
    {
        $INSERT_MESSAGE = sprintf(
            "INSERT INTO messages (name, content) VALUES ('%s', '%s')",
            $message->getName(),
            $message->getContent());

        return $this->connection->query($INSERT_MESSAGE);
    }

    public function updateMessage(Message $message)
    {
        $UPDATE_MESSAGE = sprintf("UPDATE messages SET name = '%s', content = '%s' WHERE id = %s", $message->getName(), $message->getContent(), $message->getId());

        return $this->connection->query($UPDATE_MESSAGE);
    }

    public function deleteMessage($id)
    {
        $DELETE_MESSAGE = sprintf(
            "DELETE FROM messages WHERE id = %s", $id);
        return $this->connection->query($DELETE_MESSAGE);
    }

    public function close()
    {
        $this->connection->close();
    }
}