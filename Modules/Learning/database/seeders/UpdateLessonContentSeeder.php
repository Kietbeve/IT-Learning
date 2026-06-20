<?php

namespace Modules\Learning\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Learning\Models\RoadmapLesson;

class UpdateLessonContentSeeder extends Seeder
{
    public function run(): void
    {
        // Update lesson 1
        RoadmapLesson::where('id', 1)->update([
            'content' => '<h2>Giới thiệu PHP</h2>
<p>PHP (Hypertext Preprocessor) là ngôn ngữ lập trình server-side phổ biến để xây dựng web động.</p>

<h3>Cài đặt môi trường</h3>
<ol>
<li>Cài đặt XAMPP (bao gồm Apache, PHP, MySQL)</li>
<li>Cài đặt Composer - công cụ quản lý dependencies</li>
<li>Cấu hình PHP extensions cần thiết</li>
</ol>

<h3>Syntax cơ bản</h3>
<pre><code>&lt;?php
echo "Hello World!";
$name = "Laravel";
echo "Welcome to " . $name;
?&gt;</code></pre>

<p>File PHP bắt đầu với <code>&lt;?php</code> và có thể kết thúc với <code>?&gt;</code></p>

<h3>Biến trong PHP</h3>
<p>Biến trong PHP bắt đầu bằng ký hiệu <code>$</code></p>
<pre><code>$age = 25;
$price = 99.99;
$isStudent = true;</code></pre>'
        ]);

        // Update lesson 2
        RoadmapLesson::where('id', 2)->update([
            'content' => '<h2>PHP Syntax và Variables</h2>
<p>Tìm hiểu các kiểu dữ liệu và cách khai báo biến trong PHP.</p>

<h3>Các kiểu dữ liệu</h3>
<ul>
<li><strong>String:</strong> Chuỗi ký tự</li>
<li><strong>Integer:</strong> Số nguyên</li>
<li><strong>Float:</strong> Số thực</li>
<li><strong>Boolean:</strong> true/false</li>
<li><strong>Array:</strong> Mảng</li>
</ul>

<h3>Ví dụ thực tế</h3>
<pre><code>$username = "admin";
$age = 25;
$salary = 5000.50;
$isActive = true;

$fruits = ["apple", "banana", "orange"];
echo $fruits[0]; // Output: apple</code></pre>

<h3>Toán tử</h3>
<p>PHP hỗ trợ các toán tử: +, -, *, /, %, ==, !=, <, >, &&, ||</p>'
        ]);

        // Update lesson 3
        RoadmapLesson::where('id', 3)->update([
            'content' => '<h2>Routes và Controllers trong Laravel</h2>
<p>Routing là cơ chế định tuyến URL đến các Controllers xử lý logic.</p>

<h3>1. Routes cơ bản</h3>
<p>File <code>routes/web.php</code> định nghĩa các routes:</p>
<pre><code>Route::get("/users", [UserController::class, "index"]);
Route::post("/users", [UserController::class, "store"]);
Route::get("/users/{id}", [UserController::class, "show"]);</code></pre>

<h3>2. Tạo Controller</h3>
<pre><code>php artisan make:controller UserController</code></pre>

<h3>3. Controller Methods</h3>
<pre><code>class UserController extends Controller {
    public function index() {
        $users = User::all();
        return view("users.index", compact("users"));
    }
    
    public function store(Request $request) {
        User::create($request->all());
        return redirect()->route("users.index");
    }
}</code></pre>

<p>Controllers giúp tách biệt logic xử lý khỏi routes.</p>'
        ]);

        $this->command->info('✅ Updated lesson content!');
    }
}
