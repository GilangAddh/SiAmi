### **Prasyarat**

-   PHP >= 8.1
-   Composer
-   Node.js & NPM
-   PostgreSQL

### **Langkah-langkah Instalasi**

1. **Salin File Konfigurasi**

    ```bash
    cp .env.example .env
    ```

2. **Instal Dependensi**

    - Instal dependensi PHP menggunakan Composer:
        ```bash
        composer install
        ```
    - Instal dependensi frontend menggunakan NPM:
        ```bash
        npm install
        ```

3. **Generate Key dan Link Storage**

    ```bash
    php artisan key:generate
    ```

    ```bash
    php artisan storage:link
    ```

4. **Konfigurasi Database**

    - Buka file `.env` dan sesuaikan konfigurasi database:
        ```plaintext
        DB_CONNECTION=pgsql
        DB_HOST=127.0.0.1
        DB_PORT=[db_port]
        DB_DATABASE=[db_name] -> siami_poltekkes
        DB_USERNAME=[db_username]
        DB_PASSWORD=[db_password]
        ```

5. **Migrasi dan Seed Database**

    ```bash
    php artisan migrate
    ```

    ```bash
    php artisan db:seed
    ```

6. **Kompilasi Asset Frontend**

    ```bash
    npm run dev
    ```

7. **Jalankan Aplikasi**

    ```bash
    php artisan serve
    ```

    Aplikasi akan berjalan di `http://localhost:8000`.
