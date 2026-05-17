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

## Luồng xử lý trong module

Trong dự án Laravel 11 dùng `nwidart/laravel-modules`, mỗi module hoạt động theo luồng chính như sau:

Route
↓
Request
↓
Controller
↓
Policy (nếu cần quyền)
↓
Service
↓
Model
↓
Database

### Giải thích từng thành phần

- `routes/api.php`:
  - định nghĩa endpoint API, nhận request từ client, và ánh xạ đến controller.

- `Http/Requests`:
  - validate dữ liệu đầu vào trước khi xử lý, đảm bảo request hợp lệ.

- `Http/Controllers`:
  - nhận request, gọi service xử lý nghiệp vụ, và trả response về client.

- `Policies`:
  - kiểm tra quyền người dùng trước khi thực hiện hành động, chỉ dùng khi cần phân quyền.

- `Services`:
  - xử lý logic nghiệp vụ chính, tách riêng phần business logic khỏi controller.

- `Models`:
  - làm việc với database qua Eloquent, truy vấn và lưu dữ liệu.

- `database`:
  - nơi lưu dữ liệu thật, bao gồm migration và seeder nếu cần.

### Ví dụ thực tế với module Auth

Trong module `Auth`, luồng xử lý thường như sau:

- `routes/api.php` → định nghĩa route login/register/logout.
- `Http/Requests/LoginRequest.php` → validate dữ liệu login.
- `Http/Controllers/AuthController.php` → nhận request, gọi service và trả response.
- `Policies/UserPolicy.php` → phân quyền nếu action yêu cầu kiểm tra quyền.
- `Services/AuthService.php` → xử lý login/logout, mã hóa mật khẩu, tạo token.
- `Models/User.php` → truy vấn bảng `users` và tương tác với database.

### Vai trò các thư mục/thành phần trong module

- `routes/api.php`:
  - nơi định nghĩa endpoint API và nhóm route cho module.

- `Http/Requests`:
  - chứa các lớp validate dữ liệu đầu vào, giúp controller chỉ nhận một request đã kiểm tra.

- `Http/Controllers`:
  - chứa controller receive request và điều phối sang service.

- `Policies`:
  - chứa các lớp kiểm tra quyền truy cập, áp dụng trước khi thực hiện hành động.

- `Services`:
  - chứa business logic chính của module, làm nhiệm vụ xử lý nghiệp vụ.

- `Models`:
  - định nghĩa các thực thể Eloquent, xử lý query database.

- `database`:
  - chứa migration, seed hoặc dữ liệu liên quan đến module, thực thi lưu trữ dữ liệu.

## Nguyên tắc code

- Controller giữ mỏng, chỉ chịu trách nhiệm nhận request và trả response.
- Business logic đặt ở `Service`.
- Validate ở `Request`.
- Query DB qua `Model`.
- `Policy` chỉ dùng khi có phân quyền.

## Các lệnh tạo nhanh module

Trong dự án này, một số lệnh artisan dùng để tạo nhanh module và các thành phần là:

```bash
php artisan module:make Auth

php artisan module:make-request LoginRequest Auth
php artisan module:make-policy UserPolicy Auth
php artisan module:make-model User Auth
```

---

**Happy Coding! 🚀**
