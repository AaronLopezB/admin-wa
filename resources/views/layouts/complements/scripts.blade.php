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

 @stack('scripts')

@livewireScripts
