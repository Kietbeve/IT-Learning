<?php

namespace Modules\Document\database\seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class NewDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $contributor = User::role('contributor')->first();
        if (! $contributor) {
            $contributorId = 1;
        } else {
            $contributorId = $contributor->id;
        }

        $categories = [
            ['name' => 'Công nghệ thông tin', 'slug' => 'cntt', 'type' => 'document', 'is_active' => true],
            ['name' => 'Kinh tế', 'slug' => 'kinh-te', 'type' => 'document', 'is_active' => true],
            ['name' => 'Y học', 'slug' => 'y-hoc', 'type' => 'document', 'is_active' => true],
        ];

        $categoryIds = [];
        foreach ($categories as $cat) {
            $categoryIds[] = DB::table('categories')->insertGetId(array_merge($cat, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]));
        }

        $subjects = [
            ['name' => 'Lập trình web', 'slug' => 'lap-trinh-web', 'is_active' => true, 'category_id' => $categoryIds[0]],
            ['name' => 'Kế toán', 'slug' => 'ke-toan', 'is_active' => true, 'category_id' => $categoryIds[1]],
        ];
        $subjectIds = [];
        foreach ($subjects as $sub) {
            $subjectIds[] = DB::table('subjects')->insertGetId(array_merge($sub, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]));
        }

        for ($i = 1; $i <= 5; $i++) {
            $categoryId = $categoryIds[array_rand($categoryIds)];
            $subjectId = $subjectIds[array_rand($subjectIds)];
            $price = rand(0, 1) ? 0 : rand(10, 50) * 10000;

            $docId = DB::table('documents')->insertGetId([
                'public_id' => 'DOC-'.rand(10000, 99999),
                'author_id' => $contributorId,
                'slug' => 'tai-lieu-mau-'.$i,
                'status' => 'approved',
                'download_count' => rand(10, 100),
                'favorite_count' => rand(1, 50),
                'view_count' => rand(100, 500),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $versionId = DB::table('document_versions')->insertGetId([
                'document_id' => $docId,
                'version_number' => '1.0',
                'title' => 'Tài liệu mẫu '.$i,
                'short_description' => 'Mô tả ngắn gọn cho tài liệu '.$i,
                'description' => '<p>Mô tả chi tiết cho tài liệu '.$i.'</p>',
                'category_id' => $categoryId,
                'subject_id' => $subjectId,
                'file_original_path' => 'documents/sample.pdf',
                'file_watermarked_path' => 'documents/sample_watermarked.pdf',
                'file_type' => 'pdf',
                'file_size' => 1024 * 1024 * rand(1, 5),
                'visibility' => 'public',
                'is_downloadable' => true,
                'watermark_status' => 'completed',
                'price' => $price,
                'status' => 'approved',
                'submitted_by' => $contributorId,
                'submitted_at' => Carbon::now()->subDays(rand(1, 10)),
                'reviewed_at' => Carbon::now()->subDays(rand(0, 1)),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            DB::table('documents')->where('id', $docId)->update(['current_version_id' => $versionId]);
        }
    }
}
