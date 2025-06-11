<div>
    <div class="col-sm-12">
        <div class="card">

            <div class="card-header">
                <h5>Reservaciones</h5>
                <div class="card-header-right">
                    <div class="input-group">
                        <input class="form-control" type="text" placeholder="Buscar...."
                            aria-label="Recipient's username" aria-describedby="button-addon2"  wire:model="search">
                        <button class="btn btn-outline-primary" id="button-addon2" type="button" wire:click="$refresh">
                            Buscar <i wire:loading class="fa-solid fa-circle-notch fa-spin" style="color: var(--theme-default)"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body animation-placeholder" wire:loading>
                <p class="placeholder-glow"><span class="placeholder col-12 placeholder-light"></span></p>
                <p class="placeholder-wave"><span class="placeholder col-12 placeholder-light"></span></p>
            </div>
            <div class="table-responsive custom-scrollbar mt-2" wire:loading.remove>
                <table class="table border-bottom-table">
                    <thead>
                        <tr class="border-bottom-dark">
                            <th scope="col">Folio</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Telefono</th>
                            <th scope="col">Fecha del tour</th>
                            <th scope="col">Total</th>
                            <th scope="col">Reservacion Creada</th>
                            <th scope="col">Status</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reservations as $item)

                        <tr class="border-bottom-{{ $item->color_status }}" wire:key="reserva-{{$item->id}}">
                            <th scope="row">{{ $item->id }}</th>
                            <td> {{-- <img class="img-30 me-2" src="../assets/images/user/1.jpg" alt="profile"> --}}
                                {{ $item->nombre." ". $item->apellidos }}
                            </td>
                            <td>{{ $item->email }}</td>
                            <td>
                                {{ $item->telefono }}
                            </td>
                            <td>
                                {{ $item->date_format." ".$item->time_format }}
                            </td>
                            <td>
                                &euro;{{ $item->total }}
                            </td>
                            <td>
                                {{ $item->created_format }}
                            </td>
                            <td>
                                <span class="badge badge-light-{{ $item->color_status }}">
                                    {{ $item->status_format }}
                                </span>
                            </td>
                            <td>
                                <div class="dropdown icon-dropdown">
                                    <button class="btn dropdown-toggle" id="dealDropdown1" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false"><i
                                            class="icon-more-alt"></i></button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dealDropdown1">

                                        <button class="dropdown-item"
                                            {{ $item->estatus === 1 || $item->estatus === 8 ? '':'disabled' }}
                                            wire:click.prevent="$dispatch('add-status-modal', {reservation_id:{{$item->id}}} )">Agregar Estatus</button>
                                        <button class="dropdown-item"
                                            {{ $item->estatus === 1 || $item->estatus === 8 ? '':'disabled' }}
                                            wire:click.prevent="$dispatch('show-reservation-modal', {reservation_id:{{$item->id}}} )">Detalle de compra</button>
                                        <button class="dropdown-item"
                                            {{ $item->estatus === 1 || $item->estatus === 8 ? '':'disabled' }}
                                            wire:click.prevent="$dispatch('show-modal-update-date',{reservation_id:{{$item->id}}})">Cambiar fecha y hora</button>
                                        <button class="dropdown-item"
                                            {{ $item->estatus === 1 || $item->estatus === 8 ? '':'disabled' }}
                                            wire:click="$dispatch('resendMailReservation',{reservation_id:{{ $item->id }}})">Enviar correo</button>
                                        <button class="dropdown-item"
                                            wire:click="$dispatch('sendMailRefund',{reservation_id:{{ $item->id }}})"
                                        >Enviar correo de reembolso</button>
                                        <button class="dropdown-item"
                                            {{ $item->estatus === 1 || $item->estatus === 8 ? '':'disabled' }}
                                            href="#">Reenviar términos</button>
                                        <button class="dropdown-item"
                                            wire:click="$dispatch('show-modal-add-notes',{reservation_id:{{ $item->id }}})">Nota de reserva</button>
                                        <button class="dropdown-item"
                                            wire:click="$dispatch('generate-qr-reservation',{reservation_id:{{ $item->id }}})">Generar QR de regalo</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr class="border-bottom-danger">
                            <td colspan="9"> {{-- <img class="img-30 me-2" src="../assets/images/user/1.jpg" alt="profile"> --}}
                                <p class="text-capitalize text-muted text-center">
                                        No se ha encontrado ningún registro ...
                                    </p>
                            </td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
                <div class="p-15">
                    {{ $reservations->links('vendor.livewire.customer') }}
                </div>
            </div>
            <div class="card-body animation-placeholder" wire:loading.delay>
                <p class="placeholder-glow"><span class="placeholder col-12 placeholder-light"></span></p>
                <p class="placeholder-wave"><span class="placeholder col-12 placeholder-light"></span></p>
            </div>
        </div>
    </div>

    {{-- modal add status --}}
    <div wire:ignore.self class="modal fade" id="addStatusRes">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Estatus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <form wire:submit.prevent="addNote">
                    <input type="hidden" wire:model="reservation_id" id="reservation_id">
                    <div class="modal-body">
                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="validationDefault04">State</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="validationDefault04" required="" wire:model="status">
                                <option selected value>Seleccione...</option>
                                <option value="1">Hizo el recorrido</option>
                                <option value="2">No llegó</option>
                                <option value="3">No llegó a la visita</option>
                                <option value="4">Disputa</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="exampleFormControlTextarea1">Observaciones</label>
                            <textarea class="form-control @error('msj') is-invalid @enderror" id="exampleFormControlTextarea1" rows="3" wire:model="msj"></textarea>

                            @error('msj') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary" type="submit">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- modal details reservation --}}
    <div wire:ignore.self class="modal fade" id="detailsReservation">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles de la reservacion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="simple-wrapper nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item"><a class="nav-link active txt-primary" id="home-tab" data-bs-toggle="tab"
                                href="#home" role="tab" aria-controls="home" aria-selected="true">Detalles del cliente</a></li>
                        <li class="nav-item"><a class="nav-link  txt-primary" id="profile-tabs"
                                data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                                aria-selected="false">Productos que reservo</a></li>
                        {{-- <li class="nav-item"><a class="nav-link txt-primary" id="contact-tab" data-bs-toggle="tab"
                                href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
                        </li> --}}
                    </ul>
                    <div class="tab-content" id="myTabContent">

                        <div class="tab-pane fade fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="ribbon-wrapper border border-1 height-equal" id="statusReserv" wire:ignore>

                                <div class="p-10 pt-3 mb-0 " id="detail-customer">

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane " id="profile" role="tabpanel"
                            aria-labelledby="profile-tabs">
                            <div class="pt-3 mb-0">
                                <div class="flex-space flex-wrap align-items-center table-responsive" wire:ignore>
                                    <table class="table border-bottom-table">
                                        <thead>
                                            <tr class="border-bottom-dark">
                                                <th scope="col">Descripcion</th>
                                                <th scope="col">QTY</th>

                                            </tr>
                                        </thead>
                                        <tbody id="details-product">
                                        </tbody>
                                    </table>
                                    {{-- <div class="table-responsive custom-scrollbar mt-2" wire:loading.remove>
                                    </div> --}}
                                </div>
                            </div>
                        </div>

                        {{-- <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <ul class="pt-3 d-flex flex-column gap-1">
                                <li>Us Technology offers web & mobile development solutions for all industry
                                    verticals.Include a short form using fields that'll help your business understand
                                    who's contacting them.</li>
                                <li>
                                    <strong>Visit Us:</strong>2600 Hollywood Blvd,Florida, United States-33020
                                </li>
                                <li>
                                    <strong>Mail Us:</strong>contact@us@gmail.com
                                </li>
                                <li>
                                    <strong>Contact Number:</strong>(954) 357-7760
                                </li>
                            </ul>
                        </div> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cerrar</button>
                    {{-- <button class="btn btn-primary" type="submit">Agregar</button> --}}
                </div>
            </div>
        </div>
    </div>

    {{-- modal update date reservation --}}
    <div wire:ignore.self class="modal fade" id="updateDateReservation">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleUpdateDateReservation">Agregar Estatus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <form wire:submit.prevent="updateDateReservation">
                    {{-- <input type="hidden" wire:model="reservation_id" id="reservation_id"> --}}
                    <input type="hidden" wire:model="reservation_id" id="reservation_id">
                    <div class="modal-body">
                        <div class="col-md-12 mb-2" wire:ignore>
                            <label class="form-label" for="validationDefault04">Fecha</label>
                            <input
                                class="form-control @error('dateResUp') is-invalid @enderror" id="human-friendly" type="date"
                                value="{{ now()->format('Y-m-d') }}" wire:model="dateResUp"/>
                            @error('dateResUp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="exampleFormControlTextarea1">Hora</label>
                            <select class="form-select @error('timeResUp') is-invalid @enderror" id="validationDefault04" required="" wire:model="timeResUp">
                                <option selected value>Seleccione...</option>
                                <option value="10:00">10:00</option>
                                <option value="15:00">15:00</option>
                                <option value="18:00">18:000</option>
                            </select>
                            @error('timeResUp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary" type="submit">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal add observations --}}
    <div wire:ignore.self class="modal fade" id="addNotesRes">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleObservationsRes">Agregar Estatus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <form wire:submit.prevent="addObservations" id="formObservations">
                    {{-- <input type="hidden" wire:model="reservation_id" id="reservation_id"> --}}
                    <input type="hidden" wire:model="reservation_id" id="reservation_id">
                    <div class="modal-body">

                        <div class="col-md-12">
                            <label class="form-label" for="exampleFormControlTextarea1">Observaciones</label>
                            <textarea class="form-control @error('observations') is-invalid @enderror" id="exampleFormControlTextarea1" rows="3" wire:model="observations"></textarea>
                            @error('observations') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary" type="submit">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="generateQrReservation">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" wire:ignore>
                    {{-- <h5 class="modal-title">Generar QR de regalo</h5> --}}
                    <h5 class="modal-title" id="titleGenerateQr"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <form wire:submit.prevent="generateQr" id="formObservations">
                    {{-- <input type="hidden" wire:model="reservation_id" id="reservation_id"> --}}
                    <input type="hidden" wire:model="reservation_id" id="reservation_id">
                    <div class="modal-body row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="validationDefault04">Nombre de quien regala:</label>
                            <input class="form-control @error('cnombre') is-invalid @enderror" type="text" placeholder="Nombre" wire:model="cnombre" required>
                            @error('cnombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="exampleFormControlTextarea1">Correo de quien regala:</label>
                            <input class="form-control @error('cemail') is-invalid @enderror" type="text" placeholder="Correo" wire:model="cemail" required>
                            @error('cemail') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="validationDefault04">Nombre del beneficiario:</label>
                            <input class="form-control @error('gfnombre') is-invalid @enderror" type="text" placeholder="Nombre" wire:model="gfnombre" required>
                            @error('gfnombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="exampleFormControlTextarea1">Correo del beneficiario:</label>
                            <input class="form-control @error('gfemail') is-invalid @enderror" type="text" placeholder="Correo" wire:model="gfemail" required>
                            @error('gfemail') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary" type="submit">Generar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>

@script
<script>
    console.log("Dashboard Livewire Component Loaded");
    $wire.on('add-status-modal',(event) => {
        $("#addStatusRes").modal("show");
        @this.reservation_id = event.reservation_id
    });

    // Evento para mostrar el modal de detalles de la reservación
    // Evento para mostrar el modal de detalles de la reservación
    $wire.on('show-modal-reservation', (event) => {
        // Limpia el contenido previo del modal para evitar duplicados o datos obsoletos
        $("#statusReserv").find('.ribbon').remove();
        $("#detail-customer").empty();
        $("#details-product").empty();

        // Mapeo de estatus para mostrar color y texto adecuados
        const statusMap = {
            1: { color: 'success', text: 'Hizo el recorrido' },
            2: { color: 'info', text: 'No llegó' },
            3: { color: 'warning', text: 'No llegó a la visita' },
            4: { color: 'danger', text: 'No llegó a la visita' }
        };
        const statusCode = statusMap[event.reservation.estatusfintour] || { color: 'secondary', text: 'Sin estatus' };

        // Agrega la cinta de estatus en el modal
        $("#statusReserv").append(
            `<div class="ribbon ribbon-${statusCode.color} ribbon-clip">${statusCode.text}</div>`
        );

        // URL base para archivos S3
        const urlS3 = "{{ config('secret.url_s3') }}";

        // Sincroniza datos del cliente con Livewire
        @this.rnombre = event.reservation.nombre;
        @this.rapellidos = event.reservation.apellidos;
        @this.rtelefono = event.reservation.telefono;
        @this.remail = event.reservation.email;
        @this.reservation_id = event.reservation.id;

        // Construye las notas de la reservación si existen
        let notes = '';
        if (event.reservation.note && event.reservation.note.length > 0) {
            notes = event.reservation.note.map(note => `
                <div class="your-msg">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <span class="f-w-500">${note.user.name} <span>${note.created_at}</span></span>
                            <p>${note.note}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Construye el bloque de detalles del cliente
        let detailsCustom = `
            <div class="flex-space flex-wrap align-items-center" id="data-customer">
                <div class="col-md-12 d-flex gap-1">
                    <button class="btn border-dashed-warning" id="editCustomer" data-id="${event.reservation.id}">Editar</button>
                    <button class="btn border-dashed-success d-none" id="saveDataCustomer" data-id="${event.reservation.id}">Guardar</button>
                    <button class="btn border-dashed-danger d-none" id="cancelDataCustomer" data-id="${event.reservation.id}">Cancelar</button>
                    <a class="btn border-dashed-info ${!event.reservation.terminos ? 'd-none' : ''}"
                        href="${event.reservation.terminos && event.reservation.terminos.includes(urlS3)
                            ? event.reservation.terminos
                            : (event.reservation.terminos ? "http://world-adventures.us/" + event.reservation.terminos : '#')}">
                        Ver Licencia
                    </a>
                    <a class="btn border-dashed-info ${!event.reservation.licensia ? 'd-none' : ''}"
                        href="${event.reservation.licensia && event.reservation.licensia.includes(urlS3)
                            ? event.reservation.licensia
                            : (event.reservation.licensia ? "http://world-adventures.us/" + event.reservation.licensia : '#')}">
                        Ver Terminos y condiciones
                    </a>
                </div>
                <div class="col-md-6 d-flex flex-column gap-1">
                    <ul class="d-flex flex-column gap-1">
                        <li><strong>Nombre: </strong>${event.reservation.nombre ?? ''} ${event.reservation.apellidos ?? ''}</li>
                        <li><strong>Correo: </strong>${event.reservation.email ?? 'N/A'}</li>
                        <li><strong>Telefono: </strong>${event.reservation.telefono ?? 'N/A'}</li>
                    </ul>
                </div>
                ${event.reservation.note && event.reservation.note.length > 0 ? `
                <div class="b-t-secondary col-md-12 d-flex flex-column gap-1">
                    <h5 class="mt-3">Notas de la reservación</h5>
                    <div class="social-chat">
                        ${notes}
                    </div>
                </div>` : ''}
            </div>
            <!-- Formulario para editar datos del cliente (oculto por defecto) -->
            <form wire:submit.prevent="updateCustomer" class="row g-3 needs-validation d-none" id="formCustomer">
                <input type="hidden" wire:model="reservation_id">
                <div class="col-md-6 mb-2">
                    <label class="form-label" for="validationDefault04">Nombre</label>
                    <input class="form-control" id="rnombre" type="text" placeholder="Nombre" wire:model="rnombre" required>
                    <div class="invalid-feedback" id="error-rnombre"></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="exampleFormControlTextarea1">Apellidos</label>
                    <input class="form-control" id="rapellidos" type="text" placeholder="Apellidos" wire:model="rapellidos" required>
                    <div class="invalid-feedback" id="error-rapellidos"></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="exampleFormControlTextarea1">Telefono</label>
                    <input class="form-control" id="rtelefono" type="text" placeholder="Telefono" wire:model="rtelefono" required>
                    <div class="invalid-feedback" id="error-rtelefono"></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="exampleFormControlTextarea1">Correo</label>
                    <input class="form-control" id="remail" type="text" placeholder="Correo" wire:model="remail" required>
                    <div class="invalid-feedback" id="error-remail"></div>
                </div>
                <div class="col-md-12 d-flex gap-1">
                    <button type="submit" class="btn border-dashed-success" id="saveDataCustomer" wire:loading.attr="disabled" wire:target="updateCustomer">
                        Guardar <i wire:loading class="fa-solid fa-circle-notch fa-spin" style="color: var(--theme-default)"></i>
                    </button>
                    <button type="button" class="btn border-dashed-danger" id="cancelDataCustomer">Cancelar</button>
                </div>
            </form>
        `;

        // Inserta los detalles del cliente en el modal
        $("#detail-customer").append(detailsCustom);

        // Construye la tabla de productos reservados
        let productRows = '';
        if (Array.isArray(event.reservation.carros)) {
            event.reservation.carros.forEach(element => {
                productRows += `
                    <tr class="border-bottom-info" wire:key="vehicle-${element.id}">
                        <th scope="row">${element.nombre}</th>
                        <td>${element.pivot?.total_reservas ?? ''}</td>
                    </tr>
                `;
            });
        }

        // Construye la tabla de personas asociadas a la reservación
        let personRows = '';
        if (Array.isArray(event.reservation.persons)) {
            event.reservation.persons.forEach((v, k) => {
                personRows += `
                    <tr class="border-bottom-info" wire:key="persons-${v.id}">
                        <th scope="row">Buggy (${k + 1})</th>
                        <td>${v.persons} personas</td>
                    </tr>
                `;
            });
        }

        // Inserta los productos y personas en la tabla del modal
        $("#details-product").append(productRows + personRows);

        // Muestra el modal de detalles de la reservación
        $("#detailsReservation").modal("show");
    });

    $(document).on("click","#editCustomer", function() {
        let reservation_id =  $(this).data('id');
        console.log("Editando cliente con ID: " + reservation_id);
        // $(this).addClass("d-none");
        $("#formCustomer").removeClass("d-none");
        $("#data-customer").addClass('d-none');
        // $wire.dispatch('editCustomer', { reservation_id: reservation_id });
    });

    $(document).on("click","#cancelDataCustomer", function() {
        // console.log("Guardando datos del cliente");
        $("#formCustomer").addClass("d-none");
        $("#data-customer").removeClass('d-none');
    });

    $wire.on('show-modal-update-date', (event) => {
        // Limpia el contenido previo del modal para evitar duplicados o datos obsoletos
        console.log(event, "Evento para mostrar el modal de actualización de fecha y hora");
        @this.reservation_id = event.reservation_id;
        $("#titleUpdateDateReservation").html('Actualizar fecha y hora de la reservación #' + event.reservation_id);
        $("#updateDateReservation").modal("show");

    });

    $wire.on('show-modal-add-notes',(event) => {
        // Limpia el contenido previo del modal para evitar duplicados o datos obsoletos
        console.log(event, "Evento para mostrar el modal de agregar notas");
        @this.reservation_id = event.reservation_id;
        $("#titleObservationsRes").html(`Agregar nota a la reservación #${event.reservation_id}`);
        $("#addNotesRes").modal("show");
    });

    $wire.on('generate-qr-reservation', (event) => {
        // Limpia el contenido previo del modal para evitar duplicados o datos obsoletos
        console.log(event, "Evento para generar QR de la reservación");

        @this.reservation_id = event.reservation_id;
        Swal.fire({
            title: "¿Está seguro de generar un regalo??",
            text: "Una vez generado, se cancelará la reservación actual y se creará una codigo QR de regalo.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#16C7F9",
            cancelButtonColor: "#FC4438",
            confirmButtonText: "Si, generalo!",
          }).then((result) => {
            if (result.isConfirmed) {
                $("#generateQrReservation").modal("show");
                $("#titleGenerateQr").html(`Generar QR de regalo para la reservación #${event.reservation_id}`);
                // $wire.dispatch('generateQrReservation', { reservation_id: event.reservation_id });
            //   Swal.fire({
            //     title: "Deleted!",
            //     text: "Your file has been deleted.",
            //     icon: "success",
            //   });
            }
          });
    });

    // Escucha el evento 'notify' de Livewire
    $wire.on('notify', (event) => {
        // Manejadores para cada método posible
        const handlers = {
            // Cuando se agrega una nota
            addNote: () => {
                $("#addStatusRes").modal("hide"); // Cierra el modal
                $wire.$refresh(); // Refresca el componente
                Toast.fire({
                    icon: event.type,
                    title: event.msj,
                });
            },
            // Cuando se actualiza el cliente
            updateCustomer: () => {
                $("#detailsReservation").modal("hide"); // Cierra el modal
                $wire.$refresh(); // Refresca el componente
                Toast.fire({
                    icon: event.type,
                    title: event.msj,
                });
            },
            // Cuando hay errores de validación en el formulario de cliente
            errorValidationFormCustomer: () => {
                // Limpia errores previos
                $("#formCustomer .form-control").removeClass("is-invalid");
                $("#formCustomer .invalid-feedback").text("");
                // Muestra los nuevos errores
                Object.entries(event.errors).forEach(([key, messages]) => {
                    $("#" + key).addClass("is-invalid");
                    $("#error-" + key).text(messages[0]).css({
                        "display": "block",
                        "color": "var(--bs-form-invalid-color) !important",
                    });
                });
            },
            errorUpdateDateReservation:() => {
                // Limpia errores previos
                Toast.fire({
                    icon: event.type,
                    title: event.msj,
                });
            },
            updateDateReservation: () => {
                $("#updateDateReservation").modal("hide"); // Cierra el modal
                $wire.$refresh(); // Refresca el componente
                Toast.fire({
                    icon: event.type,
                    title: event.msj,
                });
            },
            resendMail:() => {

                Toast.fire({
                    icon: event.type,
                    title: event.msj,
                });
            },
            sendMailRefund:() => {
                Toast.fire({
                    icon: event.type,
                    title: event.msj,
                });
            },
            addObservations: () => {
                $("#addNotesRes").modal("hide"); // Cierra el modal
                $wire.$refresh(); // Refresca el componente
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

    $(document).ready(function () {
        flatpickr("#human-friendly", {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            mindate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                // Actualiza el modelo de Livewire con la nueva fecha seleccionada
                @this.set('dateResUp', dateStr);
            }
        });
    });
</script>
@endscript
