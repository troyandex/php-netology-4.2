<?php

class TaskTable extends Task
{
    public function sortTable($sortingType)
    {
        $pdo = $this->connectToDb();

        switch ($sortingType) {
            case 'date':
                $query = "SELECT * FROM tasks ORDER BY date_added ASC";
                break;
            case 'status':
                $query = "SELECT * FROM tasks ORDER BY is_done ASC";
                break;
            case 'description':
                $query = "SELECT * FROM tasks ORDER BY description ASC";
                break;
            default:
                die('Произошла ошибка сортировки, попробуйте еще раз');
        }

        $result = $this->sendQueryToDb($pdo, $query)->fetchAll();
        return json_encode($result);
    }
}