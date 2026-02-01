# Real Estate Listing Platform

A PHP & MySQL web application where real estate agents can manage property listings with full CRUD operations, image uploads, search, and Ajax live filtering. This project is implemented for Task 2: Full Site Implementation. [file:1][file:2]

---

## 1. Project overview

This system allows agents to:

- Create new property listings with images.
- View all properties in a responsive card-based layout.
- Update existing property details and images.
- Delete properties with confirmation.
- Search properties by location, price range, and house type.
- Use Ajax-based live filtering without full page reload. [file:1][file:2]

The application is built using PHP for backend logic and MySQL as the database, as required in the assignment. [file:1]

---

## 2. Technologies used

- **Backend**: PHP (PDO, prepared statements) [file:1]
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, vanilla JavaScript (Fetch API)
- **Server environment**: Student server / XAMPP / similar LAMP stack [file:1]

---

## 3. Features implemented

### 3.1 CRUD for properties

Each property record supports full CRUD operations: [file:1][file:2]

- **Create**: `add.php`  
  - Adds new property with title, description, location, price, house type, and optional image upload.
- **Read**: `index.php`  
  - Displays all properties in a semantic card layout with image, details, and description.
- **Update**: `edit.php`  
  - Edits existing property details and allows changing the image.
- **Delete**: `delete.php`  
  - Deletes a property and its associated image file (if present) with confirmation on the frontend.

All database operations use prepared statements to protect against SQL injection. [file:1]

### 3.2 Search and filtering

- Search form on `index.php` with fields:
  - Location (text, partial match using `LIKE`)
  - Minimum price
  - Maximum price
  - House type (Apartment, House, Land) [file:2]
- Server-side filtering is applied to the SQL query using optional WHERE conditions. [file:1]

### 3.3 Ajax live filtering

- JavaScript (in `assets/js/app.js`) listens to changes in the search form fields. [file:1]
- Sends a GET request to `ajax_filter.php` with current filter values using the Fetch API.
- `ajax_filter.php` returns updated property cards HTML only.
- The results section (`#propertiesContainer`) is updated without reloading the entire page. [file:1][file:2]

This satisfies the assignment’s requirement for at least one useful Ajax feature (fetching data without page reload). [file:1]

### 3.4 Image upload

- Image field in `add.php` and optional update in `edit.php`.
- File validation in `handleImageUpload()`:
  - Only JPEG/PNG images.
  - Maximum size: 2 MB.
  - Files are stored in `assets/images/` with unique names.
- On delete, the corresponding image file is removed from the filesystem. [file:1]

### 3.5 Security measures

The project implements basic security practices mentioned in Task2: [file:1]

- **SQL Injection prevention**:  
  - All database queries use PDO prepared statements for SELECT, INSERT, UPDATE, and DELETE.
- **XSS prevention**:  
  - All output from the database and user input is escaped using a helper function `h()` which wraps `htmlspecialchars(...)`.
- **Server-side validation**:  
  - `validatePropertyData()` in `functions.php` validates:
    - Title (required)
    - Description (required)
    - Location (required)
    - Price (required, numeric, positive)
    - House type (must be one of the defined types)
- **File upload validation**:  
  - MIME type checking with `mime_content_type`.
  - File size limit.
  - Restriction to specific image formats. [file:1]

CSRF protection is not implemented but could be added as an enhancement. [file:1]

---

## 4. Project structure

Recommended folder structure following Task2: [file:1]

```text
projectroot/
├── config/
│   └── db.php
├── includes/
│   ├── functions.php
│   ├── header.php
│   └── footer.php
├── public/
│   ├── index.php
│   ├── add.php
│   ├── edit.php
│   ├── delete.php
│   └── ajax_filter.php
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── app.js
│   └── images/
│       └── (uploaded images)
└── README.md

config/db.php: Database connection using PDO. [file:1]

includes/functions.php: Helper functions: escape (h()), house types, image upload, validation.

includes/header.php & includes/footer.php: Shared layout and semantic structure.

public/index.php: Main page with list + search + Ajax container.

public/add.php: Add property form.

public/edit.php: Edit property form.

public/delete.php: Delete handler.

public/ajax_filter.php: Ajax endpoint for live property filtering.

assets/css/style.css: Styling with responsive layout and media queries.

assets/js/app.js: Ajax logic for fetching filtered results.

5. Database setup
Create database

Create a database, e.g. realestate_db, in phpMyAdmin or via SQL. [file:1]

Create table

Run the provided SQL in phpMyAdmin:
CREATE TABLE properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    house_type VARCHAR(50) NOT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

Configure db.php

Open config/db.php and update:
$host = 'localhost';
$db   = 'realestate_db'; // your database name
$user = 'root';          // student server / local user
$pass = '';              // password if required


Test connection

Visit public/index.php in the browser.

If connection fails, a “Database connection error” message is shown.

6. How to run the project
Copy project

Upload the project folder to the student server (e.g. inside public_html or the location your instructor gave). [file:1]

Or place it in htdocs (XAMPP) or www (WAMP/LAMP) for local testing.

Setup database

Create the database and table as described in the previous section.

Configure database credentials

Edit config/db.php according to your server settings.

Open in browser

URL example on local XAMPP:
http://localhost/realestate_project/public/index.php

URL example on student server: according to your assigned domain/path. [file:1]

Add sample data

Use the “Add property” page to insert several properties with:

Different locations (Kathmandu, Lalitpur, Bhaktapur, etc.)

Different price ranges (e.g. 12000, 35000, 8000000, 22000000)

Different house types (Apartment, House, Land)

7. Usage guide
Home / listings (index.php)

View all properties as cards.

Use the filter form to search by location, price range, and house type.

Ajax live filtering will automatically refresh the list as you type/change filters.

Add property (add.php)

Fill in title, description, location, price, and house type.

Optionally upload an image.

Submit to create a new record.

Edit property (edit.php)

Access via the “Edit” button on a card.

Change any field and optionally upload a new image.

Submit to update the record.

Delete property (delete.php)

Click “Delete” on a card.

Confirm deletion in the browser dialog.

Record and image file are removed.

8. Security and validation notes
All input is validated on the server using validatePropertyData() before inserting or updating records.

All output is escaped with h() to prevent XSS when rendering content.

All SQL statements use prepared statements to avoid SQL injection.

File upload is restricted by size and type, and stored outside the public root of public/ (under assets/images/ in the project root).

9. Known issues / limitations
No CSRF tokens are implemented for forms (optional enhancement).

There is no user authentication or role management; any visitor can manage properties.

No pagination on the list page; all properties are loaded at once (can be added as an improvement).

10. Login credentials (if needed)
If the project is later extended with authentication, document credentials here. For this implementation, there is no login and all pages are publicly accessible.
Login: not implemented in this version
