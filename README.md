# Postcode

## Project Overview

This project is an API for managing stores and retrieving store information. It includes endpoints for creating stores, retrieving stores near a specified location, and getting stores delivering to a specified location. The project is built using PHP and Laravel, with a frontend for adding stores.

## Installation

To set up the project locally, follow these steps:

1. **Clone the repository:**
   ```sh
   git clone <repository-url>
   cd <repository-directory>
   ```

2. **Install PHP dependencies:**
   ```sh
   composer install --prefer-dist --no-progress
   ```

3. **Set up the environment:**
   Copy the `.env.example` file to `.env` and configure your environment variables as needed.
   ```sh
   cp .env.example .env
   ```

4. **Generate application key:**
   ```sh
   php artisan key:generate
   ```

5. **Run database migrations and seeders:**
   ```sh
   php artisan migrate --seed
   ```

8. **Start the development server:**
   ```sh
   php artisan serve
   ```

## Usage

### API Endpoints

The API provides the following endpoints:

- **Create a new store:**
    - **Endpoint:** `POST /api/stores`
    - **Request Body:**
      ```json
      {
        "name": "string",
        "coords": {
          "latitude": "number",
          "longitude": "number"
        },
        "status": "string",
        "type": "string",
        "max_delivery_distance": "integer"
      }
      ```
    - **Response:**
      ```json
      {
        "message": "Store created successfully",
        "store": {
          "name": "string",
          "coords": {
            "latitude": "number",
            "longitude": "number"
          },
          "status": "string",
          "type": "string",
          "max_delivery_distance": "integer"
        }
      }
      ```

- **Get stores near a specified location:**
    - **Endpoint:** `GET /api/stores/near`
    - **Query Parameters:**
        - `latitude`: number (required)
        - `longitude`: number (required)
        - `radius`: number (required)
    - **Response:**
      ```json
      {
        "stores": [
          {
            "name": "string",
            "coords": {
              "latitude": "number",
              "longitude": "number"
            },
            "status": "string",
            "type": "string",
            "max_delivery_distance": "integer"
          }
        ]
      }
      ```

- **Get stores delivering to a specified location:**
    - **Endpoint:** `GET /api/stores/delivering`
    - **Query Parameters:**
        - `latitude`: number (required)
        - `longitude`: number (required)
    - **Response:**
      ```json
      {
        "stores": [
          {
            "name": "string",
            "coords": {
              "latitude": "number",
              "longitude": "number"
            },
            "status": "string",
            "type": "string",
            "max_delivery_distance": "integer"
          }
        ]
      }
      ```

### Using the OpenAPI File

The `openapi.yaml` file defines the API specification for this project. You can use this file to generate API documentation or client SDKs.

1. **View API Documentation:**
    - Use an online tool like [Swagger Editor](https://editor.swagger.io/) to view the API documentation.
    - Upload the `openapi.yaml` file to the Swagger Editor to see the API endpoints and their details.

2. **Generate Client SDKs:**
    - Use tools like [OpenAPI Generator](https://openapi-generator.tech/) to generate client SDKs in various programming languages.
    - Example command to generate a PHP client SDK:
      ```sh
      openapi-generator-cli generate -i openapi.yaml -g php -o ./client-sdk/php
      ```

## Running Tests

To run the unit tests, use the following command:
```sh
php artisan test
```

The tests are configured to use an in-memory SQLite database for testing purposes, as specified in the `phpunit.xml` file.

## Seeders

The project includes a seeder to populate the database with sample store data. To run the seeder, use the following command:
```sh
php artisan db:seed --class=StoreSeeder
```

## Postcode Download Command

To download and update postcode data, use the following command:
```sh
php artisan app:fetch-postcode-data
```
