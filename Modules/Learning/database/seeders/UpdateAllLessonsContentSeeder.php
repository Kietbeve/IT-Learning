<?php

namespace Modules\Learning\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Learning\Models\RoadmapLesson;

class UpdateAllLessonsContentSeeder extends Seeder
{
    public function run(): void
    {
        // LESSON 4: HTML5 Semantic Tags
        RoadmapLesson::where('id', 4)->update([
            'content' => '<h2>HTML5 Semantic Tags</h2>
<p>HTML5 cung cấp các thẻ semantic giúp cấu trúc trang web rõ ràng và tốt cho SEO.</p>

<h3>Các thẻ semantic quan trọng</h3>
<ul>
<li><code>&lt;header&gt;</code> - Phần đầu trang</li>
<li><code>&lt;nav&gt;</code> - Menu điều hướng</li>
<li><code>&lt;main&gt;</code> - Nội dung chính</li>
<li><code>&lt;article&gt;</code> - Bài viết độc lập</li>
<li><code>&lt;section&gt;</code> - Phân đoạn nội dung</li>
<li><code>&lt;aside&gt;</code> - Nội dung phụ (sidebar)</li>
<li><code>&lt;footer&gt;</code> - Chân trang</li>
</ul>

<h3>Ví dụ cấu trúc trang</h3>
<pre><code>&lt;!DOCTYPE html&gt;
&lt;html lang="vi"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;title&gt;Website Demo&lt;/title&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;header&gt;
        &lt;h1&gt;Website của tôi&lt;/h1&gt;
        &lt;nav&gt;
            &lt;a href="#home"&gt;Trang chủ&lt;/a&gt;
            &lt;a href="#about"&gt;Giới thiệu&lt;/a&gt;
        &lt;/nav&gt;
    &lt;/header&gt;
    
    &lt;main&gt;
        &lt;article&gt;
            &lt;h2&gt;Tiêu đề bài viết&lt;/h2&gt;
            &lt;p&gt;Nội dung bài viết...&lt;/p&gt;
        &lt;/article&gt;
    &lt;/main&gt;
    
    &lt;footer&gt;
        &lt;p&gt;© 2026 IT-Learning&lt;/p&gt;
    &lt;/footer&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>

<h3>Lợi ích của Semantic HTML</h3>
<p>✓ Tốt cho SEO (Search Engine Optimization)</p>
<p>✓ Dễ đọc và maintain code</p>
<p>✓ Hỗ trợ Accessibility cho người khuyết tật</p>'
        ]);

        // LESSON 5: CSS Flexbox và Grid
        RoadmapLesson::where('id', 5)->update([
            'content' => '<h2>CSS Flexbox và Grid Layout</h2>
<p>Hai công cụ mạnh mẽ để tạo layout responsive trong CSS hiện đại.</p>

<h3>1. CSS Flexbox</h3>
<p>Flexbox giúp sắp xếp elements theo hàng hoặc cột một cách linh hoạt.</p>

<h4>Thuộc tính Container:</h4>
<pre><code>.container {
    display: flex;
    justify-content: center; /* căn giữa ngang */
    align-items: center;     /* căn giữa dọc */
    gap: 20px;              /* khoảng cách giữa items */
}</code></pre>

<h4>Thuộc tính Items:</h4>
<pre><code>.item {
    flex: 1;           /* chiếm đều không gian */
    flex-basis: 200px; /* kích thước ban đầu */
}</code></pre>

<h3>2. CSS Grid</h3>
<p>Grid Layout tạo layout 2 chiều (hàng và cột) phức tạp.</p>

<pre><code>.grid-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 3 cột bằng nhau */
    grid-gap: 20px;
}

.grid-item {
    grid-column: span 2; /* item chiếm 2 cột */
}</code></pre>

<h3>Khi nào dùng gì?</h3>
<p><strong>Flexbox:</strong> Layout 1 chiều (navbar, card list)</p>
<p><strong>Grid:</strong> Layout 2 chiều phức tạp (dashboard, gallery)</p>

<h3>Ví dụ thực tế: Card Layout</h3>
<pre><code>&lt;div class="cards"&gt;
    &lt;div class="card"&gt;Card 1&lt;/div&gt;
    &lt;div class="card"&gt;Card 2&lt;/div&gt;
    &lt;div class="card"&gt;Card 3&lt;/div&gt;
&lt;/div&gt;

&lt;style&gt;
.cards {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}
.card {
    flex: 1 1 300px; /* grow, shrink, basis */
    padding: 20px;
    background: #f0f0f0;
}
&lt;/style&gt;</code></pre>'
        ]);

