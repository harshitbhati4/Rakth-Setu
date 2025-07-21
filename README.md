# 🩸 Rakth-Setu: Bridging Donors and Recipients

![Rakth-Setu Banner](assets/rakthsetu-banner.gif)

## Overview

**Rakth-Setu (राक्त-सेतु, "Blood Bridge")** is a dynamic platform connecting blood donors with those in urgent need. We simplify and accelerate the blood donation process with an easy-to-use interface for donors and recipients, creating a life-saving community.

---

## ✨ Features

| Feature                      | Description                                                   |
|------------------------------|---------------------------------------------------------------|
| **Donor Registration**       | Effortless & secure donor registration                        |
| **Blood Request Management** | Quick requests for blood by group, quantity & location        |
| **Search & Filter**          | Find donors/requests by blood group, location & availability  |
| **User Authentication**      | Secure login & registration                                   |
| **Admin Panel (Coming Soon)**| Admin dashboard for management                                |
| **Responsive Design**        | Optimized for all devices                                     |

---


## 🛠️ Technologies Used

**Frontend:**  
- HTML5  
- CSS3  
- JavaScript

**Backend:**  
- PHP

**Database:**  
- MySQL

---

## 💻 Installation & Setup

Follow these steps to set up Rakth-Setu locally:

1. **Clone the Repository**
    ```
    git clone https://github.com/harshitbhati4/Rakth-Setu.git
    ```

2. **Navigate to Directory**
    ```
    cd Rakth-Setu
    ```

3. **Set Up Local Server Environment**
    - Use XAMPP, WAMP, or MAMP (PHP & MySQL support required).

4. **Create the MySQL Database**
    - Access phpMyAdmin.
    - Create a new database (e.g., `rakth_setu`).

5. **Import the Database Schema**
    - Locate `database/rakth_setu.sql`.
    - Import it into the new database.

6. **Configure Database Connection**
    - Edit `connection.php` (or `config.php` if present).
    - Update with your DB credentials:
      ```
      $servername = "localhost";
      $username = "root"; // Your MySQL username
      $password = ""; // Your MySQL password
      $dbname = "rakth_setu";
      $conn = new mysqli($servername, $username, $password, $dbname);
      if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
      }
      ```

7. **Move Project Files**
    - Place all files in the web server's document root (`htdocs` for XAMPP, `www` for WAMP).

8. **Access the Application**
    - Visit: `http://localhost/Rakth-Setu/`

---

## 🤝 Contributing

1. Fork the repo  
2. Create a branch:  
 ```
  git push origin feature/my-feature
```
3. Make and commit changes  
4. Push branch:  
 ```
  git push origin feature/my-feature
```
5. Open a Pull Request explaining your changes

---

## 📄 License

MIT License. See [`LICENSE`](https://github.com/harshitbhati4/Rakth-Setu/blob/main/LICENSE) for details.

---

## 📞 Contact

Questions or suggestions?  
Harshit Bhati: [GitHub](https://github.com/harshitbhati4)

---

> _“Together, let’s bridge the gap and save lives.”_
