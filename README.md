# ⭐ PostEngine

A role-based blogging platform built with **PHP** + **Oracle DB**,
developed to sharpen backend fundamentals, authentication,
authorization, and full-stack structure.

```{=html}
<p align="center">
```

`<img src="https://img.shields.io/badge/PHP-8%2B-777BB4?style=flat&logo=php&logoColor=white" />`{=html}
`<img src="https://img.shields.io/badge/Oracle-Database-red?style=flat&logo=oracle" />`{=html}
`<img src="https://img.shields.io/badge/Status-In%20Development-yellow?style=flat" />`{=html}
`<img src="https://img.shields.io/badge/License-MIT-green?style=flat" />`{=html}

```{=html}
</p>
```

## 🎯 What is PostEngine?

A multi-role blogging system with secure login, admin workflows,
dashboards, and CRUD operations --- structured cleanly so you can
proudly say:

> "Yeah, I built that... and yes, the code is not living in one giant
> spaghetti index.php."

## 🚀 Features

### 🔐 Authentication & Authorization

- Login, signup (admin/user), logout\
- Session-based auth\
- Route protection via `auth.php`\
- Role-specific dashboards

### 🧩 RBAC (Role-Based Access Control)

- **Admin** → Approve users, manage posts, modify roles\
- **Moderator** → Review posts, moderate content\
- **Author** → Create/edit/delete posts\
- **Viewer** → Read published posts

### 📝 Post Management

- Create posts\
- Edit/update posts\
- Delete posts\
- View full post\
- Admin publish/unpublish toggle

### 👤 User Management

- Profile update\
- Password update\
- User listing + approval panel

### 🏗️ Clean Architecture

- Reusable components\
- Oracle database connection via `db.php`\
- Pages separated from logic\
- `.env` support

## 📂 Folder Structure

    PostEngine/
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
git clone https://github.com/miyaadshah/post-engine.git
cd PostEngine
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
