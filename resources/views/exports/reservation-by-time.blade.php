<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Cliente</th>
        <th>Correo</th>
        <th>Telefono</th>
        <th>Dia</th>
        <th>Hora</th>
        <th>Producto</th>
        <th>Estatus</th>
        <th>Creado</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reservations as $items)
    @php
        $color = '';
        switch ($items->color_status) {
            case 'success':
                $color = '#9ffd9a';
                break;
            case 'danger':
                $color ="#fd9a9a";
                break;
            case 'info':
                $color ="#9ad1fd";
                break;
        }
    @endphp
        <tr  width="auto">
            <td bgcolor="{{ $color }}">{{ $items->id }}</td>
            <td bgcolor="{{ $color }}" >{{ $items->full_name }}</td>
            <td bgcolor="{{ $color }}" >{{ $items->email }}</td>
            <td bgcolor="{{ $color }}" >{{ $items->telefono }}</td>
            <td bgcolor="{{ $color }}" >{{ $items->date_format }}</td>
            <td bgcolor="{{ $color }}">{{ $items->time_format }}</td>
            <td bgcolor="{{ $color }}">
                @foreach($items->carros as $carro)
                    - {{ $carro->nombre }} {{ $carro->pivot?->total_reservas }}<br>
                @endforeach
            </td>
            <td bgcolor="{{ $color }}">{{ $items->status_format }}</td>
            <td bgcolor="{{ $color }}" >{{ $items->created_format }}</td>
            <td bgcolor="{{ $color }}">&euro;{{ number_format($items->total,2) }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="8"></th>
            <th><strong>Total.:</strong></th>
            <th>&euro;{{ number_format($reservations->sum('total'), 2) }}</th>
        </tr>
    </tfoot>
</table>
