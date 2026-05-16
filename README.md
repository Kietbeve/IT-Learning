# IT-Learning

Nền tảng học tập trực tuyến dành cho cộng đồng học viên IT.

## Yêu cầu môi trường

Trước khi bắt đầu, hãy đảm bảo máy tính của bạn đã cài đặt:

- **PHP** >= 8.2
- **Composer** >= 2.0
- **Node.js** >= 20
- **MySQL** >= 8.0
- **Git**

## Hướng dẫn cài đặt

Thực hiện các bước sau để cấu hình project trên máy local:

### 1. Clone project từ Git

```bash
git clone <repository-url>
cd IT-Learning
```

### 2. Cài đặt các dependency PHP

```bash
composer install
```

### 3. Cấu hình file môi trường

```bash
cp .env.example .env
```

### 4. Tạo Application Key

```bash
php artisan key:generate
```

### 5. Cấu hình cơ sở dữ liệu

Mở file `.env` và cấu hình các thông số MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=it_learning
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Chạy database migration

```bash
php artisan migrate
```

### 7. Cài đặt các dependency Node.js

```bash
npm install
```

### 8. Build front-end assets (Development)

```bash
npm run dev
```

Hoặc build cho production:

```bash
npm run build
```

### 9. Khởi động Development Server

```bash
php artisan serve
```

Ứng dụng sẽ chạy tại: `http://127.0.0.1:8000`

## Thêm thông tin

Nếu gặp bất kỳ vấn đề nào trong quá trình cài đặt, vui lòng liên hệ với team hoặc tham khảo tài liệu chính thức của [Laravel](https://laravel.com/docs).

---

**Happy Coding! 🚀**
