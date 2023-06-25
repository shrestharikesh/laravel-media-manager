<?php

namespace Shrestharikesh\MediaManager;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait InteractsWithMedia
{
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'media');
    }


    /**
     * @param string|UploadedFile $file
     * @param string $type
     * @param bool $deletePrevious
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function addMedia(string|\Illuminate\Http\UploadedFile $file, string $type = 'default', bool $deletePrevious = false)
    {
        $data['type'] = $type;
        if (is_string($file)) {
            $data = array_merge(
                $data,
                [
                    'filename' => $this->getFilename($file),
                    'url' => $file,
                    'path' => $file
                ]
            );
        } elseif ($file instanceof UploadedFile) {
            $filename = Str::random(40).'-'.$file->getClientOriginalName();
            $file = Storage::disk('public')->putFileAs($type, $file, $filename);
            $data = array_merge(
                $data,
                [
                    'filename' => $filename,
                    'url' => Storage::disk('public')->url($file),
                    'path'=>$file
                ]
            );
        } else {
            throw new \Exception('Invalid image type.');
        }
        if($deletePrevious) {
            $this->deleteMedia($type);
        }
        return $this->media()->create($data);
    }

    public function getMedia(string $type = null): \Illuminate\Database\Eloquent\Collection
    {
        $media =  $this->media();
        if($type) {
            $media->where('type', $type);
        }
        return $media->get();
    }
    public function getFirstMedia(string $type = null): \Illuminate\Database\Eloquent\Model|MorphMany|null
    {
        $media =  $this->media();
        if($type) {
            $media->where('type', $type);
        }
        return $media->first();
    }

    public function deleteMedia($type = null)
    {
        foreach ($this->getMedia($type) as $media) {
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

    protected function getFilename($file): string
    {
        return basename(parse_url($file, PHP_URL_PATH));
    }
}
