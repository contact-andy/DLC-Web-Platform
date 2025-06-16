# Digital Literacy Content Development Project : Web Platform

## Overview

The **Digital Literacy Content Development Project** is designed to bridge the gap between content creators and end-users by offering a seamless platform to manage and access digital literacy resources.

This repository contains the **web form** component of the system, which includes an administrative interface. It is primarily used by content managers and administrators to oversee the entire digital content lifecycle.

### Key Features

- Administrative dashboard for managing:
  - Employees
  - Customers
  - Content categories
  - Digital content (photos, videos)
- Secure user authentication
- Organized and easily accessible content structure
- Local storage integration for media content

---

## Development Platform

- **Framework:** Laravel (PHP-based web application framework)
- **Database:** MySQL
- **Authentication:** Laravel’s built-in secure user authentication system
- **File Storage:** Local storage integration for managing category photos and video content
- **Hosting Environment:** Compatible with XAMPP, WAMP, or cloud-based hosting platforms

---

## Getting Started

### Prerequisites

- PHP >= 8.1
- Composer
- MySQL
- Laravel CLI
- Web server (Apache/Nginx via XAMPP/WAMP or any cloud-based environment)

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/contact-andy/DLC-Web-Platform.git

2. Navigate to the project directory:
    ```bash
    cd DLC-Web-Platform

3. Install dependencies:
    ```bash
    composer install

4. Copy the .env.example file to .env and configure your environment:
    ```bash
    cp .env.example .env

5. Generate application key:
    ```bash
    php artisan key:generate

6. Run database migrations:
    ```bash
    php artisan migrate

7. Start the development server:
    ```bash
    php artisan serve
