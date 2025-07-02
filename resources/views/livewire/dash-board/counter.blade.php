<div>
    <div class="row">
        @for ($i = 0; $i < 3; $i++)
            <div class="col-xl-4" wire:loading>
                <div class="card">
                    <div class="card-body animation-placeholder">
                        <p class="placeholder-glow"><span class="placeholder col-12 placeholder-light"></span></p>
                        <p class="placeholder-wave"><span class="placeholder col-12 placeholder-light"></span></p>
                    </div>
                </div>
            </div>
        @endfor

        <div class="col-xl-4" wire:loading.remove>
            <!-- Tarjeta de ventas de hoy -->
            <div class="card compare-order">
                <div class="card-header card-no-border">
                    <div class="header-top">
                        <div class="compare-icon shadow-primary"><i class="fa-solid fa-euro"></i></div>
                        <div class="dropdown icon-dropdown">
                            <button class="btn dropdown-toggle" id="dealDropdown1" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="icon-more-alt"></i></button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dealDropdown1">
                                <a class="dropdown-item" wire:click.prevent="$dispatch('showSalesNow')">Ver Reservaciones</a>
                                <a class="dropdown-item" wire:click.prevent="download('now')">Descargar</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <span class="f-w-500 c-o-light">Ventas de Hoy</span>
                    <h4 class="mb-2"> &euro;<span class="counter" data-target="">{{ $grafic['day']['total'] }}</span></h4>
                    <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="58" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-primary" style="width: 58%"></div>
                    </div>
                    <span class="user-growth f-12 f-w-500">
                        <span class="txt-danger">{{ $grafic['day']['count'] }}</span>
                    </span>
                    <span class="user-text">Ventas</span>
                </div>
            </div>
        </div>
        <div class="col-xl-4" wire:loading.remove>
            <!-- Tarjeta de ventas de ayer -->
            <div class="card compare-order">
                <div class="card-header card-no-border">
                    <div class="header-top">
                        <div class="compare-icon shadow-primary"><i class="fa-solid fa-euro"></i></div>
                        <div class="dropdown icon-dropdown">
                            <button class="btn dropdown-toggle" id="dealDropdown1" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="icon-more-alt"></i></button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dealDropdown1">
                                <a class="dropdown-item" wire:click.prevent="$dispatch('showSalesYesterday')">Ver Reservaciones</a>
                                <a class="dropdown-item" wire:click.prevent="download('yesterday')">Descargar</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <span class="f-w-500 c-o-light">Ventas de ayer</span>
                    <h4 class="mb-2"> &euro;<span class="counter" data-target="">{{ $grafic['yesterday']['total'] }}</span></h4>
                    <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="58" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-primary" style="width: 58%"></div>
                    </div>
                    <span class="user-growth f-12 f-w-500">
                        <span class="txt-danger">{{ $grafic['yesterday']['count'] }}</span>
                    </span>
                    <span class="user-text">ventas</span>
                </div>
            </div>
        </div>
        <div class="col-xl-4" wire:loading.remove>
            <!-- Tarjeta de ventas del mes -->
            <div class="card compare-order">
                <div class="card-header card-no-border">
                    <div class="header-top">
                        <div class="compare-icon shadow-primary"><i class="fa-solid fa-euro"></i></div>
                        <div class="dropdown icon-dropdown">
                            <button class="btn dropdown-toggle" id="dealDropdown1" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="icon-more-alt"></i></button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dealDropdown1">
                                <a class="dropdown-item" wire:click.prevent="$dispatch('showSalesWeek')">Ver Reservaciones</a>
                                <a class="dropdown-item" wire:click.prevent="download('week')">Descargar</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <span class="f-w-500 c-o-light">Ventas del mes</span>
                    <h4 class="mb-2"> &euro;<span class="counter" data-target="{{ $grafic['week']['total'] }}">{{ $grafic['week']['total'] }}</span></h4>
                    <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="58" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-primary" style="width: 58%"></div>
                    </div>
                    <span class="user-growth f-12 f-w-500">
                        <span class="txt-danger">{{ $grafic['week']['count'] }}</span>
                    </span>
                    <span class="user-text">ventas</span>
                </div>
            </div>
        </div>
    </div>
    {{-- modal details reservation --}}
    <div wire:ignore.self class="modal fade" id="nowReservations">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reservas de hoy</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body" wire:ignore>
                        <table class="table border-bottom-table" >
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
    <div wire:ignore.self class="modal fade" id="yesterdayReservations">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reservas de ayer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body" wire:ignore>
                        <table class="table border-bottom-table">
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
                            <tbody id="details-res-yesterday">
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
    <div wire:ignore.self class="modal fade" id="weekReservations">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reservas de la semana</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body" wire:ignore>
                        <table class="table border-bottom-table" >
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
                            <tbody id="details-res-week" >
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

        if (event.data.length > 0) {
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
                    <td>&euro;${element.total}</td>
                </tr>
            `;
            });
            $("#details-res-now").html(bodyTable);
        }else {
            // Opcional: limpia la tabla y muestra un mensaje
            $('#details-res-now').html(`
                <tr>
                    <td colspan="6" class="text-center text-muted">No hay reservas de hoy</td>
                </tr>
            `);
            $('#nowReservations').modal('show');
        }

    });

    $wire.on('showResYesterday',(event) => {

        if (event.data.length > 0) {
            $('#yesterdayReservations').modal('show');
            let bodyTable = '';

            event.data.forEach(element => {
                let car = '';
                element.carros.forEach(vehicle => {
                    car += `${vehicle.nombre} (${vehicle.pivot?.total_reservas ?? ""}) <br>`;
                });

                bodyTable += `
                    <tr class="border-bottom-info">
                        <th scope="row">${element.id}</th>
                        <th scope="row">${element.nombre}</th>
                        <th scope="row">${element.fecha_reservacion}</th>
                        <th scope="row">${element.hora_reservacion}</th>
                        <td>${car}</td>
                        <td>&euro;${element.total}</td>
                    </tr>
                `;
            });

            // Inyecta todo al final del bucle
            $('#details-res-yesterday').html(bodyTable);
        } else {
            // Opcional: limpia la tabla y muestra un mensaje
            $('#details-res-yesterday').html(`
                <tr>
                    <td colspan="6" class="text-center text-muted">No hay reservas de ayer</td>
                </tr>
            `);
            $('#yesterdayReservations').modal('show');
        }
    });

    $wire.on('showResWeek',(event) => {
        if (event.data.length > 0) {
            $('#weekReservations').modal('show');
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
                    <td>&euro;${element.total}</td>
                </tr>
            `;
            });
            $("#details-res-week").html(bodyTable);
        }else {
            // Opcional: limpia la tabla y muestra un mensaje
            $('#details-res-week').html(`
                <tr>
                    <td colspan="6" class="text-center text-muted">No hay reservas de hoy</td>
                </tr>
            `);
            $('#weekReservations').modal('show');
        }
    })
</script>
@endscript
