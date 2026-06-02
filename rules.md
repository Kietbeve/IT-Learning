# IT-Learning UI & Design System Rules

Tài liệu quy chuẩn UI Component & Design System cho dự án IT-Learning.

## 1. Design Principles (Nguyên tắc thiết kế)

**Mục đích**: Đảm bảo sự nhất quán, thân thiện và hiệu năng cao cho toàn bộ hệ thống.

*   **Consistency (Nhất quán)**: Cùng một loại element phải sử dụng cùng một style xuyên suốt toàn bộ hệ thống.
*   **Accessibility (Tiếp cận)**: Độ tương phản (Contrast ratio) phải đạt chuẩn WCAG AA. Hỗ trợ đầy đủ keyboard navigation.
*   **Responsive (Thích ứng)**: Thiết kế theo hướng Mobile First — đảm bảo tương thích trên mọi kích thước màn hình từ 320px đến 1920px.
*   **Performance (Hiệu năng)**: Áp dụng Lazy load cho hình ảnh, purge CSS (TailwindCSS) và tối ưu Vite bundle.

## 2. Color System (Hệ màu sắc)

**Mục đích**: Quy định màu sắc chuẩn thông qua CSS Variables/Tailwind classes, dùng đúng ngữ cảnh.

**Quy tắc**: Sử dụng các utility class của Tailwind hoặc biến CSS đã được định nghĩa.
*   **Primary**: Dành cho hành động chính.
*   **Success**: Dành cho thông báo thành công.
*   **Warning**: Dành cho cảnh báo.
*   **Danger**: Dành cho lỗi, hành động xóa dữ liệu nguy hiểm.
*   **Info**: Dành cho thông báo thông tin chung.
*   **Surface**: Nền cho card, modal, dropdown.

✅ **Đúng**: `class="text-primary-600 bg-surface"`
❌ **Sai**: `class="text-[#3b82f6] bg-white"` (Sử dụng mã màu hardcode)

## 3. Typography System (Hệ chữ)

**Mục đích**: Đảm bảo cấu trúc văn bản rõ ràng, phân cấp tốt và dễ đọc.

| Element | Font size | Font weight | Mô tả | Class Tailwind (Gợi ý) |
| :--- | :--- | :--- | :--- | :--- |
| **H1** | 36px | 700 | Tiêu đề trang chính | `text-4xl font-bold` |
| **H2** | 28px | 700 | Tiêu đề section | `text-3xl font-bold` |
| **H3** | 22px | 600 | Tiêu đề card/block | `text-2xl font-semibold` |
| **H4** | 18px | 600 | Tiêu đề nhỏ | `text-lg font-semibold` |
| **Body** | 16px | 400 | Văn bản thông thường | `text-base font-normal` |
| **Small** | 14px | 400 | Chú thích, metadata | `text-sm font-normal` |
| **Tiny** | 12px | 400 | Badge, label phụ | `text-xs font-normal` |

✅ **Đúng**: `<h1 class="text-4xl font-bold">Danh sách khóa học</h1>`
❌ **Sai**: `<h1 style="font-size: 36px; font-weight: 700;">Danh sách khóa học</h1>`

## 4. Layout Rules (Quy tắc Bố cục)

**Mục đích**: Thống nhất cấu trúc hiển thị nội dung trên các thiết bị.

**Quy tắc Layout tổng thể**:
*   **Mobile**: Sidebar ẩn, sử dụng Menu dạng Drawer. Nội dung hiển thị dạng Grid 1 cột.
*   **Tablet**: Sidebar dạng Toggle. Nội dung hiển thị dạng Grid 2 cột.
*   **Desktop**: Sidebar cố định. Nội dung hiển thị dạng Grid 3 cột.
*   **Wide**: Nội dung hiển thị dạng Grid 4 cột.

## 5. Responsive Rules (Quy tắc Responsive)

**Mục đích**: Đảm bảo hiển thị hoàn hảo từ điện thoại tới màn hình lớn dựa trên Mobile First.

