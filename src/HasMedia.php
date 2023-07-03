<?php

namespace Shrestharikesh\LaravelMediaManager;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait HasMedia
{
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'media');
    }
    /**
     * @param string|UploadedFile $file
     * @param string $tag
     * @param bool $deletePrevious
     * @return PendingMediaStore
     */
    public function addMedia(string|\Illuminate\Http\UploadedFile $file, string $disk = 'public'): PendingMediaStore
    {
        return new PendingMediaStore($this, $file);
    }
    public function getMedia(string $tag = null): \Illuminate\Database\Eloquent\Collection
    {
        $media =  $this->media();
        if($tag) {
            $media->where('tag', $tag);
        }
        return $media->get();
    }

    public function getFirstMedia(string $tag = null): Model|MorphMany|null
    {
        $media =  $this->media();
        if($tag) {
            $media->where('tag', $tag);
        }
        return $media->first();
    }
    public function deleteMedia($tag = null)
     {
        foreach ($this->getMedia($tag) as $media) {
            if (Storage::disk('public')->exists($media->path)) {
                Storage::disk('public')->delete($media->path);
            }
            $media->delete();
        }
    }
    public function deleteSpecificMedia($id)
    {
        $media = Media::find($id);
        if (Storage::disk('public')->exists($media->path)) {
            Storage::disk('public')->delete($media->path);
        }
        $media->delete();
    }


}
