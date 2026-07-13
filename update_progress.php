<?php

$service = new Modules\Learning\Services\ProjectSubmissionService();
$submissions = Modules\Learning\Models\ProjectSubmission::all();

foreach ($submissions as $s) {
    $service->updateProjectProgress($s->id);
    echo "Updated submission " . $s->id . "\n";
}
