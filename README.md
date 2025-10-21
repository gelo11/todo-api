## 🚀 Использование API

> **Базовый URL**: `http://127.0.0.1:8000/api`  
> **Язык**: PHP 8.1+ / Laravel 10  
> **Формат данных**: JSON  
> **Аутентификация**: Не требуется (публичный API)  
> **Валидация**:
> - `title` — не может содержать `<`, `>`, `&`
> - `description` — принимает HTML, но **автоматически очищается от тегов**
> - `status` — только значения: `1`, `2`, `3`, `4`, `5`

## 📦 Ответы API

Все ответы — в формате JSON.

### ✅ Успешные
| Код | Пример |
|-----|--------|
| `200 OK` | `{"data": {...}}` |
| `201 Created` | `{"data": {...}}` |
| `204 No Content` | (пустой ответ) |

### ❌ Ошибки
| Код | Сообщение | Код ошибки |
|-----|-----------|------------|
| `404` | `Not found.` | `NOT_FOUND` |
| `422` | `The given data was invalid.` | `VALIDATION_ERROR` |
| `405` | `Method not allowed.` | `METHOD_NOT_ALLOWED` |
| `500` | `Something went wrong.` | `SERVER_ERROR` |

## 🚀 Установка

```bash
git clone https://github.com/gelo11/todo-api.git
cd todo-api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

### 📦 Postman Collection

Для удобного тестирования API используйте готовую Postman-коллекцию:

👉 [Скачать Postman-коллекцию](docs/api-postman-collection.json)
