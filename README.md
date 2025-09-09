# PHP REST API (SQLite)

Простой REST API для работы с пользователями на чистом PHP и SQLite.  
Реализованы CRUD-операции и авторизация.

---
##  Установка

```bash
git clone https://github.com/username/php-rest-api.git
cd php-rest-api
php -S localhost:8000 -t api
---

## Методы API

### 1. Создать пользователя
**POST** `/users`

Тело запроса (JSON):
```json
{
  "username": "user1",
  "password": "12345",
  "email": "user1@example.com"
}
```

Ответ:
```json
{
  "status": "success",
  "id": 1
}
```

---

### 2. Авторизация пользователя
**POST** `/users/login`

Тело запроса (JSON):
```json
{
  "username": "user1",
  "password": "12345"
}
```

Ответ:
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "username": "user1",
    "email": "user1@example.com"
  }
}
```

---

### 3. Получить информацию о пользователе
**GET** `/users/{id}`

Пример:  
```
GET /users/1
```

Ответ:
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "username": "user1",
    "email": "user1@example.com"
  }
}
```

---

### 4. Обновить пользователя
**PUT** `/users/{id}`

Тело запроса (JSON):
```json
{
  "username": "newname",
  "password": "newpass",
  "email": "new@example.com"
}
```

Ответ:
```json
{
  "status": "success"
}
```

---

### 5. Удалить пользователя
**DELETE** `/users/{id}`

Пример:  
```
DELETE /users/1
```

Ответ:
```json
{
  "status": "success"
}
```

---

