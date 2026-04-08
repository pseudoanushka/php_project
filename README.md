# LifeTrack: Personal Decision Analyzer

A creative, beginner-friendly PHP web application that tracks your daily lifestyle decisions and categorizes your behavior into "Healthy", "Risky", or "Chaotic". 

## Objective
This project demonstrates basic and intermediate PHP concepts in a full-stack environment without the use of frameworks, while providing a clean and engaging user interface.

## Prerequisites
- PHP 7.4 or higher
- PDO extension for SQLite (or MySQL)
- GD extension (optional, for the dynamic score card images)
- A local web server (like Apache, Nginx, or the built-in PHP development server)

## Setup & Running Locally

Since this app includes a fallback to SQLite, you don't even need a separate MySQL database server to test it out!

### Option 1: Using the built-in PHP Web Server
1. Clone the repository or download the source code.
2. Open your terminal/command prompt.
3. Navigate to the project directory:
   ```bash
   cd path/to/php_project
   ```
4. Start the built-in PHP server:
   ```bash
   php -S localhost:8000
   ```
5. Open your web browser and go to `http://localhost:8000/index.php`.

### Option 2: Using XAMPP/WAMP/LAMP
1. Copy the project folder into your `htdocs` (XAMPP) or `www` (WAMP) directory.
2. Start Apache (and MySQL if you configure it to use MySQL instead of SQLite).
3. Access the project via `http://localhost/php_project/index.php`.

## Features
- **Daily Logging:** Log your sleep, study, exercise, junk food, water, and screen time.
- **Dynamic Scoring System:** Calculates a score (0-100) and categorizes your day as Healthy, Risky, or Chaotic.
- **Fun Mode:** Provides sarcastic, AI-like feedback based on your habits.
- **Dashboard:** Visualizes your score trends, habit breakdown, and category distribution.
- **History:** View a timeline and tabular data of all past entries.
- **Image Generation:** Automatically creates a downloadable "Score Card" image using the PHP GD library (if enabled).

## Technical Demonstrations
This application covers the following PHP concepts:
1. **Variables & Data Types:** Integers, strings, floats, booleans, and arrays.
2. **Conditional Logic:** `if-else` and `switch` statements for scoring and categorization.
3. **Loops:** `for`, `while`, and `foreach` loops used extensively for displaying entries and generating charts.
4. **Arrays:** Indexed and multi-dimensional associative arrays for scoring rules and sarcastic feedback templates.
5. **String Functions:** `strtoupper()`, `strtolower()`, `ucfirst()`, `strlen()`, `substr()`, and more for text formatting.
6. **Functions:** User-defined functions for business logic and anonymous functions (closures) for UI formatting.
7. **Form Handling:** Secure POST submission and input validation (`filter_var()`, sanitization).
8. **Database Interaction:** Full CRUD (Create, Read, Update, Delete) using PDO prepared statements.
9. **Image Processing:** Using the GD library to dynamically draw text, shapes, and colors onto a PNG template.

## Configuration
By default, the application uses **SQLite** and stores the database file in `data/lifetrack.db`. 

To use **MySQL** instead:
1. Open `db.php`.
2. Change `$use_sqlite = true;` to `$use_sqlite = false;`.
3. Update the `$db_host`, `$db_name`, `$db_user`, and `$db_pass` variables with your MySQL credentials.
4. The script will automatically attempt to create the necessary tables structure.

## UI / UX
- Custom premium dark mode design system (Vanilla CSS).
- Glassmorphism effects, CSS grid/flexbox layouts, and micro-animations.
- Fully responsive on mobile devices.

## Troubleshooting
- **No Score Card Image?** Ensure the `gd` extension is enabled in your `php.ini`. Look for `;extension=gd` and remove the semicolon.
- **Database Errors?** If using SQLite, ensure the script has write permissions to create the `data/` directory.

---
*Built as a creative demonstration of PHP fundamentals.*
