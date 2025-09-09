<?php
class database
{
    private $db;

    public function __construct()
    {
        $this->db = new SQLite3('users.db');

        $query = "CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            email TEXT NOT NULL
        );";
         $this->db->exec($query);
    }

      /**
     * Получить подключение к БД
     * 
     * @return \SQLite3 Подключение к базе данных
     */
    public function getConnection(): \SQLite3
    {
        return $this->db;
    }

    /**
     * Закрыть соединение
     */
    public function closeConnection(): void
    {
        if ($this->db) {
            $this->db->close();
        }
    }


}