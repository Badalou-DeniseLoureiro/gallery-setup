<?php

namespace Cow\Gallery\Models;

use Cow\Gallery\Support\Util;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'collect',
        'file_name',
        'file_path',
        'file_ext',
        'order_column',
        'manipulations',
        'size',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($item) {
            Util::remove($item);
        });
    }

    public function getUrl(string $type = null)
    {
        return asset(
            'storage' . DIRECTORY_SEPARATOR
                . $this->collect . ($type ? DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR :  DIRECTORY_SEPARATOR)
                . $this->file_name . '.'
                . $this->file_ext
        );
    }
}
