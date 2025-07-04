# CodeIgniter 3 Blog Backend (Backend Developer Task)

This is a simple blog backend system built using CodeIgniter 3. It allows you to create, edit, delete, and filter blog posts. Features include image uploads, category tagging, and AJAX-based CRUD operations.

---

## Requirements

- PHP 7.2+
- MySQL
- WAMP (Windows) or XAMPP (Cross-platform)
- Web browser (to access via localhost)

---

## Getting Started

### Step 1: Project Setup

Place this project folder **directly inside your WAMP `www/` or XAMPP `htdocs/` directory**:

- For WAMP:
```

C:/wamp64/www/backend-task

```

- For XAMPP:
```

C:/xampp/htdocs/backend-task

```

⚠️ If you rename the folder or put it in a subfolder, you must update both `.htaccess` and `application/config/config.php` to reflect the new base URL.

---

### Step 2: Database Setup

1. Open phpMyAdmin or MySQL CLI.
2. Create a database named:

```

backend\_task\_db

```

3. Import the SQL file `backend_task_db.sql` located in the project root to populate the database.

---

### Step 3: Configure Application

#### A. Update the database config in:
```

application/config/database.php

````

Set it as:
```php
'hostname' => 'localhost',
'username' => 'root',
'password' => '',
'database' => 'backend_task_db',
````

Adjust `password` if your MySQL has one.

#### B. Update the base URL in:

```
application/config/config.php
```

Set:

```php
$config['base_url'] = 'http://localhost/backend-task/';
```

---

### Step 4: Uploads Folder

Ensure there's an `uploads/` folder in your project root for storing uploaded images:

```bash
mkdir uploads
chmod 775 uploads
```

---

## Features

* AJAX-based create, edit, and delete operations
* Upload and preview featured images
* Support for multiple categories (comma-separated)
* Filter blog posts by date and category
* Clean MVC structure using CodeIgniter 3
* Automatic error handling and JSON responses

---

## Developer Notes

* Ensure `mod_rewrite` is enabled on Apache.
* The `.htaccess` file is pre-configured for clean URLs (without `index.php`).
* Category filtering uses `FIND_IN_SET()` to match single categories in a comma-separated string.

---

## Folder Structure

```
backend-task/
├── application/
│   ├── controllers/
│   ├── models/
│   ├── views/
│   └── config/
├── system/               # CodeIgniter core
├── uploads/              # Uploaded blog images
├── assets/               # Optional: custom CSS/JS
├── backend_task_db.sql   # SQL dump file
├── .htaccess
├── index.php
└── README.md
```

---

## Default DB Connection Settings

* **Host:** localhost
* **Username:** root
* **Password:** (empty)
* **Database:** backend\_task\_db

> If your environment differs, update `/application/config/database.php` accordingly.

---

## Author

**Ajirin**
Email: [mubarak@ajirinibi.com.ng](mailto:mubarak@ajirinibi.com.ng)
Website: [ajirinibi.com.ng](http://ajirinibi.com.ng)

---

## License

MIT License

