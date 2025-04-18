# tably
# 🚀 Laravel Backend (API)

## 📦 Установка

```bash
git clone <репозиторий>
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

## ⚙️ Основное

- PHP 8.1+
- Laravel 10
- RESTful API
- Авторизация (Sanctum или JWT)
- FormRequest для валидации
- Service Layer (бизнес-логика)
- Resources (красивые JSON-ответы)
- Eloquent ORM

## 📁 Структура

- `app/Http/Controllers` — Контроллеры
- `app/Http/Requests` — FormRequest
- `app/Services` — Сервисы
- `app/Http/Resources` — JSON ресурсы
- `routes/api.php` — Роуты API

## 🧪 Тестирование

```bash
php artisan test
```

## ✅ Запуск

```bash
php artisan serve
```

## 🧠 Полезные команды

```bash
php artisan migrate
```

# 🎨 Vue Frontend (SPA)

## 📦 Установка

```bash
cd vueFront
npm install
```

## 🚀 Запуск

```bash
npm run dev
```

## ⚙️ Основное

- Vue 3 + Vite
- Composition API + `<script setup>`
- Vue Router
- Axios (работа с API)
- Composable-функции (useAuth, useForm)

## 📁 Структура

- `src/components` — Компоненты
- `src/views` — Страницы
- `src/router` — Роутинг
- `src/stores` — Pinia store
- `src/api` — Axios запросы
- `src/composables` — Переиспользуемая логика

## 🔐 Авторизация

- Токен сохраняется в `localStorage`
- Добавляется в заголовок Authorization
- Используется `router.beforeEach` для защиты маршрутов

## 🌐 Связь с Laravel

- `.env`: установить `BACKEND_URL=http://localhost:8000/api`
- Все запросы через Axios с baseURL