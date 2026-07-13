<?php

namespace Modules\Learning\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Learning\Models\ForumThread;
use Modules\Learning\Models\ForumPost;
use Modules\Learning\Models\RoadmapLesson;
use App\Models\User;

class ForumSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            $this->command->warn('No users found, skipping forum seed');
            return;
        }

        $lessons = RoadmapLesson::limit(3)->get();
        if ($lessons->isEmpty()) {
            $this->command->warn('No lessons found, skipping forum seed');
            return;
        }

        $samples = [
            'Bài học này rất hay và bổ ích, cảm ơn tác giả!' => 'Cảm ơn bạn, hy vọng bạn sẽ tiếp tục theo dõi các bài học tiếp theo.',
            'Cho em hỏi phần này có tài liệu tham khảo thêm không ạ?' => 'Bạn có thể xem thêm ở phần Tài liệu trên trang chủ nhé!',
            'Em làm theo hướng dẫn mà bị lỗi ở bước 3, ai giúp em với ạ' => 'Bạn thử kiểm tra lại version PHP nhé, cần >= 8.1.',
        ];

        foreach ($lessons as $lesson) {
            $i = 0;
            foreach ($samples as $question => $answer) {
                $i++;
                $thread = ForumThread::create([
                    'user_id' => $user->id,
                    'title' => 'Câu hỏi #' . $i . ' về ' . $lesson->title,
                    'content' => $question,
                    'threadable_id' => $lesson->id,
                    'threadable_type' => RoadmapLesson::class,
                    'last_post_at' => now()->subHours(5 - $i),
                    'created_at' => now()->subDays($i),
                    'updated_at' => now()->subHours(5 - $i),
                ]);

                ForumPost::create([
                    'thread_id' => $thread->id,
                    'user_id' => $user->id,
                    'content' => $answer,
                    'is_best_answer' => true,
                    'created_at' => now()->subHours(5 - $i),
                    'updated_at' => now()->subHours(5 - $i),
                ]);
            }
        }

        $this->command->info('✅ Forum seeded: ' . ($lessons->count() * count($samples)) . ' threads with replies');
    }
}
