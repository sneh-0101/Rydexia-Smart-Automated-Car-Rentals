# 🚘 Rydexia – Car Rental Management System

A modern, responsive, and automated **Car Rental Management System** built using **PHP, HTML, CSS, and MySQL**.

This project is designed for a car rental startup named **Rydexia**, offering automated booking, vehicle management, customer handling, and rental tracking.

---

## 📌 Features

### 🔐 Admin Panel

* Admin login & authentication
* Dashboard with statistics (cars, bookings, customers, revenue)
* Add / Update / Delete cars
* Manage car availability
* View & manage customer bookings
* Approve or cancel rental requests
* Track rental history
* Generate revenue reports

### 🚘 Customer Module

* User registration & login
* Search available cars
* Car listing with images, price, details
* Book cars (with date & duration selection)
* View booking history
* Cancel booking (before approval)

---

## 🧑‍💻 Technology Used

* **Frontend:** HTML5, CSS3
* **Backend:** Core PHP
* **Database:** MySQL
* **Styling:** Modern UI/UX, responsive layout
* **Tools:** VS Code, XAMPP, phpMyAdmin

---

## 📂 Project Folder Structure

```
Rydexia/
│── index.php
│── about.php
│── contact.php
│── login.php
│── register.php
│── cars.php
│── booking.php
│── user-dashboard.php
│── admin/
│     ├── dashboard.php
│     ├── manage-cars.php
│     ├── manage-bookings.php
│     ├── add-car.php
│     ├── edit-car.php
│── assets/
│     ├── css/
│     ├── images/
│     ├── js/
│── database/
      └── rydexia.sql
```

---

## 🛢️ Database Overview

### **Tables**

* `admin`
* `users`
* `cars`
* `bookings`
* `rental_records`

### **Main Fields**

* Car: id, model, brand, price_per_day, image, status
* User: id, name, email, phone, password
* Booking: id, user_id, car_id, start_date, end_date, status

---

## 🚀 How to Run This Project

1. Install **XAMPP**
2. Move project folder into:

   ```
   C:/xampp/htdocs/
   ```
3. Start **Apache** & **MySQL**
4. Import `rydexia.sql` into phpMyAdmin
5. Run the project in browser:

   ```
   http://localhost/rydexia
   ```

---

## ✨ Company Name

**Rydexia – Smart Automated Car Rentals**

**Tagline:** *“Drive the Future with Smart Mobility.”*

---

## 📧 Developer

**Sneh Popat**
BCA Student | Developer | TheDigitalDreamCoder

---

If you need:
✅ Logo for Rydexia
✅ Database SQL file
✅ Admin dashboard UI
✅ Project Report PDF

Just tell me — I will generate everything.
