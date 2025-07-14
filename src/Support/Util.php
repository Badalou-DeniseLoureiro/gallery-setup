<?php

namespace Cow\Gallery\Support;

use Cow\Gallery\Models\Gallery;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Util
{
    public static function getHumanReadableSize(string $file): string
    {
        $sizeInBytes = File::size($file);
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        if ($sizeInBytes == 0) {
            return '0 ' . $units[1];
        }

        for ($i = 0; $sizeInBytes > 1024; $i++) {
            $sizeInBytes /= 1024;
        }

        return round($sizeInBytes, 2) . ' ' . $units[$i];
    }

    public static function createPath($path)
    {
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path);
        }
    }

    public static function clearTmpFile($file)
    {
        if (File::exists($file)) {
            File::delete($file);
        }
    }

    public static function remove(Gallery $item)
    {
        $path = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $item->collect . DIRECTORY_SEPARATOR);

        self::clearTmpFile($path . $item->file_name . '.' . $item->file_ext);

        collect(json_decode($item->manipulations ?? []))
            ->each(fn ($value) => self::clearTmpFile($path . DIRECTORY_SEPARATOR . $value . DIRECTORY_SEPARATOR . $item->file_name . '.' . $item->file_ext));
    }
}
