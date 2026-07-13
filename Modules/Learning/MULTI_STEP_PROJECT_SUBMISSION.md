# HỆ THỐNG NỘP PROJECT THEO NHIỀU BƯỚC (Multi-Step Project Submission)

## 📋 TỔNG QUAN

Hệ thống cho phép học viên nộp project theo quy trình nhiều bước với phê duyệt từng bước:

**Quy trình 5 bước chuẩn:**
1. **Phân tích yêu cầu** → Nộp file Word/Text phân tích
2. **Thiết kế Database** → Nộp file SQL hoặc diagram
3. **Thiết kế UI** → Nộp link Figma
4. **Code** → Nộp link GitHub
5. **Demo Video** → Nộp link YouTube hoặc upload video

**Đặc điểm:**
- Mỗi bước phải được Admin/CTV phê duyệt mới được làm bước tiếp theo
- Nếu bị reject, học viên phải làm lại bước đó (có giới hạn số lần)
- Sau khi hoàn thành tất cả bước, Admin/CTV chấm điểm cuối cùng

---

## 🗄️ DATABASE SCHEMA

### 1. `project_submission_steps`
Định nghĩa các bước nộp bài của project (Admin config)

**Cột quan trọng:**
- `project_id` - Project nào
- `step_name` - Tên bước (VD: "Phân tích yêu cầu")
- `step_order` - Thứ tự bước (1-5)
- `submission_type` - Loại nộp: `file`, `link`, hoặc `both`
- `allowed_file_types` - JSON array loại file cho phép
- `max_file_size_mb` - Kích thước file tối đa
- `max_resubmissions` - Số lần nộp lại tối đa

### 2. `project_step_submissions`
Lưu nội dung học viên nộp cho từng bước

**Cột quan trọng:**
- `project_submission_id` - Link đến submission tổng
- `step_id` - Bước nào đang nộp
- `user_id` - Học viên nộp
- `submission_number` - Lần nộp thứ mấy (1, 2, 3...)
- `file_path`, `file_name`, `file_size_kb` - Thông tin file upload
- `link_url` - Link nộp bài (Figma, GitHub, YouTube)
- `status` - Trạng thái: `draft`, `submitted`, `under_review`, `approved`, `rejected`
- `feedback` - Góp ý từ reviewer
- `is_current` - Có phải submission hiện tại không (latest)

### 3. `project_reviewers`
Phân công Admin/CTV chấm bài

**Cột quan trọng:**
- `project_id` - Project được phân công
- `user_id` - Reviewer (Admin/CTV)
- `assigned_by` - Người phân công
- `can_final_grade` - Có quyền chấm điểm cuối cùng không
- `reviews_count` - Số lượng đã review (cho load balancing)

### 4. `project_step_reviews`
Lịch sử review chi tiết

**Cột quan trọng:**
- `step_submission_id` - Submission nào được review
- `reviewer_id` - Người review
- `decision` - Quyết định: `approved` hoặc `rejected`
- `feedback` - Nhận xét chi tiết

### 5. `project_final_grades`
Điểm số và nhận xét cuối cùng

**Cột quan trọng:**
- `project_submission_id` - Submission tổng
- `user_id` - Học viên được chấm
- `score` - Điểm số (0-100)
- `grade_level` - Xếp loại: `excellent`, `good`, `average`, `poor`
- `feedback` - Nhận xét tổng quan
- `is_passed` - Có đạt yêu cầu không (score >= 50)

---

## 👥 WORKFLOW TỪNG VAI TRÒ

### 🔹 ADMIN

**1. Quản lý steps của project**
- URL: `/admin/learning/projects/{projectId}/steps`
- Component: `ManageProjectSteps`
- Chức năng:
  - Tạo các bước nộp bài cho project
  - Config loại nộp (file/link/both), file types, max size
  - Bật/tắt từng bước
  - Tạo nhanh 5 bước mặc định

**2. Phân công reviewer**
- Sử dụng `ProjectReviewService::assignReviewer()`
- Có thể assign nhiều reviewer cho 1 project
- Chỉ định reviewer nào có quyền chấm điểm cuối

**3. Chấm điểm cuối cùng**
- Sau khi tất cả bước approved
- Sử dụng `ProjectReviewService::submitFinalGrade()`
- Nhập điểm (0-100) + feedback

### 🔹 REVIEWER/CONTRIBUTOR

**1. Xem danh sách submissions cần review**
- URL: `/reviewer/submissions`
- Chỉ thấy submissions của projects mình được assign

**2. Review từng submission**
- URL: `/reviewer/submissions/{submissionId}/review`
- Component: `ReviewSubmission`
- Chức năng:
  - Xem file/link học viên nộp
  - Xem lịch sử submissions trước đó
  - Xem tiến độ học viên (đã làm được bước nào)
  - **Approve** hoặc **Reject** với feedback
  - Quick approve cho bài tốt

