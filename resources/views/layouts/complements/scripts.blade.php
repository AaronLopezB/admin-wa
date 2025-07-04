 <!-- latest jquery-->
 <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
 <!-- Bootstrap js-->
 <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
 <!-- feather icon js-->
 <script src="{{ asset('assets/js/icons/feather-icon/feather.min.js') }}"></script>
 <script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js') }}"></script>
 <!-- scrollbar js-->
 <script src="{{ asset('assets/js/scrollbar/simplebar.min.js') }}"></script>
 <script src="{{ asset('assets/js/scrollbar/custom.js') }}"></script>
 <!-- Sidebar jquery-->
 {{-- <script src="{{ asset('assets/js/config.js') }}"></script> --}}
 <!-- Plugins JS start-->
 <script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
 <script src="{{ asset('assets/js/sidebar-pin.js') }}"></script>
 <script src="{{ asset('assets/js/slick/slick.min.js') }}"></script>
 <script src="{{ asset('assets/js/slick/slick.js') }}"></script>
 {{-- <script src="{{ asset('assets/js/header-slick.js') }}"></script> --}}
 <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>

 <script src="{{ asset('assets/js/script.js') }}"></script>
 {{-- <script src="{{ asset('assets/js/script1.js') }}"></script> --}}
 {{-- <script src="{{ asset('assets/js/theme-customizer/customizer.js') }}"></script> --}}
 <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
 <script  src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"   ></script>
 <script>
    const uri = '{!! asset('') !!}';
 </script>
 <script>

    const Toast = Swal.mixin({
      toast: true,
      position: "top",
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
      },
    });

 </script>

