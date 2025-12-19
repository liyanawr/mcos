# mcos
Melati Chillz Ordering System

Here is the comprehensive, professional **README.md** for your GitHub repository. It includes the pre-installation requirements, the unique database features we implemented, and clear setup instructions.

---

# 🍔 Foodhub - Restaurant Management & Ordering System

Foodhub is a professional, full-stack PHP web application designed to streamline restaurant operations. It features a dual-interface system: a high-conversion ordering platform for **Customers** and a robust administrative dashboard for **Staff** and **Admins**.

## 📋 Prerequisites

Before you begin, ensure you have met the following requirements to run the environment locally:

* **Local Server**: Install **XAMPP**, **WAMP**, or **MAMP**.
* **PHP Version**: Ensure your local server is running **PHP 8.0** or higher.
* **Database**: **MySQL** (managed via phpMyAdmin).
* **Git**: Installed for repository cloning.
* **Write Permissions**: Ensure the `images/` directory is writable for food and category image uploads.

---

## 🚀 Key Features

### 👤 Customer Interface

* **Smart Menu**: Search for dishes and filter by category.
* **Order Tracking**: Real-time status updates for orders (Pending, On Delivery, Delivered).
* **User Profile**: Personal dashboard to manage contact details, dorm addresses, and bank information for payment verification.

### 👔 Admin & Staff Dashboard

* **Advanced Analytics**: Track Sales (Monthly/Yearly), view the top 2 best-selling items with images, and read live customer feedback.
* **Inventory Management**: Full CRUD support for food categories and menu items with image processing.
* **Work Management**:
* **Attendance Tracking**: Integration with `WORK_LOG` to monitor staff hours.
* **Employment Classification**: Differentiates between Full-Time (salary-based) and Part-Time (hourly-rated) staff.
* **Supervisory Chain**: Profiles display assigned supervisor details and employment status.


* **Order Fulfillment**: Master control to update delivery and payment statuses.

---

## 🛠️ Tech Stack

* **Backend**: PHP 8.x
* **Database**: MySQL
* **Frontend**: HTML5, CSS3 (Modern Flexbox/Card-based UI), Bootstrap
* **Security**: SQL Injection prevention using `mysqli_real_escape_string`.

---

## 📂 Database Schema

The system utilizes a relational schema including the following primary tables:

* `STAFF`, `CUSTOMER`, `MENU`, `CATEGORY`, `ORDER`, `WORK_LOG`, `FULL_TIME`, `PART_TIME`, `PAYMENT`, and `DELIVERY`.

---

## 📦 Installation

1. **Clone the Repo**:
```bash
git clone https://github.com/your-username/food-order.git

```


2. **Deploy to Server**:
Move the folder to your local server directory (e.g., `C:\xampp\htdocs\mcos`).

3. **Database Import**:
* Open **phpMyAdmin**.
* Create a database named `mcos`.
* Import the provided `.sql` file from the `/database` folder.

4. **Run**:
Visit `http://localhost/mcos/` in your browser.

---

## 📝 License

Distributed under the MIT License.

---

**Would you like me to help you create a `.gitignore` file so you don't accidentally upload your database password or XAMPP temporary files?**
