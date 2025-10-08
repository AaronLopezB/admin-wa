<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura Benefits Travel SL</title>
    <style>
         /* html {
            height: 100%;
            width: 100%;
        } */

        @page {
            /* size: A4; */
            margin: 0px;
        }
        /* Estilos para impresión y visualización */
        body {
            background: url('{{ public_path('imgs/holaMembretadav2.jpg') }}') no-repeat center top fixed;
            /* background-position: top left; */
            /* background-repeat: no-repeat; */
            background-size: cover;
            /* width: 210mm;
            min-height: 297mm; */
            margin: 0 auto;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
            color: #111;
            position: relative;
            box-sizing: border-box;
        }
        /* Contenido de la factura */
        .invoice-content {
            position: relative;
            padding: 15mm 15mm;
            /* height: 100%; */
            box-sizing: border-box;
            margin-top: 70px
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 24px;
            margin: 0 0 10px 0;
            color: #2c3e50;
        }

        .datos-empresa p,
        .datos-factura p {
            margin: 3px 0;
            font-size: 13px;
        }

        .factura-info {
            margin: 25px 0;
            padding: 15px;
            /* background-color: rgba(255, 255, 255, 0.8); */
            border-radius: 5px;
            border-left: 4px solid #3498db;
            font-size: 13px;
        }

        .factura-info p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            /* background-color: rgba(255, 255, 255, 0.9); */
        }

        table, th, td {
            border: 1px solid #bdc3c7;
        }

        th {
            /* background-color: #f8f9fa; */
            padding: 10px;
            text-align: left;
            font-weight: bold;
            color: #2c3e50;
        }

        td {
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }

        .totales {
            margin-top: 5px;
            width: 45%;
            float: right;
            /* background-color: rgba(255, 255, 255, 0.9); */
            /* border-radius: 5px; */
            padding: 10px;
            /* box-shadow: 0 2px 5px rgba(0,0,0,0.1); */
        }

        .totales table {
            width: 100%;
            background: transparent;
        }

        .totales td {
            border: none;
            padding: 8px 5px;
        }

        .importe-total {
            font-weight: bold;
            border-top: 2px solid #2c3c4d !important;
            color: #2c3e50;
            font-size: 16px;
        }

    </style>
</head>
<body>

    <!-- Contenido de la factura -->
    <div class="invoice-content">
        <div class="header">
            <div class="datos-empresa">
                <h1>Benefits Travel SL</h1>
                <h3>World Adventures</h3>
                <p>Calle Atenas 2, local NN, Pozuelo de Alarcon</p>
                <p>CIF: B88085915</p>
                <p>28224 Madrid Madrid</p>
                <p>España</p>
                <p>+34 648 070 864</p>
                <p>support@world-adventures.es</p>
            </div>
            <div class="datos-factura">
                <p><strong>Número de factura:</strong> {{ $numeroFactura }}</p>
                <p><strong>Fecha de emisión:</strong> {{ $fecha }}</p>
            </div>
        </div>

        <div class="factura-info">
            <div class="datos-cliente">
                <p><strong>Facturar a:</strong></p>
                <p>{{ $customer->nombre.' '.$customer->apellidos }}</p>
                <p>{{ $customer->email }}</p>
                <p>{{ Str::upper($customer->king_invoice) }}: {{ $customer->key_invoice }}</p>
                <p><strong>Folio de reserva:</strong> {{ $customer->id }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Importe Base</th>
                    {{-- <th>Total</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item['description'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>{{ number_format($item['total'], 2, ',', '.') }} €</td>
                    {{-- <td>{{ number_format($item['total'], 2, ',', '.') }} €</td> --}}
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totales">
            <table>
                {{-- <tr>
                    <td>Importe</td>
                    <td>{{ number_format(collect($items)->sum(fn($i) => $i['total']), 2, ',', '.') }} €</td>
                </tr> --}}
                <tr>
                    <td>IVA (21%)</td>
                    <td>{{ number_format(collect($items)->sum(fn($i) => $i['total'] * 0.21), 2, ',', '.') }} €</td>
                </tr>
                <tr class="importe-total">
                    <td>Total a pagar</td>
                    <td>
                        @php
                            $importe = collect($items)->sum(fn($i) => $i['total']);
                            $iva = collect($items)->sum(fn($i) => $i['total'] * 0.21);
                            $total = round($importe + $iva);
                        @endphp
                        {{ number_format($total, 2, ',', '.') }} €
                    </td>
                </tr>
            </table>
        </div>


    </div>
</body>
</html>
