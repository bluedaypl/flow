# OrderFlow

![OrderFlow Logo](public/images/logo.svg)

OrderFlow is a modern order management system designed to streamline the process of handling orders.

## Features

- Order Management & Tracking
- Real-time Order Status Updates
- Reporting & Analytics
- User Role Management

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL/PostgreSQL
- Node.js & NPM

## Installation

1. Clone the repository
2. Install dependencies using Composer
3. Set up your environment variables
4. Run the migrations

### Installation in Dokku

For installation instructions, refer to [Dokku's official installation guide](https://dokku.com/docs/getting-started/installation/).

### Install MySQL Plugin

Refer to the [Dokku MySQL plugin](https://github.com/dokku/dokku-mysql).

### Setup in Dokku

1. Create a new Dokku app:
    ```bash
    dokku apps:create orderflow
    ```

2. Configure MySQL database:
    ```bash
    dokku mysql:create orderflow-db
    dokku mysql:link orderflow-db orderflow
    ```

3. Set environment variables:
    ```bash
    dokku config:set orderflow APP_KEY=$(php artisan key:generate --show)
    dokku config:set orderflow APP_ENV=production
    ```

4. Set up buildpacks:
    ```bash
    dokku buildpacks:add orderflow https://github.com/heroku/heroku-buildpack-nodejs
    dokku buildpacks:add orderflow https://github.com/heroku/heroku-buildpack-php
    ```

5. Deploy the application:
    ```bash
    git remote add dokku dokku@your-server:orderflow
    git push dokku main
    ```
