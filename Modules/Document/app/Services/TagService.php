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
                    $tagSlug = Str::slug($tagName);
                    $tag = Tag::firstOrCreate(
                        ['slug' => $tagSlug],
                        ['name' => $tagName]
                    );
                    $tagIds[] = $tag->id;
                }
            }
        }

        $document->tags()->sync(array_unique($tagIds));
    }
}
