# Todo API
A simple Todo CRUD api built with PHP Laravel

## Project Setup
1. **Create .env File**: Create a file in the root directory and name it `.env`. Copy the content from the `.env.example` and paste into `.env`, and save it.

2. **Install Composer Dependencies**: Run the command

    ```bash
    composer install
    ```

3. **Create Database**: Create a database (MySql DB preferably) and name it exactly as specified in the `.env` file "todo_api".

4. **Migrate Database**: Run the following command to populate your database with the necessary tables:

    ```bash
    php artisan migrate
    ```

## Tests
1. Execute the command below to run unit/feature tests

    ```bash
    php artisan test
    ```

For test coverage, you can locate (and open in a browser) the `index.html` file in the `\coverage-report` folder in the root directory

## Run Project
1. Run the following commands in two separate terminals

    ```bash
    php artisan serve
    ```

## API docs
To access and test the API endpoints, paste this url in the browser `{{ baseurl }}/api/documentation`
