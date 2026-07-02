<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migrate existing documents with parent_document_id to document_relationships table
     */
    public function up(): void
    {
        // Find all documents that have a parent (drafts/edit submissions)
        $drafts = DB::table('documents')
            ->whereNotNull('parent_document_id')
            ->get();
        
        foreach ($drafts as $draft) {
            // Determine relationship status based on document status
            $relationshipStatus = match($draft->status) {
                'approved' => 'approved',
                'pending' => 'pending',
                'rejected' => 'rejected',
                default => 'pending'
            };
            
            // Create relationship record
            DB::table('document_relationships')->insert([
                'parent_document_id' => $draft->parent_document_id,
                'draft_document_id' => $draft->id,
                'relationship_type' => 'edit_submission', // All existing drafts are edits
                'status' => $relationshipStatus,
                'rejected_reason' => $draft->rejected_reason ?? null,
                'submitted_by' => $draft->author_id,
                'reviewed_by' => $draft->reviewed_by ?? null,
                'submitted_at' => $draft->created_at ?? now(),
                'reviewed_at' => $draft->reviewed_at ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Log migration result
        $count = $drafts->count();
        if ($count > 0) {
            echo "✓ Migrated {$count} draft documents to document_relationships table\n";
        } else {
            echo "✓ No draft documents to migrate\n";
        }
    }

    /**
     * Reverse the migrations.
     * 
     * Delete migrated relationship records (only those from this migration)
     */
    public function down(): void
    {
        // Find all documents that still have parent_document_id
        $draftIds = DB::table('documents')
            ->whereNotNull('parent_document_id')
            ->pluck('id');
        
        if ($draftIds->isNotEmpty()) {
            // Delete relationship records for these drafts
            $deleted = DB::table('document_relationships')
                ->whereIn('draft_document_id', $draftIds)
                ->delete();
            
            echo "✓ Deleted {$deleted} relationship records\n";
        } else {
            echo "✓ No relationship records to delete\n";
        }
    }
};