**Breakpoints chuẩn**:
| Breakpoint | Prefix Tailwind | Áp dụng cho |
| :--- | :--- | :--- |
| **Mobile** | (default) | Layout 1 cột, sidebar ẩn |
| **Tablet** | `md:` (≥ 768px) | Layout 2 cột, sidebar toggle |
| **Desktop** | `lg:` (≥ 1024px)| Layout đầy đủ, sidebar cố định |
| **Wide** | `xl:` (≥ 1280px)| Grid 4 cột cho tài liệu |

**Quy tắc Grid tài liệu (Danh sách khóa học, bài viết...)**:
✅ **Đúng**:
```html
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <!-- Items -->
</div>
```
❌ **Sai**: Không sử dụng responsive prefix khiến giao diện vỡ trên di động:
`<div class="grid grid-cols-4 gap-6">`

## 6. Blade Component Standards (Tiêu chuẩn Blade Component)

**Mục đích**: Tái sử dụng UI elements, đồng bộ với thư viện mặc định.

**Quy tắc**:
*   Sử dụng Blade Component của WireUI cho các thành phần cơ bản (Button, Badge, Input, Select, Modal...).
*   Với component custom, phải thiết kế linh hoạt, nhận attributes qua `$attributes`.

✅ **Đúng** (Dùng WireUI button):
```html
<x-button primary label="Lưu thay đổi" />
```
❌ **Sai** (Tự viết lại bằng HTML thuần):
```html
<button class="bg-blue-600 text-white px-4 py-2 rounded">Lưu thay đổi</button>
```

## 7. Livewire Component Standards (Tiêu chuẩn Livewire Component)

**Mục đích**: Xử lý logic động và tương tác người dùng.

**Quy tắc**:
*   Sử dụng Livewire cho các xử lý logic phức tạp, gọi database, form submit thay vì Javascript/Ajax thuần.
*   Sử dụng Alpine.js chỉ cho các UI interaction đơn giản phía client (như toggle dropdown, modal nội bộ đơn giản) không cần tương tác server.

✅ **Đúng**: `<form wire:submit="save">`
❌ **Sai**: Dùng jQuery hoặc Vanilla JS để `fetch()` gửi form.

## 8. Form & Validation Rules (Quy tắc Form và Xác thực)

**Mục đích**: Đảm bảo form nhập liệu thống nhất, báo lỗi rõ ràng.

**Quy tắc**:
*   Label luôn hiển thị phía trên input.
*   Trường bắt buộc phải có dấu `*`.
*   Thông báo lỗi hiển thị ngay bên dưới input.

✅ **Đúng**:
```html
<div>
    <label class="block font-medium text-sm text-gray-700">Email <span class="text-red-500">*</span></label>
    <x-input wire:model="email" />
    @error('email')
        <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
    @enderror
</div>
```
❌ **Sai**: Placeholder thay cho label, lỗi hiện chung một cục ở trên cùng.

## 9. Empty State Rules (Quy tắc Trạng thái Rỗng)

**Mục đích**: Không để người dùng hoang mang khi không có dữ liệu.

**Quy tắc**:
Mọi danh sách rỗng (table, grid) KHÔNG được để trống trơn mà phải hiển thị Empty State thống nhất gồm:
1. Icon minh họa
2. Tiêu đề
3. Mô tả
4. Nút hành động (Ví dụ: Thêm mới)

✅ **Đúng**:
```html
<div class="text-center py-10">
    <x-icon name="document-text" class="w-12 h-12 mx-auto text-gray-400" />
    <h3 class="mt-2 text-sm font-semibold text-gray-900">Không có dữ liệu</h3>
    <p class="mt-1 text-sm text-gray-500">Bắt đầu bằng cách tạo tài liệu mới.</p>
    <div class="mt-6">
        <x-button primary label="Tạo mới" icon="plus" wire:click="create" />
    </div>
</div>
```
❌ **Sai**: Bảng hiển thị `<tr><td>Không có dữ liệu</td></tr>` sơ sài.

## 10. Loading State Rules (Quy tắc Trạng thái Tải)

**Mục đích**: Thông báo cho người dùng hệ thống đang xử lý.

**Quy tắc**:
*   **Toàn trang / Component lớn** (Dashboard, Báo cáo): Sử dụng `<x-loading-page />` hoặc component loading tương đương.
*   **Hành động cụ thể** (Nút lưu, submit form): Sử dụng tính năng loading tích hợp của WireUI button (`wire:loading`).

