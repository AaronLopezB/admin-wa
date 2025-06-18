<div>
    <div class="cart-box">
        {{-- <div> --}}
            <svg wire:loading.class="d-none">
                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-ecommerce') }}"></use>
            </svg>
            <span class="badge rounded-pill badge-danger" wire:loading.class="d-none">{{ count($items) }}</span>
        {{-- </div> --}}
        <i wire:loading class="fas fa-spin fa-circle-notch"></i>
    </div>
    @if (count($items) > 0)

    <div class="cart-dropdown onhover-show-div">
        <h6 class="f-18 mb-0 dropdown-title">Cart</h6>
        <ul>
            @forelse ($items as $item)
            <li>
                <div class="d-flex"><img class="img-fluid b-r-5 me-3 img-60"
                        src="{{ asset('imgs/products/'.$item->carros->img_vehiculo) }}" alt="">
                    <div class="flex-grow-1"><span>{{ $item->carros->nombre }}</span>
                        <div class="qty-box">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <button class="btn quantity-left-minus" type="button" data-type="minus" disabled
                                        data-field="">-</button>
                                </span>
                                <input class="form-control input-number" type="text" name="quantity"
                                    value="{{ $item->available }}" disabled>
                                <span class="input-group-prepend">
                                    <button class="btn quantity-right-plus" type="button" data-type="plus" disabled
                                        data-field="">+</button>
                                </span>
                            </div>
                        </div>
                        <h6 class="font-primary">&euro;{{number_format($item->price, 2)}}</h6>
                    </div>
                    <div class="close-circle">
                        <a class="bg-danger" href="#" wire:click="$dispatch('delete',{car_id:{{ $item->id }}})">
                            {{-- <i class="fas fa-times"></i> --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </a>
                    </div>
                </div>
            </li>
            @empty
            nada
            @endforelse

            <li class="total">
                <h6 class="mb-0">Order Total : <span class="f-right">&euro;{{ $items->sum('total') }}</span></h6>
            </li>
            <li class="text-center"><a class="d-block view-cart f-w-700 btn btn-primary w-100" href="">View Cart</a><a
                    class="btn btn-primary view-checkout btn btn-primary w-100 f-w-700" href="">Checkout</a></li>
        </ul>
    </div>
    @endif
</div>

@script
<script>
    $wire.on('delete',(event) => {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Se eliminara el producto del carrito.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log(event.car_id,'deleteCar');
                // $wire.$refresh();
                $wire.dispatch('deleteCar',{car_id:event.car_id})
                // $wire.dispatch('activeAccountU');
            }
        });
    });

    $wire.on('notify', (event) => {
        console.log(event);

        // Manejadores para cada método posible
        const handlers = {
            // Cuando se agrega una nota
            deleteItem: () => {
                Toast.fire({
                    icon: event.type,
                    title: event.msj,
                });
            }

        };

        // Ejecuta el manejador correspondiente o muestra advertencia si no existe
        (handlers[event.method] || (() => {
            Toast.fire({
                icon: "warning",
                title: 'Acción no reconocida',
            });
        }))();
    });
</script>
@endscript
