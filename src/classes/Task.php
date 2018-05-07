<?php

class Task
{

    protected $host = 'localhost';
    protected $dbname = 'kpronin';
    protected $dbuser = 'kpronin';
    protected $dbpassword = 'neto1718';

    // Подключение к бд
    protected function connectToDb()
    {
        try {
            $pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname;charset=utf8", $this->dbuser, $this->dbpassword, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            die('Произошла ошибка, не удалось установить соединение с базой');
        }
        return $pdo;
    }

    // Отправка запроса
    protected function sendQueryToDb($pdo, $query, $queryParams = [])
    {
        $statement = $pdo->prepare($query);
        try {
            $statement->execute($queryParams);
        } catch (PDOException $e) {
            die('Произошла ошибка, не удалось выполнить запрос');
        }
        return $statement;
    }

    public function getAllTasks()
    {
        $pdo = $this->connectToDb();

        $query = "SELECT * FROM tasks";

        return $this->sendQueryToDb($pdo, $query);
    }

    public function addTask()
    {
        $pdo = $this->connectToDb();

        $query = "INSERT INTO tasks (description, date_added) VALUE (?, NOW())";
        $description = (string)(isset($_POST['task']) ? $_POST['task'] : "");
        $description = trim($description);
        if (!strlen($description)) {
            die('зачем же вам задача из одних пробелов?');
        }

        return $this->sendQueryToDb($pdo, $query, [$description]);
    }

    public function getLastTask()
    {
        $pdo = $this->connectToDb();

        $query = "SELECT description, date_added, id FROM tasks ORDER BY id DESC LIMIT 1";

        $result = $this->sendQueryToDb($pdo, $query)->fetch(PDO::FETCH_ASSOC);

        $result = json_encode($result);
        return $result;
    }

    public function setTaskIsDone()
    {
        $pdo = $this->connectToDb();

        $query = "UPDATE tasks SET is_done = 1 WHERE id = ?";
        $id = (int)!empty($_POST['id']) ? $_POST['id'] : 0;

        $this->sendQueryToDb($pdo, $query, [$id]);
        return true;
    }

    public function deleteTask()
    {
        $pdo = $this->connectToDb();

        $query = "DELETE FROM tasks WHERE id = ?";
        $id = (int)!empty($_POST['id']) ? $_POST['id'] : 0;


        $this->sendQueryToDb($pdo, $query, [$id]);
    }

    public function editTask()
    {
        $pdo = $this->connectToDb();

        $query = "UPDATE tasks SET description = ? WHERE id = ?";
        $description = (string)!empty($_POST['editDescription']) ? $_POST['editDescription'] : 0;
        if (!trim($description) || strlen(trim($description)) === 0) {
            die('задача из пробелов? интересно...'); // Jquery не видит этот die и выдает success, исправить!
        }
        $id = (int)!empty($_POST['id']) ? $_POST['id'] : 0;

        $this->sendQueryToDb($pdo, $query, [$description, $id]);
        return $description;
    }
}