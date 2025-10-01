## 🚀 Использование API

> **Базовый URL**: `http://127.0.0.1:8000/api`  
> **Язык**: PHP 8.1+ / Laravel 10  
> **Формат данных**: JSON  
> **Аутентификация**: Не требуется (публичный API)  
> **Валидация**:
> - `title` — не может содержать `<`, `>`, `&`
> - `description` — принимает HTML, но **автоматически очищается от тегов**
> - `status` — только значения: `1`, `2`, `3`, `4`, `5`

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
