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
        // $("#searchForm").keypress(function (e) {
        //     e.preventDefault();
        //     console.log(e.target.value);
        //     if (e.keyCode === 13) {

        //         if (e.target.value) {
        //         $("body").addClass("offcanvas");
        //         } else {
        //         $("body").removeClass("offcanvas");
        //         }
        //     }
        // });
        $("#searchForm").on('keydown', function (e) {
            if (e.key == 'Enter') {

                e.preventDefault();
                const input = $("input[name='search']").val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('search') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {search:input},
                    // dataType: "json",
                    beforeSend:function() {
                        $('.Typeahead-spinner').show();
                        $("#closeSerch").hide();
                        $("body").addClass("offcanvas");
                    },
                    success: function (response) {
                        $(".Typeahead-menu").show();
                        let items = '';
                        if (response.length > 0) {
                            console.log(response);
                        }
                        else {
                            items += `
                                <li>No se encontraron resultados</li>
                            `;
                        }
                        $("#data-search").html(items);
                    },
                    complete: function () {
                        $('.Typeahead-spinner').hide();
                        $("#closeSerch").show();
                    }
                });
                console.log('hola',input);
            }

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
