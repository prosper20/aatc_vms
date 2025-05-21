# AATC VMS

Follow these steps to set up a project on your local environment.

## Prerequisites
Make sure you have the following installed:
- PHP (>= 8.2)
- Composer
- MySQL or any supported database
- Node.js & npm

## Installation Steps

### 1. Clone the Repository
```bash
git clone https://github.com/cmwebsolution/laravel-blade-starter-kit.git
cd laravel-blade-starter-kit
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Create Environment File
```bash
touch .env
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Configure Database
Add the following to the `.env` file and update the database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 6. Run Migrations
```bash
php artisan migrate
```

### 7. Start Development Server
```bash
php artisan serve
```
Access the application at: `http://127.0.0.1:8000`

### 8. Compile Assets
```bash
npm run dev  # For development
npm run build  # For production
```

