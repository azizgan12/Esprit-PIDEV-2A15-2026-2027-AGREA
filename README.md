<div align="center">

<img src="assets/images/logo/AGREA.png" alt="AGREA logo" width="120">

# AGREA

**Grow smarter. Farm greener.**

A PHP web platform to manage farms, track tree types and connect an agricultural community.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?logo=mysql&logoColor=white)
![Architecture](https://img.shields.io/badge/architecture-MVC-38761D)

</div>

---

## Features

**For every user**
- Register with an account type (Farmer, Client, Employee, Supplier, Expert) and log in securely
- Profile page with picture upload (JPG, PNG, WEBP, max 2 MB) and editable details
- Community space (navigation ready for future posts and discussions)

**For farmers**
- Create, edit and delete farms (name, location, area in hectares)
- Record the tree types growing on each farm: type, quantity, area and planting year
- Add or edit all tree types of a farm from one dynamic form (1 to 50 types)

**For administrators**
- Dashboard with live user statistics per role
- Full user management: edit details and role, upload pictures, delete users
- Manage any user's farms and tree types

**UI/UX**
- Modern, responsive interface built on the AGREA green palette
- Animated login/register screen, collapsible mobile sidebar, animated stat counters
- Respects the user's reduced-motion setting

## Tech stack

| Layer | Technology |
|-------|------------|
| Backend | PHP 8 (PDO), no framework |
| Database | MySQL / MariaDB |
| Frontend | HTML, CSS (custom design system), vanilla JavaScript |
| Server | Apache (XAMPP) |

## Project structure

The project follows a simple MVC architecture with a single front controller.

```
AGREA/
├── index.php                 # Front controller / router (?action=...)
├── create_admin.php          # One-time admin account setup (CLI / localhost only)
├── config/
│   ├── database.php          # PDO connection
│   └── session.php           # Session setup + CSRF helpers
├── controllers/
│   ├── UserController.php    # Auth, profiles, user management
│   ├── FarmController.php    # Farm CRUD (farmer + admin)
│   └── TreeController.php    # Tree types per farm (farmer + admin)
├── models/
│   ├── User.php
│   ├── Farm.php
│   └── Tree.php
├── views/
│   ├── frontoffice/          # login, register, profile, farm and tree forms
│   ├── backoffice/           # admin dashboard, users, user farms and trees
│   ├── farms/                # farmer farm list and farm details
│   ├── community/
│   └── shared/               # sidebar, auth hero, tree form fields
├── database/migrations/      # SQL migrations
└── assets/
    ├── css/style.css         # Design system
    ├── js/app.js             # UI enhancements
    └── images/               # logo/ and profiles/ (uploads)
```

## Getting started

### Requirements
- PHP 8.0 or newer with the `pdo_mysql` and `fileinfo` extensions
- MySQL or MariaDB
- Apache (for example [XAMPP](https://www.apachefriends.org/))

### Installation

1. **Clone the repository** into your web root (for example `htdocs`):
   ```bash
   git clone https://github.com/<your-username>/AGREA.git
   ```

2. **Create the database** named `agrea` (utf8mb4) and the base tables (see [Database](#database)).

3. **Apply the migrations** from `database/migrations/` in order:
   ```
   2026_11_23_expand_user_roles.sql
   2026_11_23_create_trees_table.sql
   ```

4. **Configure the connection** in `config/database.php` if your credentials differ from the defaults:
   ```php
   private $host = "localhost";
   private $dbname = "agrea";
   private $username = "root";
   private $password = "";
   ```

5. **Add your logo** at `assets/images/logo/AGREA.png`.

6. **Make the upload folder writable**: `assets/images/profiles/`.

7. **Create the first administrator** from a terminal:
   ```bash
   php create_admin.php
   ```
   Default account: `admin@agrea.com` / `00000000`. **Change this password after your first login.**

8. **Open the app** at `http://localhost/AGREA/`. The login page is the default page.

## Database

Tables used by the application (reference schema, adjust types to your needs):

```sql
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    birth_date DATE NULL,
    country VARCHAR(100) NULL,
    phone_number VARCHAR(30) NULL,
    mail VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_picture VARCHAR(255) NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'farmer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE farms (
    farm_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    farm_name VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL,
    area_size DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

The `trees` table is created by `database/migrations/2026_11_23_create_trees_table.sql`.

## Roles

| Role | Access |
|------|--------|
| `farmer` | Profile, community, own farms and trees |
| `client`, `employee`, `supplier`, `expert` | Profile, community |
| `admin` | Everything above plus dashboard and user, farm and tree management |

## Routes

All pages go through `index.php?action=<name>`.

| Action | Description | Access |
|--------|-------------|--------|
| `login` (default), `register` | Authentication | Public |
| `profile`, `editProfile`, `logout` | Own account (admins see the dashboard on `profile`) | Logged in |
| `community` | Community space | Logged in |
| `farms`, `farm`, `saveFarm`, `deleteFarm` | Own farms | Farmer, Admin |
| `saveTree`, `deleteTree` | Own farm trees | Farmer, Admin |
| `users`, `editUser`, `deleteUser` | User management | Admin |
| `adminUserFarms`, `adminSaveFarm`, `adminDeleteFarm` | Manage a user's farms | Admin |
| `adminFarmTrees`, `adminSaveTrees`, `adminDeleteTree` | Manage a user's trees | Admin |

## Security

- Passwords hashed with `password_hash()` and verified with `password_verify()`
- Prepared statements (PDO) for all queries
- CSRF token required on every POST request, checked centrally in `index.php`
- Session cookies set to `HttpOnly` and `SameSite=Lax`, session ID regenerated at login
- Output escaped with `htmlspecialchars()`
- Uploads validated by real MIME type and size, stored under random file names
- Destructive actions (delete user, farm, tree) are POST-only
- `.htaccess` files deny direct web access to `config/`, `controllers/`, `models/`, `views/` and `database/`
- `create_admin.php` only runs from the command line or localhost

## Design

| Name | Hex |
|------|-----|
| Forest Green | `#1F4531` |
| Medium Green | `#38761D` |
| Light Green | `#85CF88` |
| White | `#FFFFFF` |

Typeface: [Plus Jakarta Sans](https://fonts.google.com/specimen/Plus+Jakarta+Sans), loaded from Google Fonts with system fonts as fallback.

## Roadmap

- Community posts and discussions
- Admin reports and platform settings (placeholders already on the dashboard)
- Login rate limiting and password reset

## Contributing

1. Fork the project
2. Create a feature branch: `git checkout -b feature/my-feature`
3. Commit your changes and push the branch
4. Open a pull request

## License

Add a license of your choice (for example MIT) before publishing.
