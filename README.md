# 📊 Loan App

A bridging loan calculation app built using **Laravel (API)** and **Vue 3 (SPA)** — created as part of a full-stack take-home assignment.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 10, Sanctum (API Auth), MySQL/SQLite  
- **Frontend**: Vue 3 (Vite), Tailwind CSS  
- **Auth**: Laravel Sanctum + Vue Router Guards  
- **Optional**: Docker for local dev

---

## ⚙️ Requirements

- PHP ≥ 8.1  
- Composer  
- Node.js ≥ 18  
- npm  
- MySQL or SQLite (default: SQLite)  
- Docker (optional)

---

## 🚀 Getting Started

### 1. Clone the Repo

```bash
git clone https://github.com/yourusername/loan-app.git
cd loan-app
2. Backend Setup (Laravel)
bash
Copy
Edit
cd loan-backend
cp .env.example .env
composer install
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
To run tests:

bash
Copy
Edit
php artisan test
3. Frontend Setup (Vue 3)
bash
Copy
Edit
cd ../loan-frontend
npm install
npm install -D tailwindcss@3 @tailwindcss/cli
npm run dev
Frontend: http://localhost:5173

Backend API: http://localhost:8000/api

🧪 Test Users
Email	Password	Notes
test@example.com	password	Default seeded user
repayment@example.com	password	Repayment scenario demo
interest@example.com	password	Interest-only demo
retained@example.com	password	Retained-interest demo

🔐 Authentication
Register/login at /register or /login

Tokens stored in localStorage via Sanctum

Protected routes: /dashboard, /loan/:id

🧱 Project Structure
bash
Copy
Edit
loan-app/
├── loan-backend/       # Laravel API
│   ├── app/
│   ├── routes/
│   └── database/
├── loan-frontend/      # Vue 3 + Tailwind SPA
│   ├── views/
│   ├── components/
│   └── router/
└── docker-compose.yml  # Optional Docker setup
🐳 Run with Docker (Optional)
bash
Copy
Edit
docker-compose up --build
Then visit:

Vue Frontend: http://localhost:5173

Laravel API: http://localhost:8000/api

🎯 Features
📆 Daily interest loan calculation engine

💸 Supports repayment, interest-only, and retained interest

🛠 Add/edit/delete mid-term events (rate changes, part payments, early payoff)

♻️ Recalculation on each user action

🧾 Headline summaries and refund breakdown

✅ Full authentication and protected routes

🧪 Feature and unit test coverage