        // LESSON 6: JavaScript Basics và DOM
        RoadmapLesson::where('id', 6)->update([
            'content' => '<h2>JavaScript Basics và DOM Manipulation</h2>
<p>JavaScript cho phép tương tác động với HTML elements thông qua DOM (Document Object Model).</p>

<h3>1. Lấy Elements từ DOM</h3>
<pre><code>// Lấy element theo ID
const title = document.getElementById("title");

// Lấy elements theo class
const buttons = document.querySelectorAll(".btn");

// Lấy element đầu tiên khớp selector
const firstButton = document.querySelector(".btn");</code></pre>

<h3>2. Thao tác với Content</h3>
<pre><code>// Thay đổi text
title.textContent = "Tiêu đề mới";

// Thay đổi HTML
title.innerHTML = "&lt;strong&gt;Tiêu đề in đậm&lt;/strong&gt;";

// Thay đổi attribute
const image = document.querySelector("img");
image.src = "new-image.jpg";
image.alt = "Mô tả mới";</code></pre>

<h3>3. Thay đổi Style CSS</h3>
<pre><code>const box = document.querySelector(".box");
box.style.backgroundColor = "blue";
box.style.width = "200px";
box.style.display = "none"; // ẩn element</code></pre>

<h3>4. Event Handling (Xử lý sự kiện)</h3>
<pre><code>const button = document.querySelector("#myButton");

// Click event
button.addEventListener("click", function() {
    alert("Button được click!");
});

// Input event
const input = document.querySelector("#username");
input.addEventListener("input", function(e) {
    console.log("Giá trị nhập:", e.target.value);
});</code></pre>

<h3>5. Tạo và Xóa Elements</h3>
<pre><code>// Tạo element mới
const newDiv = document.createElement("div");
newDiv.textContent = "Div mới";
newDiv.className = "box";

// Thêm vào DOM
document.body.appendChild(newDiv);

// Xóa element
const oldElement = document.querySelector(".old");
oldElement.remove();</code></pre>

<h3>Ví dụ thực tế: Todo List</h3>
<pre><code>const addButton = document.querySelector("#add");
const input = document.querySelector("#task");
const list = document.querySelector("#todoList");

addButton.addEventListener("click", function() {
    const task = input.value;
    if (task) {
        const li = document.createElement("li");
        li.textContent = task;
        list.appendChild(li);
        input.value = ""; // clear input
    }
});</code></pre>'
        ]);

        // LESSON 7: ES6+ Modern JavaScript
        RoadmapLesson::where('id', 7)->update([
            'content' => '<h2>ES6+ Modern JavaScript Features</h2>
<p>Các tính năng JavaScript hiện đại giúp code ngắn gọn và dễ đọc hơn.</p>

<h3>1. Arrow Functions</h3>
<pre><code>// Cách cũ
function add(a, b) {
    return a + b;
}

// Arrow function
const add = (a, b) => a + b;

// Với nhiều dòng
const greet = (name) => {
    const message = `Hello ${name}!`;
    return message;
};</code></pre>

<h3>2. Template Literals</h3>
<pre><code>const name = "Huy";
const age = 25;

// Cách cũ
const message = "Tên: " + name + ", Tuổi: " + age;

// Template literal
const message = `Tên: ${name}, Tuổi: ${age}`;

// Multi-line
const html = `
    &lt;div&gt;
        &lt;h1&gt;${name}&lt;/h1&gt;
        &lt;p&gt;${age} tuổi&lt;/p&gt;
    &lt;/div&gt;
`;</code></pre>

<h3>3. Destructuring</h3>
<pre><code>// Array destructuring
const colors = ["red", "green", "blue"];
const [first, second] = colors;
console.log(first); // "red"

// Object destructuring
const user = { name: "Huy", age: 25, city: "HCM" };
const { name, age } = user;
console.log(name); // "Huy"</code></pre>

<h3>4. Spread Operator (...)</h3>
<pre><code>// Copy array
const arr1 = [1, 2, 3];
const arr2 = [...arr1, 4, 5]; // [1,2,3,4,5]

// Merge objects
const obj1 = { a: 1, b: 2 };
const obj2 = { ...obj1, c: 3 }; // {a:1, b:2, c:3}

// Function arguments
const numbers = [5, 10, 15];
Math.max(...numbers); // 15</code></pre>

<h3>5. Async/Await</h3>
<pre><code>// Fetch API với async/await
async function getUsers() {
    try {
        const response = await fetch("/api/users");
        const users = await response.json();
        console.log(users);
    } catch (error) {
        console.error("Lỗi:", error);
    }
}

getUsers();</code></pre>

<h3>6. Array Methods</h3>
<pre><code>const numbers = [1, 2, 3, 4, 5];

// map: transform array
const doubled = numbers.map(n => n * 2); // [2,4,6,8,10]

// filter: lọc elements
const evens = numbers.filter(n => n % 2 === 0); // [2,4]

// reduce: tính tổng
const sum = numbers.reduce((acc, n) => acc + n, 0); // 15

// find: tìm element đầu tiên
const found = numbers.find(n => n > 3); // 4</code></pre>'
        ]);

        $this->command->info('✅ Updated all 7 lessons content!');
    }
}
