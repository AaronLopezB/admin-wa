(function ($) {
    "use strict";
    var notify = $.notify(
        '<i class="fa-regular fa-bell"></i><strong>Cargando </strong> No cerrar esta página...',
        {
            type: "theme",
            allow_dismiss: true,
            delay: 2000,
            showProgressbar: true,
            timer: 300,
            animate: {
                enter: "animated fadeInDown",
                exit: "animated fadeOutUp",
            },
        }
    );

    // setTimeout(function () {
    //     notify.update(
    //         "message",
    //         '<i class="fa-regular fa-bell"></i><strong>Loading</strong> Inner Data.'
    //     );
    // }, 1000);
})(jQuery);
