<div>
    <div class="row">
        @forelse ($vehicles as $item)

        <div class="col-xxl-3 col-md-4 col-sm-6 box-col-4" :key="'vehicle'.$item->id">
            <div class="card">
                <div class="product-box">
                    @php
                        $img = str_replace('/images/cars/png/','',$item->img);
                    @endphp
                    <div class="product-img"><img class="img-fluid" src="{{ asset('imgs/products/'.$img) }}" alt="">
                        <div class="product-hover">
                            <ul>
                                {{-- <li><a class="btn" href="cart.html"><i class="fa-solid fa-cart-shopping"></i></a></li> --}}
                                <li data-bs-toggle="modal" data-bs-target='{{ "#modal-vehicle-{$item->id}" }}'>
                                    <a class="btn" href="#!"><i class="fa-solid fa-cart-shopping"></i></a>
                                </li>
                                {{-- <li><a class="btn" href="#!"><i class="fa-solid fa-code-compare fa-rotate-90"></i></a>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                    <div class="modal fade" id='{{ "modal-vehicle-{$item->id}" }}' tabindex="-1" role="dialog"
                        aria-labelledby='{{ "modal-vehicle-{$item->id}" }}' aria-hidden="true" wire:ignore.self>
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="product-box row">
                                        <div class="product-img col-lg-6"><img class="img-fluid"
                                                src="{{ asset('imgs/products/'.$img) }}" alt="">
                                        </div>
                                        <div class="col-lg-6 text-start">
                                            <div class="product-details"><a href="product-details.html">{{ $item->nombre }}</a>
                                                <div class="product-price">
                                                    &euro;{{ number_format($item->precio,2) }}
                                                    {{-- <del>
                                                        $50.00
                                                    </del> --}}
                                                </div>
                                                <div class="product-view">
                                                    <h6 class="f-w-500">Detalles del producto</h6>
                                                    <p class="mb-0">{!! $item->descripcion !!}</p>
                                                </div>

                                                <div class="product-qnty">
                                                    <h6 class="f-w-500">Agregar Vehiculos</h6>
                                                    <p>Disponibles: {{ $item->availability }}</p>
                                                    <fieldset>
                                                        @if ($item->availability > 0)

                                                        <div class="touchspin-wrapper">
                                                            <button class="decrement-touchspin btn-touchspin touchspin-primary">
                                                                <i class="fa-solid fa-minus"> </i>
                                                            </button>

                                                            <input class="input-touchscustom_touchspinpin spin-outline-primary" type="number" value="0" wire:model="qty" data-id="{{ $item->id }}" data-available="{{ $item->availability }}" readonly>

                                                            <button class="increment-touchspin btn-touchspin touchspin-primary">
                                                                <i class="fa-solid fa-plus"></i>
                                                            </button>
                                                        </div>

                                                        @else
                                                            <div class="alert alert-light-info" role="alert">
                                                                <p class="txt-secondary"> {{ $item->nombre.' No esta disponible' }}  </p>
                                                            </div>
                                                        @endif
                                                    </fieldset>

                                                </div>
                                                <div wire:ignore class="product-size row" id="guestVechile{{ $item->id }}" >

                                                </div>
                                                <div>
                                                    <button class="btn btn-primary" type="button" {{ $item->availability <= 0?'disabled':'' }}
                                                        wire:click.prevent="$dispatch('addVehicle',{vehicle_id: {{ $item->id }}})">
                                                        Add to Cart
                                                    </button>
                                                        <a class="btn btn-primary ms-2"
                                                        href="product-details.html">
                                                        View Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div><button class="btn-close" type="button" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-details">
                        <div class="rating"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star-half-stroke"></i><i class="fa-regular fa-star"></i><i
                                class="fa-regular fa-star"></i></div><a href="product-details.html">
                            <h4>{{ $item->nombre }}</h4>
                        </a>
                        <p>{{ Str::limit($item->descripcion,30) }}</p>
                        <div class="product-price">&euro;{{ number_format($item->precio,2) }} {{-- <del>$50.00 </del> --}}</div>
                    </div>
                </div>
            </div>
        </div>
        @empty

        @endforelse

    </div>
</div>

@script
<script>
    console.log("hola");
    $(document).ready(function () {
        let inputQTY = document.getElementsByClassName('input-touchscustom_touchspinpin');

        Array.from(inputQTY).forEach((elem,i) => {
            let inpData = parseInt(elem.getAttribute("value"), 10);
            let inpAvailable = parseInt(elem.getAttribute('data-available'), 10);
            let vehicle_id = elem.getAttribute('data-id');

            let increment = elem.parentNode.querySelectorAll('.increment-touchspin');
            let decrement = elem.parentNode.querySelectorAll('.decrement-touchspin');

            if (increment) {
                increment[0].addEventListener('click', () => {
                    if (inpData < inpAvailable) {
                        inpData++;
                        elem.setAttribute('value', inpData);
                        @this.set('qty', inpData);

                        // Agregar un nuevo select para el nuevo vehículo
                        let guest = `<div class="col-md-6 guest-item" id="guest-item-${vehicle_id}-${inpData}">
                                <label class="form-label" for="exampleFormControlTextarea1">Vehiculo (${inpData})</label>
                                <select class="form-select @error('role') is-invalid @enderror" required="" wire:model="guestVehicle[${inpData}]">
                                    <option selected value>Seleccione...</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                                </div>`;
                        $(`#guestVechile${vehicle_id}`).append(guest);

                        console.log(inpData, 'increment');
                    }
                });
            }
            if (decrement) {
                decrement[0].addEventListener('click', () => {
                    if (inpData > 1) {
                        $(`#guest-item-${vehicle_id}-${inpData}`).remove();
                        inpData--;
                        elem.setAttribute('value', inpData);
                        @this.set('qty', inpData);
                        console.log(inpData, 'decrement');
                    }
                });
            }
        });

    });
</script>
@endscript
