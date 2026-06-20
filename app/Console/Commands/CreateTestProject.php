<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Learning\Models\Project;
use Modules\Learning\Models\RoadmapLesson;
use Modules\Learning\Models\Roadmap;

class CreateTestProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:create-test {roadmap_id=4}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test project lesson for testing submission feature';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $roadmapId = $this->argument('roadmap_id');
        
        // Check if roadmap exists
        $roadmap = Roadmap::find($roadmapId);
        if (!$roadmap) {
            $this->error("Roadmap ID {$roadmapId} not found!");
            return 1;
        }
        
        $this->info("Creating test project for roadmap: {$roadmap->title}");
        
        // Create a project
        $project = Project::create([
            'roadmap_id' => $roadmapId,
            'section_id' => null,
            'title' => 'Capstone Project: Build a Complete Web Application',
            'description' => '<h2>Project Requirements</h2>
<p>Build a full-stack web application using the technologies learned in this roadmap.</p>
<h3>Features to implement:</h3>
<ul>
<li>User authentication and authorization</li>
<li>CRUD operations for main entities</li>
<li>Responsive UI design</li>
<li>API endpoints</li>
<li>Database integration</li>
</ul>
<h3>Deliverables:</h3>
<ul>
<li>GitHub repository with source code</li>
<li>Live demo deployment</li>
<li>README with setup instructions</li>
</ul>',
            'starter_code_url' => 'https://github.com/example/starter-project',
            'deadline_at' => now()->addDays(30),
            'max_resubmissions' => 3,
            'sort_order' => 999,
        ]);
        
        $this->info("✓ Created project: {$project->title}");
        
        // Create a lesson for this project
        $lesson = RoadmapLesson::create([
            'roadmap_id' => $roadmapId,
            'section_id' => null,
            'title' => 'Final Project: Capstone Challenge',
            'slug' => 'final-project-capstone-' . uniqid() . '-' . time(),
            'lesson_type' => 'project',
            'content' => '<h2>Final Capstone Project</h2>
<p>This is your chance to demonstrate everything you have learned! Build a complete web application from scratch.</p>
<p>Download the starter code, read the requirements carefully, and submit your completed project when ready.</p>
<p><strong>Good luck!</strong></p>',
            'video_url' => null,
            'document_id' => null,
            'exam_id' => null,
            'project_id' => $project->id,
            'is_preview' => false,
            'is_required' => true,
            'is_published' => true,
            'sort_order' => 999,
        ]);
        
        $this->info("✓ Created lesson: {$lesson->title}");
        $this->newLine();
        $this->info("🎉 Success! Test project created.");
        $this->newLine();
        $this->info("Access the project submission at:");
        $this->line("http://127.0.0.1:8000/roadmaps/{$roadmapId}/learn/{$lesson->id}");
        
        return 0;
    }
}
