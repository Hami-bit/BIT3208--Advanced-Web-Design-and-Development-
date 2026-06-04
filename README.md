#  NexaBank – Simple Banking System
## BIT3208 Advanced Web Design and Development

---

##  Project Structure

```
BIT3208_Project/
│
├── Week1/   → Environment setup, Hello World, basic DB test
├── Week2/   → Wireframes & GUI design (HTML/CSS layouts)
├── Week3/   → JavaScript validation & PHP syntax practice
├── Week4/   → Backend: PHP forms, sessions, login system
└── Week5/   → Database CRUD: deposits, withdrawals, transfers
```

---

##  Setup Instructions

### Requirements
- XAMPP / WAMP / Laragon (includes PHP 7.4+ & MySQL)
- Web browser

### Steps

1. **Copy the project** into your localhost root:
   - XAMPP → `C:/xampp/htdocs/BIT3208_BankingSystem/`
   - WAMP  → `C:/wamp64/www/BIT3208_BankingSystem/`

2. **Import the database** (PHPMyAdmin):
   - Open `http://localhost/phpmyadmin`
   - Create database: `week5db`
   - Import: `Week5/database/Week5db.sql`

3. **Open in browser**:
   - `http://localhost/BIT3208_BankingSystem/Week5/`

4. **Login with demo accounts**:

   | Username | Password | Role  |
   |----------|----------|-------|
   | admin    | password | Admin |
    | mike     | password | User  |
   | alice    | password | User  |

---

##  Features

| Feature              | Week  | Technology           |
|----------------------|-------|----------------------|
| Static HTML pages    | Wk 2  | HTML5 + CSS3         |
| Responsive layout    | Wk 2  | CSS Grid/Flexbox     |
| Form validation      | Wk 3  | JavaScript (ES6)     |
| User registration    | Wk 4  | PHP + MySQL          |
| Login / Logout       | Wk 4  | PHP Sessions         |
| Password hashing     | Wk 4  | `password_hash()`    |
| Deposit funds        | Wk 5  | CRUD – Create/Update |
| Withdraw funds       | Wk 5  | CRUD – Create/Update |
| Transfer between accounts | Wk 5 | CRUD – All ops  |
| Transaction history  | Wk 5  | CRUD – Read          |
| Pagination           | Wk 5  | SQL LIMIT/OFFSET     |

---

## 🗄️ Database Tables

| Table          | Purpose                          |
|----------------|----------------------------------|
| `users`        | Customer accounts                |
| `transactions` | All deposits/withdrawals/transfers |
| `transfers`    | Sender/receiver transfer records |

---

##  Weekly Deliverables Checklist

### Week 1
- [x] localhost installed and tested
- [x] Hello World page created
- [x] Basic database connectivity test

### Week 2
- [x] Wireframes designed
- [x] GUI layout implemented
- [x] Navigation bar created
- [x] Home page, Login page, Register page, Dashboard

### Week 3
- [x] JavaScript form validation
- [x] DOM manipulation (live balance preview)
- [x] PHP syntax practice file

### Week 4
- [x] Registration form with PHP processing
- [x] Login system with session management
- [x] Password hashing implemented
- [x] Protected dashboard page
- [x] Logout functionality

### Week 5
- [x] Full database created (users, transactions, transfers)
- [x] Deposit CRUD operation
- [x] Withdrawal CRUD operation
- [x] Transfer CRUD operation
- [x] Transaction history with pagination
- [x] SQL database export

---

##  Security Features
- Prepared statements (prevents SQL injection)
- `password_hash()` / `password_verify()` (no plain text passwords)
- `htmlspecialchars()` (prevents XSS)
- Session-based authentication
- Server-side validation + client-side validation

---

##  Technologies Used
- **Frontend**: HTML5, CSS3, JavaScript (ES6)
- **Backend**: PHP 8.x
- **Database**: MySQL (via MySQLi)
- **Server**: Apache (XAMPP/WAMP/Laragon)
- **Version Control**: Git / GitHub

---

*Advanced Web Design and Development – Banking System Project*
