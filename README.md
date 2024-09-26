# Blog Application APIs

## Description

This is a simple blog application built with Laravel. The application provides RESTful APIs for managing users, posts, and comments, allowing users to perform CRUD operations. It includes features for user authentication and role management.

## Features

- User registration and login with Laravel Passport
- CRUD operations for posts and comments
- Search and filter functionality for posts
- Middleware for authentication and role-based access

## Technologies Used

- PHP
- Laravel
- MySQL
- Composer
- Postman (for testing APIs)

## Installation

1. Clone the repository:
   
   git clone https://github.com/YeshanPasindu/BlogAPIs.git

2. Navigate to the project directory:

    cd BlogAPIs

3. Install dependencies

    composer install

4. Create a copy of the environment file

    cp .env.example .env

5. Generate the application key

    php artisan key:generate

6. Set up your database in the .env file

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_username
    DB_PASSWORD=your_password

7. Run migrations

    php artisan migrate

8. Start the local development server

    php artisan serve
