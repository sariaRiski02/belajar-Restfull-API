Tentu! Berikut adalah README.md yang lebih terperinci dan berwarna untuk proyek Anda:

````markdown
# 🌐 User Management API Documentation

API ini dirancang untuk menyederhanakan pengelolaan pengguna, kontak, dan alamat dalam aplikasi Anda. Dengan API ini, Anda dapat dengan mudah:

### Manajemen Pengguna:

-   👤 Membuat, membaca, memperbarui, dan menghapus data pengguna (CRUD).
-   🔒 Autentikasi dan otorisasi pengguna.
-   🛡️ Mengelola peran dan izin pengguna.

### Manajemen Kontak:

-   📇 Menambahkan, melihat, mengedit, dan menghapus kontak pengguna.
-   📧 Mengelola informasi kontak seperti nama, email, nomor telepon, dll.

### Manajemen Alamat:

-   🏠 Menambahkan, melihat, mengedit, dan menghapus alamat pengguna.
-   🗺️ Mengelola informasi alamat seperti jalan, kota, negara, kode pos, dll.

## 🚀 Endpoints

### 🔑 Autentikasi

API ini menggunakan **Bearer Token**. Untuk mendapatkan token, kirim permintaan POST ke `/api/users/login` dengan kredensial yang valid. Sertakan token ini dalam header `Authorization` untuk setiap permintaan yang memerlukan autentikasi.

### Manajemen Pengguna

#### Register New User

**URL:** `/api/users`  
**Method:** `POST`  
**Description:** Register a new user.

**Request Body:**

```json
{
    "username": "string",
    "password": "string",
    "name": "string"
}
```
````

**Example Request:**

```json
{
    "username": "testUsername",
    "password": "testPassowrd123",
    "name": "testName"
}
```

**Responses:**

-   **201 Created**
    -   **Description:** User Registered Successfully
    -   **Example:**
        ```json
        {
            "data": {
                "id": 1,
                "username": "testUsername",
                "name": "testName"
            }
        }
        ```
-   **400 Bad Request**
    -   **Description:** Validation Error
    -   **Example:**
        ```json
        {
            "errors": {
                "username": [
                    "Username is required",
                    "username cannot contain spaces"
                ],
                "password": [
                    "Password is required",
                    "Password must be at least 6 characters long"
                ],
                "name": [
                    "Name is required",
                    "Name must be at least 3 characters long"
                ]
            }
        }
        ```

#### Login User

**URL:** `/api/users/login`  
**Method:** `POST`  
**Description:** Login a user.

**Request Body:**

```json
{
    "username": "string",
    "password": "string"
}
```

**Responses:**

-   **200 OK**
    -   **Description:** User logged in successfully
    -   **Example:**
        ```json
        {
            "data": {
                "id": 1,
                "username": "testUsername",
                "name": "testName",
                "token": "string"
            }
        }
        ```

#### Get Current User

**URL:** `/api/users/current`  
**Method:** `GET`  
**Description:** Get the current authenticated user.

**Headers:**

-   `Authorization`: Bearer token

**Responses:**

-   **200 OK**
    -   **Description:** User retrieved successfully
    -   **Example:**
        ```json
        {
            "data": {
                "id": 1,
                "username": "testUsername",
                "name": "testName"
            }
        }
        ```
-   **401 Unauthorized**
    -   **Description:** Unauthorized access

#### Update Current User

**URL:** `/api/users/current`  
**Method:** `PATCH`  
**Description:** Update the current authenticated user.

**Headers:**

-   `Authorization`: Bearer token

**Request Body:**

```json
{
    "name": "string",
    "password": "string"
}
```

**Responses:**

-   **200 OK**
    -   **Description:** User updated successfully
    -   **Example:**
        ```json
        {
            "data": {
                "id": 1,
                "username": "testUsername",
                "name": "testName"
            }
        }
        ```
-   **401 Unauthorized**
    -   **Description:** Unauthorized access

#### Logout User

**URL:** `/api/users/logout`  
**Method:** `DELETE`  
**Description:** Logout the current authenticated user.

**Headers:**

-   `Authorization`: Bearer token

**Responses:**

-   **200 OK**
    -   **Description:** User logged out successfully
    -   **Example:**
        ```json
        {
            "data": true
        }
        ```

### 📇 Manajemen Kontak

Detail dokumentasi untuk manajemen kontak dapat ditemukan di file `Docs/Contact-API.json`.

### 🏠 Manajemen Alamat

Detail dokumentasi untuk manajemen alamat dapat ditemukan di file `Docs/Address-API.json`.

## 📂 OpenAPI Specification

Dokumentasi lengkap dapat dilihat menggunakan OpenAPI Extension dan file JSON berikut:

1. `Docs/Address-API.json`
2. `Docs/Contact-API.json`
3. `Docs/User-API.json`

Gunakan ekstensi OpenAPI di IDE pilihan Anda untuk melihat dokumentasi API dengan lebih rinci.

---

Dengan dokumentasi ini, Anda memiliki semua yang Anda butuhkan untuk mulai bekerja dengan API Pengelolaan Pengguna, Kontak, dan Alamat dalam aplikasi Anda. 🚀

```

Anda dapat menambahkan atau mengubah bagian sesuai kebutuhan proyek Anda. Dokumentasi ini memberikan gambaran lengkap tentang API Anda dan cara penggunaannya, lengkap dengan contoh dan penjelasan.
```