### 🔹 STUDENT (HỌC VIÊN)

**1. Nộp project theo từng bước**
- URL: `/student/projects/{projectId}/submit`
- Component: `SubmitProject`

**Quy trình:**
1. Xem progress bar (5 bước)
2. Làm bước hiện tại:
   - Upload file (nếu yêu cầu)
   - Nhập link (nếu yêu cầu)
   - Thêm ghi chú (tùy chọn)
   - Nhấn "Nộp bài"
3. Chờ giáo viên review
4. Nếu **approved** → Làm bước tiếp theo
5. Nếu **rejected** → Xem feedback và làm lại
6. Lặp lại cho đến khi hoàn thành tất cả bước
7. Chờ giáo viên chấm điểm cuối cùng

---

## 🛣️ ROUTES/API

### Admin Routes
```php
GET /admin/learning/projects/{projectId}/steps
// Quản lý steps của project
```

### Reviewer Routes
```php
GET /reviewer/submissions
// Danh sách submissions cần review

GET /reviewer/submissions/{submissionId}/review
// Review chi tiết một submission
```

### Student Routes
```php
GET /student/projects/{projectId}/submit
// Nộp project với multi-step workflow
```

---

## 📦 MODELS & SERVICES

### Models
- `ProjectSubmissionStep` - Định nghĩa bước
- `ProjectStepSubmission` - Nội dung nộp bài
- `ProjectReviewer` - Phân công reviewer
- `ProjectStepReview` - Lịch sử review
- `ProjectFinalGrade` - Điểm cuối cùng

### Services
- `ProjectSubmissionService` - Xử lý nộp bài từ học viên
  - `submitStep()` - Submit một bước
  - `isProjectCompleted()` - Kiểm tra hoàn thành chưa
  - `getNextStep()` - Lấy bước tiếp theo cần làm

- `ProjectReviewService` - Xử lý review từ Admin/CTV
  - `approveSubmission()` - Duyệt bài
  - `rejectSubmission()` - Từ chối với feedback
  - `assignReviewer()` - Phân công reviewer
  - `submitFinalGrade()` - Chấm điểm cuối

---

## 🚀 CÁCH SỬ DỤNG

### Bước 1: Admin tạo steps cho project

```php
// Truy cập: /admin/learning/projects/{id}/steps
// Nhấn "Tạo 5 bước mặc định" hoặc tạo từng bước thủ công
```

### Bước 2: Admin phân công reviewer

```php
use Modules\Learning\Services\ProjectReviewService;

$reviewService = new ProjectReviewService();
$reviewService->assignReviewer(
    projectId: 1,
    userId: 2, // ID của Admin/CTV
    assignedBy: 1, // ID của người phân công
    canFinalGrade: true // Có quyền chấm điểm cuối không
);
```

### Bước 3: Học viên nộp bài

```php
// Truy cập: /student/projects/{id}/submit
// Làm từng bước theo hướng dẫn
// Mỗi bước chờ phê duyệt mới được làm tiếp
```

### Bước 4: Reviewer chấm bài

```php
// Truy cập: /reviewer/submissions/{id}/review
// Xem bài nộp → Approve/Reject
```

### Bước 5: Admin chấm điểm cuối

```php
use Modules\Learning\Services\ProjectReviewService;

$reviewService = new ProjectReviewService();
$finalGrade = $reviewService->submitFinalGrade(
    projectSubmissionId: 1,
    reviewerId: 1,
    score: 85.5,
    feedback: 'Bài làm tốt, code clean, UI đẹp',
    stepScores: ['step1' => 90, 'step2' => 80, ...] // Optional
);
```

---

## ⚠️ LƯU Ý

1. **Thứ tự bước:** Học viên phải làm tuần tự, không được skip
2. **Số lần resubmit:** Có giới hạn (default 5 lần), admin có thể config
3. **File upload:** Kiểm tra loại file và kích thước trước khi nộp
4. **Reviewer permissions:** Chỉ reviewer được assign mới chấm được
5. **Final grade:** Chỉ được chấm khi tất cả bước đã approved

---

## 🎯 TÍNH NĂNG NỔI BẬT

✅ **Workflow từng bước rõ ràng** - Học viên biết mình đang ở đâu  
✅ **Feedback chi tiết** - Giáo viên góp ý từng bước  
✅ **Resubmit linh hoạt** - Cho phép nộp lại nhiều lần  
✅ **Load balancing** - Tự động assign reviewer ít việc nhất  
✅ **Progress tracking** - Theo dõi tiến độ real-time  
✅ **File + Link support** - Linh hoạt nhiều loại nộp bài  

---

**Developed by: OpenCode AI Assistant**  
**Date: 2026-07-08**
