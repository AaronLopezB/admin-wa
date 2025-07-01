<div>
    <div class="row">
        <div class="col-xl-4">
            <div class="card compare-order">
                <div class="card-header card-no-border">
                    <div class="header-top">
                        <div class="compare-icon shadow-primary"><i class="fa-solid fa-dollar"></i></div>
                        <div class="dropdown icon-dropdown"><button class="btn dropdown-toggle" id="dealDropdown1"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false"><i
                                    class="icon-more-alt"></i></button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dealDropdown1">
                                <a class="dropdown-item" wire:click.prevent="$dispatch('showSalesNow')">Ver Reservaciones</a>
                                <a class="dropdown-item" href="#">Descargar</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0"> <span class="f-w-500 c-o-light">Ventas de Hoy</span>
                    <h4 class="mb-2"> $<span class="counter" data-target="">{{ $grafic['day']['total'] }}</span></h4>
                    <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="58" aria-valuemin="0"
                        aria-valuemax="100">
                        <div class="progress-bar bg-primary" style="width: 58%"></div>
                    </div><span class="user-growth f-12 f-w-500">
                        {{-- <i class="icon-arrow-down txt-danger"></i> --}}
                        <span class="txt-danger">{{ $grafic['day']['count'] }}</span></span><span class="user-text">last
                        month</span>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card compare-order">
                <div class="card-header card-no-border">
                    <div class="header-top">
                        <div class="compare-icon shadow-primary"><i class="fa-solid fa-dollar"></i></div>
                        <div class="dropdown icon-dropdown"><button class="btn dropdown-toggle" id="dealDropdown1"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false"><i
                                    class="icon-more-alt"></i></button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dealDropdown1">
                                <a class="dropdown-item" >Ver Reservaciones</a>
                                <a class="dropdown-item" href="#">Descargar</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0"> <span class="f-w-500 c-o-light">Ventas de ayer</span>
                    <h4 class="mb-2"> $<span class="counter" data-target="">{{ $grafic['yesterday']['total'] }}</span></h4>
                    <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="58" aria-valuemin="0"
                        aria-valuemax="100">
                        <div class="progress-bar bg-primary" style="width: 58%"></div>
                    </div><span class="user-growth f-12 f-w-500">
                        {{-- <i class="icon-arrow-down txt-danger"></i> --}}
                        <span class="txt-danger">{{ $grafic['yesterday']['count'] }}</span></span><span
                        class="user-text">ventas</span>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card compare-order">
                <div class="card-header card-no-border">
                    <div class="header-top">
                        <div class="compare-icon shadow-primary"><i class="fa-solid fa-dollar"></i></div>
                        <div class="dropdown icon-dropdown">
                            <button class="btn dropdown-toggle" id="dealDropdown1" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false"><i class="icon-more-alt"></i></button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dealDropdown1">
                                <a class="dropdown-item" href="#">Ver Reservaciones</a>
                                <a class="dropdown-item" href="#">Descargar</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0"> <span class="f-w-500 c-o-light">Ventas del mes</span>
                    <h4 class="mb-2"> $<span class="counter" data-target="{{ $grafic['week']['total'] }}">{{ $grafic['week']['total'] }}</span></h4>
                    <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="58" aria-valuemin="0"
                        aria-valuemax="100">
                        <div class="progress-bar bg-primary" style="width: 58%"></div>
                    </div><span class="user-growth f-12 f-w-500">
                        {{-- <i class="icon-arrow-down txt-danger"></i> --}}
                        <span class="txt-danger">{{ $grafic['week']['count'] }}</span>
                    </span><span class="user-text">ventas</span>
                </div>
            </div>
        </div>
    </div>
    {{-- modal details reservation --}}
    <div wire:ignore.self class="modal fade" id="nowReservations">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detalles de la reservacion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table border-bottom-table" wire.ignore>
                            <thead>
                                <tr class="border-bottom-dark">
                                    <th scope="col">#</th>
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Dia</th>
                                    <th scope="col">Hora</th>
                                    <th scope="col">Producto</th>
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody id="details-res-now" >
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cerrar</button>
                        {{-- <button class="btn btn-primary" type="submit">Agregar</button> --}}
                    </div>
                </div>
            </div>
    </div>
</div>


@script
<script>
    $wire.on('showResNow',(event) => {

        $('#nowReservations').modal('show');
        let bodyTable='';
        event.data.forEach(element => {
            let car = '';
            element.carros.forEach(vehicle => {
                car += `${vehicle.nombre} (${vehicle.pivot?.total_reservas?? ""}) `;
            });

            bodyTable += `
                    <tr class="border-bottom-info" wire:key="now-res-${element.id}">
                        <th scope="row">${element.id}</th>
                        <th scope="row">${element.nombre}</th>
                        <th scope="row">${element.fecha_reservacion}</th>
                        <th scope="row">${element.hora_reservacion}</th>
                        <td>${car}</td>
                        <td>${element.total}</td>
                    </tr>
            `;
        });
        $("#details-res-now").html(bodyTable);
        console.log(bodyTable);
    });
</script>
@endscript
