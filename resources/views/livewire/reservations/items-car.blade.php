<div>
    <ul class="summery-contain">
        @foreach ($items as $item)

        <li>
            <img class="img-fluid" src="{{ asset('imgs/products/'.$item->carros->img_vehiculo) }}" alt="headphone">
            <h6>{{ $item->carros->nombre }}<span>X {{ $item->available }}</span></h6>
            <h6 class="price">&euro;{{ number_format($item->price, 2) }}</h6>
        </li>
        @endforeach
    </ul>
    <ul class="summary-total">
        {{-- <li>
            <h6>Subtotal</h6>
            <h6 class="price">$625.00</h6>
        </li>
        <li>
            <h6>Shipping</h6>
            <h6 class="price">$14.00</h6>
        </li>
        <li>
            <h6>Tax</h6>
            <h6 class="price">$18.00</h6>
        </li> --}}
        {{-- <li>
            <h6>Coupon Code</h6>
            <h6 class="price">$-30.00</h6>
        </li> --}}
        <li class="list-total">
            <h6>Total </h6>
            <h6 class="price">&euro;{{ $items->sum('total') }}</h6>
        </li>
    </ul>
</div>
