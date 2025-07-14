<div class="row">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <div class="col-md-3 my-3 d-flex flex-column">
        <label for="">{{ $label }}</label>

        <label for="files" class="btn @error('files.*') btn-danger @else btn-info @enderror" style="cursor: pointer;">
            <input type="file" wire:model="files" id="files" style="opacity:0; z-index:-1;" class="position-absolute"
                accept="image/*" multiple>
            <span class="pointer-events:none;"><i class="far fa-images"></i> {{ $result ? 'Enviar' : 'Selecionar' }}
                imagens</span>
        </label>
        @error('files.*')
            <span class="error">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-12 position-relative">
        <div class="row position-relative" wire:sortable="updateOrder">
            @foreach ($result as $item)
                <div class="col-3 mb-3 p-3 position-relative" wire:sortable.item="{{ $item->id }}"
                    wire:key="{{ $item->order_column }}">

                    <a href="#" class="rmImgBtn d-block text-danger" style="right: 0px; top: 0px;"
                        wire:click.prevent="delete({{ $item->id }})"><i class="fa-regular fa-circle-xmark"></i></a>

                    <img src="{{ $item->getUrl('thumb') }}" class="img-fluid" alt=""
                        style="height: 200px; object-fit:cover;" wire:sortable.handle>
                </div>
            @endforeach
        </div>
    </div>

    @if ($files)
        <div class="col-12">
            <div class="row">
                @foreach ($files as $item)
                    <div class="col-3 mb-3 p-3 position-relative">
                        <img src="{{ asset('storage/' . $item) }}" class="img-fluid" alt=""
                            style="height: 200px; object-fit:cover">
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
