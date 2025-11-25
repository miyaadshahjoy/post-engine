# ⭐ PostEngine

A role-based blogging platform built with **PHP** + **Oracle DB**,
developed to sharpen backend fundamentals, authentication,
authorization, and full-stack structure.

## 🎯 What is PostEngine?

A multi-role blogging system with secure login, admin workflows,
dashboards, and CRUD operations for posts and users.

## 🚀 Features

### 🔐 Authentication & Authorization

- Login, signup (admin/user), logout
- Session-based auth
- Route protection via `auth.php`
- Role-specific dashboards

### 🧩 RBAC (Role-Based Access Control)

- **Admin** → Approve users, manage posts, modify roles\
- **Moderator** → Review posts, moderate content\
- **Author** → Create/edit/delete posts\
- **Viewer** → Read published posts

### 📝 Post Management

- Create posts
- Edit/update posts
- Delete posts
- View full post
- Admin publish/unpublish toggle

### 👤 User Management

- Profile update
- Password update
- User listing + approval panel

### 🏗️ Clean Architecture

- Reusable components
- Oracle database connection via `db.php`
- Pages separated from logic
- `.env` support

## 📷 Screenshots

![PREVIEW](/images/screenshots/ss-1.png)
![PREVIEW](/images/screenshots/ss-2.png)
![PREVIEW](/images/screenshots/ss-3.png)
![PREVIEW](/images/screenshots/ss-4.png)
![PREVIEW](/images/screenshots/ss-5.png)
![PREVIEW](/images/screenshots/ss-6.png)
![PREVIEW](/images/screenshots/ss-7.png)
![PREVIEW](/images/screenshots/ss-8.png)
![PREVIEW](/images/screenshots/ss-9.png)
![PREVIEW](/images/screenshots/ss-14.png)
![PREVIEW](/images/screenshots/ss-10.png)
![PREVIEW](/images/screenshots/ss-11.png)
![PREVIEW](/images/screenshots/ss-12.png)
![PREVIEW](/images/screenshots/ss-13.png)

## 📂 Folder Structure

    post-engine/
    |---- app/
    │     └── auth.php
    │
    ├── auth/
    │   ├── logout.php
    │   ├── signin.php
    │   ├── signup-admin.php
    │   ├── signup-user.php
    │   └── signup.php
    │
    ├── components/
    │   ├── admin/
    │   │   ├── approve-user.php
    │   │   ├── dashboard-overview.php
    │   │   ├── delete-user.php
    │   │   ├── feature.php
    │   │   ├── postslist.php
    │   │   ├── profile-setting.php
    │   │   ├── publish.php
    │   │   └── userslist.php
    │   │
    │   └── author/
    │   |   ├── dashboard-overview.php
    │   |   ├── postslist.php
    │   |   └── profile-setting.php
    |   |
    │   └── layout/
    │       ├── header.php
    │       ├── footer.php
    |
    │
    ├── config/
    │   └── db.php
    │
    ├── dashboard/
    │   ├── admin.php
    │   ├── author.php
    │   └── moderator.php
    │
    ├── DB Queries/
    │   └── CREATE TABLE ...
    │
    ├── images/
    │
    ├── pages/
    │   └── post.php
    │
    ├── posts/
    │   ├── create.php
    │   ├── update.php
    │   └── view.php
    │
    ├── public/
    │   ├── css/
    │   │   └── style.css
    │   └── js/
    │
    ├── users/
    │   ├── profile.php
    │   └── update-password.php
    │
    ├── .env
    ├── .env.example
    ├── .gitignore
    ├── index.php
    └── README.md

## ⚙️ Setup Instructions

### 1️⃣ Clone the repo

```bash
git clone https://github.com/miyaadshahjoy/post-engine.git
cd post-engine
```

### 2️⃣ Environment setup

Copy `.env` template:

```bash
cp .env.example .env
```

Update DB credentials:

    DB_HOST=localhost
    DB_PORT=1521
    DB_SERVICE=xe
    DB_USERNAME=your_username
    DB_PASSWORD=your_password

## 🛠️ Tech Stack

- **PHP 8+**
- **Oracle Database (with OCI8)**
- **HTML + CSS + Vanilla JS**
- Secure session-based auth
- Modular PHP components

## 🤝 Contributing

PRs are welcome.
If you're adding something spicy, create an issue first so we can argue about it constructively.

## 📄 License

MIT — use it, remix it, break it, rebuild it.
