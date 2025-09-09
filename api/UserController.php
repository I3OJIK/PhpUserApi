<?php
require_once __DIR__ . '/../Database.php';

/**
 * CRUD для users
 * 
 * @param \SQLite3 $db подключение к БД
 */
class UserController {
    private $db;

    /**
     * Подключение к бд
     */
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Создать пользователя
     * 
     * @param array $data данные для создания (username, password, email)
     * 
     * @return array
     */
    public function create($data): array
    {
        if (!isset($data['username']) || !isset($data['password']) || !isset($data['email'])) {
            http_response_code(400);
            return [
                'status' => 'error',
                'message' => 'заполните все поля: username, password, email'
            ];
        }

        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
        $stmt->bindValue(':username', $data['username'], SQLITE3_TEXT);
        $stmt->bindValue(':password',$hashedPassword , SQLITE3_TEXT);
        $stmt->bindValue(':email', $data['email'], SQLITE3_TEXT);
        if ($stmt->execute()) {
            http_response_code(201);
            return
            [   'status' => 'success',
                'id' => $this->db->lastInsertRowID(),
            ];
        }
        http_response_code(400);
        return [
            'status' => 'error',
            'message' => 'Пользователь уже зарегестирован'
        ];
       
    }

    /**
     * Получить пользователя
     * 
     * @param int $id id пользователя
     * 
     * @return array
     */
    public function get($id): array
    {
        // проверка на существование пользователя
        $existingUser = $this->getUserById($id);
        if (!$existingUser) {
            http_response_code(404);
            return [
                'status' => 'error',
                'message' => 'пользователь не найден'
            ];
        }
        $stmt = $this->db->prepare("SELECT id, username, email FROM users WHERE id = :id");
        $stmt->bindValue(':id', $id,  SQLITE3_INTEGER);

        $user = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
        if ($user) 
            return [
                'status' => 'success',
                'data' => $user
            ];
        return ['status' => 'error'];
    }

    /**
     * Обновить пользователя
     * 
     * @param int $id id пользователя
     * @param array $data данные для обновления (username, password, email)
     * 
     * @return array
     */
    public function update($id, $data): array
    {
        // проверка на существование пользователя
        $existingUser = $this->getUserById($id);
        if (!$existingUser) {
            http_response_code(404);
            return [
                'status' => 'error',
                'message' => 'пользователь не найден'
            ];
        }
        if (!isset($data['username']) || !isset($data['password']) || !isset($data['email'])) {
            http_response_code(400);
            return [
                'status' => 'error',
                'message' => 'заполните все поля: username, password, email'
            ];
        }

        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE users SET username = :username, password = :password, email = :email WHERE id = :id");
        $stmt->bindValue(':username', $data['username'], SQLITE3_TEXT);
        $stmt->bindValue(':password', $hashedPassword, SQLITE3_TEXT);
        $stmt->bindValue(':email', $data['email'], SQLITE3_TEXT);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        if ($stmt->execute()) {
            return ['status' => 'success'];
        } else {
            return [
                'status' => 'error',
                'message' => 'Не удалось обновить пользователя'
            ];
        }
    }

    /**
     * Удалить пользователя
     * 
     * @param int $id id пользователя 
     * 
     * @return array
     */
    public function delete($id): array
    {
        // проверка на существование пользователя
        $existingUser = $this->getUserById($id);
        if (!$existingUser) {
            http_response_code(404);
            return [
                'status' => 'error',
                'message' => 'пользователь не найден'
            ];
        }
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        if ($stmt->execute()) {
            return ['status' => 'success'];
        } else {
            return [
                'status' => 'error',
                'message' => 'Не удалось удалить пользователя'
            ];
        }
    }

    /**
     * Авторизация пользователя
     * 
     * @param array $data данные для авторизации (username, password)
     * 
     * @return array
     */
    public function login($data): array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindValue(':username', $data['username'], SQLITE3_TEXT);
        $result = $stmt->execute();
        $user = $result->fetchArray(SQLITE3_ASSOC);

        if ($user && password_verify($data['password'], $user['password'])) {
            return 
            [
                'status' => 'success',
                'data' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                ]
            ];
        } else {
            return ['status' => 'error', 'message' => 'Неверный логин или пароль'];
        }
    }
    
    /**
     * проверка существования пользователя
     * 
     * @param mixed $id
     * 
     * @return array|false
     */
    private function getUserById($id): array|false
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_ASSOC);
    }
 
}
