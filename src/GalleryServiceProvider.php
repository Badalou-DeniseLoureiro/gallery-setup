<?php

namespace Cow\Gallery;

use Cow\Gallery\Http\Livewire\Component\Form;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Illuminate\View\Compilers\BladeCompiler;

class GalleryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'gallery');

        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/gallery'),
            __DIR__ . '/database/migrations/create_galleries_table.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_galleries_table.php'),
        ], 'gallery');
    }

    public function register()
    {
        $this->app->afterResolving(
            BladeCompiler::class,
            function () {
                Livewire::component('form-gallery', Form::class);
            }
        );
    }
}
