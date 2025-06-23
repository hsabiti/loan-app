# loan-app

Novellus Sample Loan App — built with Laravel (API) and Vue 3 (SPA).

---

## ⚙️ Requirements

- **Node.js** ≥ 18
- **PHP** ≥ 8.1
- **Composer**
- **MySQL** or **SQLite** (uses SQLite by default)

---

🚀 Setup Instructions
Backend

```bash
cd loan-backend
cp ../.env.example .env
composer install
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve

Frontend
cd loan-frontend
npm install
npm run dev

🔐 Auth
Use the `/register` or `/login` pages to authenticate.

Sanctum tokens are saved in localStorage.



Run It
Docker:-
docker-compose up --build
Then visit:

Frontend: http://localhost:5173

Backend API: http://localhost:8000/api


🔐 Authentication
Visit /register or /login to create/login a user.

Auth tokens are issued via Laravel Sanctum and stored in localStorage.

Routes like /dashboard are protected using Vue Router navigation guards.


# Terminal 1 - Laravel API
cd loan-backend
php artisan serve

# Terminal 2 - Vue Frontend
cd loan-frontend
npm run dev


Run with Docker (Optional)
docker-compose up --build

loan-app/
├── loan-backend/     # Laravel backend API
├── loan-frontend/    # Vue 3 frontend SPA
└── docker-compose.yml