<script>
    $(document).ready(function () {
        $(".header-search").click(function () {
            $(".search-full").addClass("open");
        });
        $(".close-search").click(function () {
            $(".search-full").removeClass("open");
            $("body").removeClass("offcanvas");
        });
        $(".mobile-toggle").click(function () {
            $(".nav-menus").toggleClass("open");
        });
        $(".mobile-toggle-left").click(function () {
            $(".left-header").toggleClass("open");
        });
        $(".bookmark-search").click(function () {
            $(".form-control-search").toggleClass("open");
        });
        $(".filter-toggle").click(function () {
            $(".product-sidebar").toggleClass("open");
        });
        $(".toggle-data").click(function () {
            $(".product-wrapper").toggleClass("sidebaron");
        });
        $(".form-control-search input").keyup(function (e) {
            if (e.target.value) {
            $(".page-wrapper").addClass("offcanvas-bookmark");
            } else {
            $(".page-wrapper").removeClass("offcanvas-bookmark");
            }
        });

        // Maneja el evento 'keydown' en el formulario de búsqueda
        $("#searchForm").on('keydown', function (e) {
            // Solo ejecuta la búsqueda si se presiona Enter
            if (e.key === 'Enter') {
                e.preventDefault();
                const input = $("input[name='search']").val().trim();

                // Evita enviar la petición si el campo está vacío
                if (!input) return;

                $.ajax({
                    type: "POST",
                    url: "{{ route('search') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { search: input },
                    beforeSend: function () {
                        $('.Typeahead-spinner').show();
                        $("#closeSerch").hide();
                        $("body").addClass("offcanvas");
                    },
                    success: function (response) {
                        $(".Typeahead-menu").show();
                        let items = '';

                        // Verifica si hay resultados
                        if (Array.isArray(response.data) && response.data.length > 0) {
                            response.data.forEach(element => {
                                let button = `<button class="btn btn-pill border-dashed-primary btn-sm" data-id="${element.id}" data-model="${response.model}" id="showDetailsSearch">Ver</button>`;
                                switch (response.model) {
                                    case 'user':
                                        items += `<li><strong>#${element.id}</strong> ${element.name} - ${element.email} ${button}</li>`;
                                        break;
                                    case 'reservation':
                                        items += `<li><strong>#${element.id}</strong> ${element.nombre} ${element.apellidos} - ${element.email} ${button}</li>`;
                                        break;
                                    case 'product':
                                        items += `<li><strong>#${element.id}</strong> ${element.nombre} - (${element.identidicador}) locacion ${element.location} ${button}</li>`;
                                        break;
                                    case 'code':
                                        items += `<li><strong>#${element.id}</strong> ${element.codigo} - (${element.descuento}%) ${button}</li>`;
                                        break;
                                    default:
                                        items += `<li><strong>#${element.id}</strong> ${element.nombre ?? ''} ${element.apellidos ?? ''} - ${element.email ?? ''} ${button}</li>`;
                                }
                            });
                        } else {
                            items = `<li>No se encontraron resultados</li>`;
                        }
                        $("#data-search").html(items);
                    },
                    complete: function () {
                        $('.Typeahead-spinner').hide();
                        $("#closeSerch").show();
                    }
                });
            }
        });

        $(document).on('click','#showDetailsSearch', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let model = $(this).data('model');
            console.log(id,model,'busqueda');

            $.ajax({
                type: "GET",
                url: `${uri}show/details/search/${id}/${model}`,
                beforeSend:function() {
                    console.log('espere');
                    $("#preloadSearch").removeClass('d-none');

                },
                success: function (response) {
                    if (response.model == 'reservations') {
                        $("#modal-lg").modal('show');
                        modalDataRes(response);
                    }
                },
                complete: function () {
                    $("#preloadSearch").addClass('d-none');
                }
            });

        });

        function modalDataRes(response) {
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
            const statusCode = statusMap[response.data.estatusfintour] || { color: 'secondary', text: 'Sin estatus' };
            console.log(statusCode);
            // Agrega la cinta de estatus en el modal
            $("#statusReserv").append(
                `<div class="ribbon ribbon-${statusCode.color} ribbon-clip">${statusCode.text}</div>`
            );
            // URL base para archivos S3
            const urlS3 = "{{ config('secret.url_s3') }}";
            // Construye las notas de la reservación si existen
            let notes = '';
            if (response.data.note && response.data.note.length > 0) {
                notes = response.data.note.map(note => `
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
                        <a class="btn border-dashed-info ${!response.data.terminos ? 'd-none' : ''}"
                            href="${response.data.terminos && response.data.terminos.includes(urlS3)
                                ? response.data.terminos
                                : (response.data.terminos ? "http://world-adventures.us/" + response.data.terminos : '#')}">
                            Ver Licencia
                        </a>
                        <a class="btn border-dashed-info ${!response.data.licensia ? 'd-none' : ''}"
                            href="${response.data.licensia && response.data.licensia.includes(urlS3)
                                ? response.data.licensia
                                : (response.data.licensia ? "http://world-adventures.us/" + response.data.licensia : '#')}">
                            Ver Terminos y condiciones
                        </a>
                    </div>
                    <div class="col-md-6 d-flex flex-column gap-1">
                        <ul class="d-flex flex-column gap-1">
                            <li><strong>Nombre: </strong>${response.data.nombre ?? ''} ${response.data.apellidos ?? ''}</li>
                            <li><strong>Correo: </strong>${response.data.email ?? 'N/A'}</li>
                            <li><strong>Telefono: </strong>${response.data.telefono ?? 'N/A'}</li>
                        </ul>
                    </div>
                    ${response.data.note && response.data.note.length > 0 ? `
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
            if (Array.isArray(response.data.carros)) {
                response.data.carros.forEach(element => {
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
            if (Array.isArray(response.data.persons)) {
                response.data.persons.forEach((v, k) => {
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
        }

        // $("body").keydown(function (e) {
        //     if (e.keyCode == 27) {
        //     $(".search-full input").val("");
        //     $(".form-control-search input").val("");
        //     $(".page-wrapper").removeClass("offcanvas-bookmark");
        //     $(".search-full").removeClass("open");
        //     $(".search-form .form-control-search").removeClass("open");
        //     $("body").removeClass("offcanvas");
        //     }
        // });
    });

</script>

@stack('scripts')

@livewireScripts
