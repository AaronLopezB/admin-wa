<div>
    <ul class="summery-contain">
        @foreach ($items as $item)

        <li wire:key="car_{{ $item->id }}" wire:loading.class="d-none">
            <img class="img-fluid" src="{{ asset('imgs/products/'.$item->carros->img_vehiculo) }}" alt="headphone">
            <h6>{{ $item->carros->nombre }}<span>X {{ $item->available }}</span></h6>

            <select class="form-select" data-vehicle="{{ $item->id }}" id="addCode" wire:loading.attr="disabled" wire:target="addDiscount">
                <option value="0">Descuento</option>
                @for ($i = 5; $i <= 50; $i += 5)
                    @php
                        $discount = number_format($i / 100, 2);
                    @endphp
                    <option value="{{ $discount }}" {{ $item->discount == $discount? 'selected':'' }}>{{ $i }}%</option>
                @endfor
            </select>
            <div class="invalid-feedback">Invalid select feedback</div>
            <h6 class="price">
                @if ($item->discount != 0)
                    @php
                        $price = number_format($item->price,2);
                    @endphp
                <span class="text-muted text-decoration-line-through">&euro;{{ $price }}</span>&euro;{{ number_format($price - ( $price * $item->discount ),2) }}</h6>
                @else
                    &euro;{{ number_format($item->price,2) }}
                @endif

        </li>
        <div wire:key="load_{{ $item->id }}" class="row gy-3 p-15 d-none" wire:loading.class.remove="d-none">
            <div class="col-sm-12 ">
                <div class="placeholder-body">
                    <div class="placeholder-start">
                        <div class="square"></div>
                    </div>
                    <div class="placeholder-end">
                        <div class="placeholder-line placeholder-h-17 w-25 mb-2"></div>
                        <div class="placeholder-line"></div>
                        <div class="placeholder-line placeholder-h-8 w-50"></div>
                        <div class="placeholder-line w-75"></div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </ul>
    <ul class="summary-total">
        @if ($items->pluck('codigo_id')->first())
            @php
                $cupon =($items->sum('total') * $items->pluck('code')->first()->descuento) / 100;
                $descuentoTotal = $items->sum('total') - $cupon;
            @endphp
            <li>
                <h6>Descuento </h6>
                <h6 class="price">
                    &euro;
                        {{ number_format($cupon,2) }}
                </h6>
            </li>
        @endif
        <li class="list-total">
            <h6>Total </h6>
            <h6 class="price">
                &euro;
                @if ($items->pluck('codigo_id')->first())
                    @php
                        $cupon =($items->sum('total') * $items->pluck('code')->first()->descuento) / 100;
                        $descuentoTotal = $items->sum('total') - $cupon;
                    @endphp
                    {{ number_format($descuentoTotal,2) }}
                @else
                {{ number_format($items->sum('total'),2) }}
                @endif
            </h6>
        </li>
    </ul>
</div>
@script
<script>
    $(document).ready(function () {
        $(document).on('change','#addCode', function (e) {
            e.preventDefault();
            let value = $(this).val();
            let vehicle = $(this).data('vehicle');
            console.log({
                value:value,
                vehicle:vehicle
            });
            $wire.dispatch('addDiscount',{discount:value,vehicle_id:vehicle});
        });
    });
</script>
@endscript
