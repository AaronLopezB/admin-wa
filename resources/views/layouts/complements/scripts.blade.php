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
                    console.log(response);
                    $("#modal-lg").modal('show');
                },
                complete: function () {
                    $("#preloadSearch").addClass('d-none');
                }
            });

        });

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
