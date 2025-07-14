<?php

namespace Cow\Gallery;

use Cow\Gallery\Exceptions\WebpExeption;
use Buglinjo\LaravelWebp\Facades\Webp;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait WithGallery
{
    public $files;

    public function __constrct()
    {
        $this->files = [];
    }

    protected function getListeners()
    {
        $arr = ['fileReset', 'files'];
        if (sizeof($this->listeners)) return array_merge($arr, $this->listeners);

        return $arr;
    }

    public function files($values)
    {
        $this->files = $values;
    }

    public function fileReset()
    {
        $this->files = [];
    }

    public function makeImage($file, $name, $output = 'media', $quality = 80)
    {
        $name = $name . '.webp';
        self::createPath($output);
        $webp = Webp::make($file);

        if ($webp->save(storage_path('app/public/' . $output) . '/' . $name, $quality)) {
            $newFile = storage_path('app/public/' . $output) . '/' . $name;
        }

        if (method_exists($this->model, 'registerMediaConversions')) {
            foreach ($this->model->registerMediaConversions() as $item) {
                $path = "{$output}/" . $item['type'];

                self::createPath($path);

                Image::make($newFile)
                    ->resize($item['with'], $item['height'], function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(storage_path("app/public/{$path}/{$name}"), $quality);
            }
        }
        return "{$output}/{$name}";
    }
}
