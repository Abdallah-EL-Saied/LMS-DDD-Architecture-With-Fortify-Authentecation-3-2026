# 🎓 Fatema Alzahraa Center - LMS
![Branding](public/FZLogo.png)

A modern, secure, and robust Learning Management System (LMS) built with **Laravel 12**, **Livewire**, and **Flux UI**, following **Domain-Driven Design (DDD)** principles.

---

## 🚀 Key Features

### 🔐 Advanced Security
- **Strong Password Policy**: Enforced complexity requirements (8+ chars, mixed case, numbers).
- **Two-Factor Authentication (MFA)**: Built-in support for 2FA on sensitive actions (login, password change).
- **Default Password Protection**: Prevents account access with weak default passwords.
- **Password Visibility**: Interactive toggle (Show/Hide) on all password fields.

### 🌐 Seamless Social Login
- **Google Authentication**: One-click login and registration via Google.
- **Account Linking**: Connect an existing traditional account with Google from the settings page.
- **Data Integrity**: Intelligent prevention of duplicate social connections and account switching conflicts.

### 🛡️ Smart Role Management
- **Role-Based Access Control (RBAC)**: Powered by `spatie/laravel-permission`.
- **Automatic Role Assignment**: New users automatically receive the `student` role.
- **Secure Management**: Administrative routes protected with strict role-based middleware.

### 🎨 Custom Branding
- **Identity First**: Fully customized with academy-specific logos and favicons.
- **Flux UI Integration**: Beautiful, modern, and accessible components using the Flux framework.

---

## 🏗️ Architecture: Pure DDD

The project is structured using **Domain-Driven Design** to ensure scalability and maintainability.

```text
app/
├── Domains/           # Business logic & state (Framework agnostic)
├── Application/       # Use Cases & Application Services (Actions)
├── Infrastructure/    # Persistence & External integrations (Eloquent)
└── Interfaces/        # Web/API entry points (Controllers, Livewire)
```

---

## 🛠️ Tech Stack

- **Backend**: PHP 8.2+, Laravel 12
- **Frontend**: Livewire, Flux UI, Tailwind CSS
- **Authentication**: Laravel Fortify, Socialite
- **Security**: MFA, Spatie Permissions
- **Database**: MySQL/PostgreSQL

---

## 🏁 Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/your-username/lms.git
   cd lms
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Configure Environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run Migrations & Seeders:**
   ```bash
   php artisan migrate --seed
   ```

5. **Start the server:**
   ```bash
   php artisan serve
   ```

---

## 📖 Project Documentation

For a detailed technical report on all implemented files, architectural cycles, and security logic, please refer to:
👉 **[Technical Report (Other_Files/README.md)](Other_Files/README.md)**

---

**Developed for Fatema Alzahraa Center**
*Empowering education through modern technology.*
