<div>

    <ul >
        @forelse ($items as $item)
            <li>
                <div class="d-flex"><img class="img-fluid b-r-5 me-3 img-60"
                        src="{{ asset('imgs/products/'.$item->carros->img_vehiculo) }}" alt="">
                    <div class="flex-grow-1"><span>{{ $item->carros->nombre }}</span>
                        <div class="qty-box">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                <button
                                        class="btn quantity-left-minus" type="button" data-type="minus" disabled
                                        data-field="">-</button>
                                    </span>
                                    <input class="form-control input-number" type="text"
                                    name="quantity" value="{{ $item->available }}" disabled>
                                    <span class="input-group-prepend">
                                        <button
                                        class="btn quantity-right-plus" type="button" data-type="plus" disabled
                                        data-field="">+</button>
                                    </span>
                            </div>
                        </div>
                        <h6 class="font-primary">&euro;{{number_format($item->price, 2)}}</h6>
                    </div>
                    <div class="close-circle"><a class="bg-danger" href="#"><i class="fas fa-times"></i></a></div>
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
