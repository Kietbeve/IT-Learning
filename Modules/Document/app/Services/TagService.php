<?php

namespace Modules\Document\Services;

use Illuminate\Support\Str;
use Modules\Document\Models\Document;
use App\Models\Tag;

class TagService
{
    /**
     * Sync existing tags and create new custom tags for a document.
     *
     * @param Document $document
     * @param array $selectedTags Array of existing tag IDs
     * @param string|null $customTagsInput Comma-separated string of custom tags
     * @return void
     */
    public function syncTags(Document $document, array $selectedTags = [], ?string $customTagsInput = null): void
    {
        $tagIds = [];
        
        // Save selected existing tags
        if (!empty($selectedTags)) {
            foreach ($selectedTags as $tagId) {
                $tagIds[] = (int) $tagId;
            }
        }

        // Process custom tags
        if (!empty($customTagsInput)) {
            $customTags = array_map('trim', explode(',', $customTagsInput));
            foreach ($customTags as $tagName) {
                if (!empty($tagName)) {
                    // Find by name including soft-deleted to avoid tags_name_unique constraint error
                    $tag = Tag::withTrashed()->where('name', $tagName)->first();
                    
                    if (!$tag) {
                        $slug = Str::slug($tagName);
                        $originalSlug = $slug;
                        $counter = 1;
                        
                        // Ensure slug is truly unique against all records including soft-deleted
                        while (Tag::withTrashed()->where('slug', $slug)->exists()) {
                            $slug = $originalSlug . '-' . $counter;
                            $counter++;
                        }
                        
                        $tag = Tag::create([
                            'name' => $tagName,
                            'slug' => $slug
                        ]);
                    } else {
                        // If found but soft-deleted, restore it
                        if ($tag->trashed()) {
                            $tag->restore();
                        }
                    }
                    
                    $tagIds[] = $tag->id;
                }
            }
        }

        $document->tags()->sync(array_unique($tagIds));
    }
}
