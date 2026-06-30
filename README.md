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

DB_CHARSET=utf8mb3 # Tùy chỉnh database trên máy
DB_COLLATION=utf8mb3_unicode_ci # Tùy chỉnh database trên máy
```

### 6. Chạy database migration và seeder

Chạy lệnh sau để tạo cấu trúc bảng:

```bash
php artisan migrate
```

Chạy lệnh sau để nạp dữ liệu mẫu (seeders):

```bash
php artisan db:seed
```

Hoặc bạn có thể chạy đồng thời cả hai lệnh bằng:

```bash
php artisan migrate --seed
```

_(Lưu ý: Đối với các module riêng lẻ, bạn cũng có thể chạy seeder của riêng module đó bằng lệnh `php artisan module:seed <ModuleName>`)_

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

## Deploy demo lên Render

Project này đã có sẵn `Dockerfile` và `docker/entrypoint.sh`, nên cách deploy ổn nhất trên Render là dùng **Web Service -> Docker**.

### 1. Cách tạo service trên Render

1. Vào Render Dashboard
2. Chọn **New +** -> **Web Service**
3. Kết nối repository GitHub/GitLab
4. Chọn:
   - **Environment**: `Docker`
   - **Branch**: `main` hoặc nhánh bạn muốn deploy
   - **Region**: gần người dùng nhất

### 2. Build command và Start command

Nếu bạn chọn **Docker** trên Render thì:

- **Build Command**: để trống
- **Start Command**: để trống

Render sẽ tự build image từ `Dockerfile` và chạy `ENTRYPOINT` trong container.

Nếu bạn muốn deploy theo kiểu **Native Web Service** thay vì Docker, có thể dùng:

```bash
Build Command: composer install --no-dev --optimize-autoloader && npm ci && npm run build
Start Command: php artisan serve --host=0.0.0.0 --port=$PORT
```

Khuyến nghị vẫn là dùng Docker vì repo đã tối ưu sẵn cho kiểu deploy này.

### 3. Các ENV cần khai báo trên Render

Vào tab **Environment** của service và khai báo các biến sau:

```env
APP_NAME=IT-Learning
APP_ENV=production
APP_KEY=base64:your_app_key_here
APP_DEBUG=false
APP_URL=https://your-app-name.onrender.com
APP_TIMEZONE=Asia/Ho_Chi_Minh

DB_CONNECTION=mysql
DB_HOST=your-mysql-host
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_CONNECTION=sync
CACHE_STORE=database
FILESYSTEM_DISK=local

GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=https://your-app-name.onrender.com/google/callback

PAYOS_CLIENT_ID=your_payos_client_id
PAYOS_API_KEY=your_payos_api_key
PAYOS_CHECKSUM_KEY=your_payos_checksum_key
PAYOS_RETURN_URL=https://your-app-name.onrender.com/payment/return
PAYOS_CANCEL_URL=https://your-app-name.onrender.com/payment/cancel

PLATFORM_FEE_PERCENT=10
```

### 4. APP_KEY

Nếu chưa có `APP_KEY`, tạo local bằng:

```bash
php artisan key:generate --show
```

Copy toàn bộ output và dán vào biến `APP_KEY` trên Render.

### 5. Lưu ý quan trọng khi deploy

- `APP_URL` phải là domain thật của Render, không dùng `localhost`
- `GOOGLE_REDIRECT_URI`, `PAYOS_RETURN_URL`, `PAYOS_CANCEL_URL` cũng phải đổi sang domain Render
- Nếu dùng database riêng, nhớ mở quyền kết nối từ Render
- Nếu muốn seed dữ liệu lúc khởi động container, đặt thêm:

```env
RUN_SEEDERS=true
```

### 6. Migrate và seed

Container đã tự chạy migrate trong `docker/entrypoint.sh`.
Nếu muốn nạp seed data, bật `RUN_SEEDERS=true`.

### 7. Tóm tắt nhanh cho Render

- **Environment**: `Docker`
- **Build Command**: để trống
- **Start Command**: để trống
- **APP_ENV**: `production`
- **APP_DEBUG**: `false`
- **APP_URL**: domain Render
- **DB_***: trỏ về MySQL thật

### 8. Nếu Render báo lỗi

- `No application encryption key`: thiếu `APP_KEY`
- `Database connection failed`: sai `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`
- `Vite manifest not found`: kiểm tra Docker build có chạy `npm run build`
- `Permission denied`: kiểm tra quyền thư mục `storage` và `bootstrap/cache`

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