✅ **Đúng**:
```html
<!-- Nút có loading state -->
<x-button primary wire:click="save" wire:target="save">
    <span wire:loading.remove wire:target="save">Lưu lại</span>
    <span wire:loading wire:target="save">Đang lưu...</span>
</x-button>
```

## 11. Table & PowerGrid Rules (Quy tắc Bảng dữ liệu)

**Mục đích**: Hiển thị dữ liệu dạng danh sách nhất quán.

**Quy tắc**:
*   **Bắt buộc** sử dụng thư viện **PowerGrid** làm thư viện bảng dữ liệu mặc định cho hệ thống Admin/Dashboard.
*   Chỉ dùng bảng HTML thuần cho các danh sách cực kỳ đơn giản (dưới 5 dòng, không filter, không sort).

✅ **Đúng**: Tạo component kế thừa từ `PowerGridComponent`.
❌ **Sai**: Tự viết vòng lặp `@foreach` tạo table thủ công kèm tính năng sort, paginate bằng tay.

## 12. Modal Rules (Quy tắc Modal)

**Mục đích**: Sử dụng hộp thoại đúng ngữ cảnh.

**Quy tắc**:
*   Sử dụng Modal của WireUI.
*   Modal phải có tiêu đề rõ ràng, nút "Đóng" (hoặc icon X) và các nút hành động đặt ở góc dưới cùng bên phải.
*   Không lồng Modal vào trong Modal (hạn chế tối đa).

## 13. Notification Rules (Quy tắc Thông báo)

**Mục đích**: Thông báo trạng thái hoạt động một cách thống nhất.

**Quy tắc**:
*   Bắt buộc sử dụng Toast Notification của WireUI.
*   Phải có `title` và `description` rõ ràng.

✅ **Đúng** (Trong Livewire component):
```php
$this->notification()->success(
    title: 'Thành công',
    description: 'Dữ liệu đã được lưu thành công'
);
```
❌ **Sai**: Sử dụng `alert()` của Javascript hoặc tự viết thông báo flash message thuần.

## 14. Accessibility Rules (Quy tắc Tiếp cận - A11y)

**Mục đích**: Hỗ trợ mọi đối tượng người dùng.

**Quy tắc**:
*   Contrast ratio chuẩn WCAG AA.
*   Các thẻ input luôn có `id` và liên kết với `label` qua `for`.
*   Hỗ trợ di chuyển bằng bàn phím (tabindex hợp lý).
*   Thẻ hình ảnh phải có thuộc tính `alt`.

## 15. Dark Mode Compatibility (Quy tắc Dark Mode)

**Mục đích**: Giao diện hỗ trợ chuẩn chế độ tối nếu yêu cầu.

**Quy tắc**:
*   Luôn sử dụng utility classes của Tailwind với prefix `dark:` cho các màu sắc nền, văn bản nếu hệ thống yêu cầu hỗ trợ Dark Mode.
*   Tránh sử dụng màu tĩnh khó nhìn khi chuyển mode.

## 16. Naming Convention (Quy tắc Đặt tên)

**Mục đích**: Đồng bộ trong code base.

**Quy tắc**:
*   **Blade Component**: Kebab-case. (VD: `user-profile.blade.php`)
*   **Livewire Component**:
    *   Class: PascalCase (VD: `UserProfile.php`)
    *   View: Kebab-case (VD: `user-profile.blade.php`)
*   **CSS Classes (Tailwind custom)**: Kebab-case.

## 17. Anti-patterns (Những điều bị cấm)

**Mục đích**: Tránh code rác, khó bảo trì.

1.  🚫 **CẤM** sử dụng Inline CSS (`style="..."`) ngoại trừ trường hợp binding width/height động không thể dùng class.
2.  🚫 **CẤM** viết custom CSS trong `<style>` khi Tailwind đã hỗ trợ class tương đương.
3.  🚫 **CẤM** dùng jQuery. Dùng Livewire hoặc Alpine.js thay thế.
4.  🚫 **CẤM** bỏ qua Error state trong Form.
5.  🚫 **CẤM** hiển thị danh sách rỗng mà không có Empty State.

---
*Tài liệu này là quy chuẩn bắt buộc cho mọi lập trình viên khi tham gia phát triển dự án.*
