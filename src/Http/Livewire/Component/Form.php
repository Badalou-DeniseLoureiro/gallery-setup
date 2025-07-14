<?php

namespace Cow\Gallery\Http\Livewire\Component;

use Cow\Gallery\WithGallery;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads, WithGallery;

    protected $listeners = ['refreshComponent' => '$refresh', 'refreshModel'];

    public $model, $label = "Galeria de imagens";

    public function mount($model = null)
    {
        $this->model = $model;
    }

    public function refreshModel($model, $id)
    {
        $this->model = $model::find($id);
    }

    public function updatedFiles()
    {
        $files = [];
        foreach ($this->files as $item) {
            $files[] = $item->storeAs('tmp', $item->getClientOriginalName());
        }

        $this->emitUp('files', $files);
    }

    public function updateOrder($list)
    {
        $this->model
            ?->getMedia()
            ->each(fn ($item) => $item->update(['order_column' => collect($list)->where('value', $item->id)->first()['order']]));
    }

    public function delete($id)
    {
        $this->model
            ?->getMedia()
            ->firstWhere('id', $id)
            ->delete();
    }

    public function render()
    {
        return view('gallery::livewire.component.form', ['result' => $this->model?->getMedia() ?? collect()]);
    }
}
