public function run(): void
{
    $roadmaps = [
        ['title' => 'Lập trình Backend chuyên sâu với Laravel', 'slug' => 'backend-laravel', 'status' => 'active', 'level' => 'beginner'],
        ['title' => 'Làm chủ Frontend với ReactJS', 'slug' => 'frontend-react', 'status' => 'active', 'level' => 'beginner'],
        ['title' => 'Fullstack Developer với Next.js', 'slug' => 'fullstack-nextjs', 'status' => 'active', 'level' => 'advanced'],
        ['title' => 'Cấu trúc dữ liệu và giải thuật', 'slug' => 'dsa-foundation', 'status' => 'active', 'level' => 'beginner'],
        ['title' => 'Hệ thống Microservices với Go', 'slug' => 'go-microservices', 'status' => 'active', 'level' => 'advanced'],
        ['title' => 'DevOps và CI/CD thực chiến', 'slug' => 'devops-cicd', 'status' => 'active', 'level' => 'advanced'],
    ];

    foreach ($roadmaps as $data) {
        Roadmap::updateOrCreate(['slug' => $data['slug']], $data);
    }
}