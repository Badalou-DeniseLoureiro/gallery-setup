<?php

namespace Cow\Gallery;

use Cow\Gallery\Models\Gallery;
use Cow\Gallery\Support\Util;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait HasGallery
{
    public function media()
    {
        return $this->morphMany(Gallery::class, 'model');
    }

    public function addMedia($file)
    {
        $order_column = $this->getMedia()->count() + 1;
        $collect = Str::lower((new \ReflectionClass(get_class()))->getShortName()) . DIRECTORY_SEPARATOR . $this->id ?? Str::random();

        $file = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR .  $file);
        $path_collect = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $collect);

        $file_name = pathinfo($file, PATHINFO_FILENAME);
        $file_path = $path_collect . DIRECTORY_SEPARATOR . $file_name . '.jpg';

        Util::createPath($collect);
        $image = Image::make($file)->encode('jpg', 80);
        $image->save($file_path);

        $manipulations = $this->makeConversions($file, $file_name, $collect);

        $this->media()->create([
            'collect' => $collect,
            'file_name' => $file_name,
            'file_path' => $collect . DIRECTORY_SEPARATOR . $file_name . '.jpg',
            'file_ext' => 'jpg',
            'order_column' => $order_column,
            'manipulations' => json_encode($manipulations, true),
            'size' => Util::getHumanReadableSize($file),
        ]);

        Util::clearTmpFile($file);
    }

    private function makeConversions($file, $file_name, $collect)
    {
        $arr = [];
        if (method_exists($this, 'registerMediaConversions')) {
            foreach ($this->registerMediaConversions() as $item) {
                $arr[] = $item['type'];
                $path = $collect . DIRECTORY_SEPARATOR . $item['type'];
                $path_collect = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $file_name . '.jpg');

                Util::createPath($path);

                Image::make($file)
                    ->resize($item['with'], $item['height'], fn ($constraint) => $constraint->aspectRatio())
                    ->save($path_collect, 80);
            }
        }

        return $arr;
    }

    public function getMedia()
    {
        return $this->media()->orderBy('order_column', 'asc')->get();
    }

    public function getFirstMedia()
    {
        return $this->media()->orderBy('order_column', 'asc')->first();
    }
}
