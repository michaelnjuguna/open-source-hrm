# 🇰🇪 Kenyan HRM System (Open Source)

A modern, Laravel + FilamentPHP-based **Human Resource Management System** tailored for Kenyan businesses. This solution simplifies employee management, payroll (PAYE, NHIF, NSSF), attendance, and more — all in compliance with Kenyan labor laws.

## 🔧 Tech Stack

-   **Laravel 12+**
-   **FilamentPHP 3.x** (admin panel)
-   **MySQL/MariaDB** (database)
-   **PHP 8.2+**
-   **Tailwind CSS** (via Filament)
-   **Alpine.js** (via Filament)

---

## 🚀 Features

### ✅ Core Modules

-   **Employee Records** (with KRA PIN, NSSF, NHIF, etc.)
-   **Departments**
-   **Attendance**
-   **Leave**
-   _More modules coming soon_  
     (Payroll, Recruitment, Training, etc.)

## ⚙️ Installation

```bash
git clone https://github.com/michaelnjuguna/open-source-hrm.git
cd open-source-hrm

composer install
cp .env.example .env
php artisan key:generate

# Setup DB credentials in .env
php artisan migrate --seed

composer run dev
```

## 🤝 Contributing

All contributions are welcome. Please fork the repo, create a feature branch and submit a pull request.

## 📜 License

[MIT license](LICENSE)

Made with ❤️
