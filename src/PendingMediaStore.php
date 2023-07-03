<?php

namespace Shrestharikesh\LaravelMediaManager;

use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PendingMediaStore
{
    protected \Illuminate\Http\UploadedFile|string $media;
    protected string $tag = 'default';
    protected string $disk = 'public';
    protected string $alt_text = '';
    protected $mediable;
    protected $deletePrevious = false;

    public function __construct($mediable, string|\Illuminate\Http\UploadedFile $media)
    {
        $this->media = $media;
        $this->mediable = $mediable;
    }

    /**
     * @param string $tag
     * @return PendingMediaStore
     */
    public function withTag(string $tag)
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * @param string $disk
     * @return PendingMediaStore
     */
    public function onDisk(string $disk)
    {
        $this->disk = $disk;
        return $this;
    }

    public function withAltText($alt_text)
    {
        $this->alt_text = $alt_text;
        return $this;
    }

    /**
     * @return PendingMediaStore
     */
    public function deletePrevious()
    {
        $this->deletePrevious = true;
        return $this;
    }

    /**
     * Handle the object's destruction.
     *
     * @return void
     * @throws \Exception
     */
    public function __destruct()
    {
        if (filter_var($this->media, FILTER_VALIDATE_URL)) {
            if (!$this->alt_text) {
                $this->alt_text = $this->getAltTextFromUrl($this->media);
            }
            $data = [
                'url' => $this->media,
                'path' => $this->media
            ];
        } elseif ($this->media instanceof UploadedFile) {
            if (!$this->alt_text) {
                $this->alt_text = Str::random(40) . '-' . $this->media->getClientOriginalName();
            }
            $filename = Str::slug($this->alt_text).'.'.$this->media->extension();
            $file = Storage::disk($this->disk)->putFileAs($this->tag, $this->media, $filename);
            $data =[
                    'url' => Storage::disk('public')->url($file),
                    'path' => $file
                ];
        } else {
            throw new \Exception('Invalid value for image.');
        }
        if ($this->deletePrevious) {
            $this->mediable->deleteMedia($this->tag);
        }
        $data['tag'] = $this->tag;
        $data['alt_text'] = $this->alt_text;
        $this->mediable->media()->create($data);
    }

    protected function getAltTextFromUrl($file): string
    {
        return basename(parse_url($file, PHP_URL_PATH));
    }
}
