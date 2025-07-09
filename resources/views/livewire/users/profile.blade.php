<div>
    <div class="user-profile">
        <div class="row">
            <!-- user profile first-style start-->
            <div class="col-sm-12">
                <div class="card hovercard text-center common-user-image">
                    <div class="cardheader">
                        <div class="user-image">
                            <div class="avatar">
                                <div class="common-align">
                                    <div><img id="output" src="{{ asset('imgs/user2.png') }}"
                                            alt="Profile Image"><input type="file" accept="image/*"
                                            onchange="loadFile(event)">
                                        <div class="icon-wrapper" id="cancelButton"><i
                                                class="icofont icofont-error"></i></div>
                                        <div class="icon-wrapper"><i class="icofont icofont-pencil-alt-5"></i></div>
                                    </div>
                                    <div class="user-designation"><a target="_blank" href="">{{ $user->name }}</a>
                                        <div class="desc">{{ $user->getRoleNames()[0] }}</div>
                                    </div>
                                </div>
                                {{-- <div class="follow">
                                    <div>
                                        <div class="follow-num counter" data-target="258690">0</div>
                                        <span>Follower</span>
                                    </div>
                                    <div>
                                        <div class="follow-num counter" data-target="659887">0</div>
                                        <span>Following</span>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- user profile first-style end-->
            <div class="col-12">
                <div class="card user-bio">
                    <div class="card-body">
                        <div class="row g-3">
                            {{-- <div class="col-12">
                                <div class="ttl-info text-start">
                                    <h6> <i class="fa-solid fa-user-tie pe-2"></i> Bio</h6><span class="mb-sm-3">Over
                                        five years of experience creating visually
                                        attractive and user-friendly websites has given me a passion for
                                        creating unique and creative websites. skilled in fusing
                                        cutting-edge online technologies with beautiful design concepts
                                        to create amazing user experiences. robust history in front-end
                                        development, UI/UX, and graphic design. highly skilled at
                                        solving problems, has a keen eye for detail, and can collaborate
                                        with others in a hectic setting.</span>
                                </div>
                            </div> --}}
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="ttl-info text-start">
                                    <h6><i class="fa-solid fa-envelope pe-2"></i> Email</h6>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="ttl-info text-start">
                                    <h6><i class="fa-solid fa-calendar-days pe-2"></i>Created</h6>
                                    <span>{{ $user->created_at }}</span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="ttl-info text-start">
                                    <h6><i class="fa-solid fa-shield pe-2"></i>Role</h6><span>
                                        {{ $user->getRoleNames()[0] }}</span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="ttl-info text-start pb-0">
                                    <h6><i class="fa-solid fa-tag pe-2"></i>Estatus</h6>
                                    <span>{{ $user->status }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- user profile menu start-->
            <div class="col-12">
                <div class="row scope-bottom-wrapper user-profile-wrapper">
                    <div class="col-xxl-3 user-xl-25 col-xl-4 box-col-4">
                        <div class="card">
                            <div class="card-body">
                                <ul class="sidebar-left-icons nav nav-pills" id="add-product-pills-tab" role="tablist">
                                    {{-- <li class="nav-item"> <a class="nav-link active" id="target-project-tab"
                                            data-bs-toggle="pill" href="#target-project" role="tab"
                                            aria-controls="target-project" aria-selected="false">
                                            <div class="nav-rounded">
                                                <div class="product-icons"><i class="fa-solid fa-timeline"></i></div>
                                            </div>
                                            <div class="product-tab-content">
                                                <h6>Recent Activity</h6>
                                            </div>
                                        </a></li>
                                    <li class="nav-item"> <a class="nav-link" id="budget-project-tab"
                                            data-bs-toggle="pill" href="#budget-project" role="tab"
                                            aria-controls="budget-project" aria-selected="false">
                                            <div class="nav-rounded">
                                                <div class="product-icons"><i class="fa-solid fa-list-check"></i></div>
                                            </div>
                                            <div class="product-tab-content">
                                                <h6>Tasks</h6>
                                            </div>
                                        </a></li> --}}
                                        <li class="nav-item"><a class="nav-link active" id="attachment-tab" data-bs-toggle="pill"
                                            href="#attachment" role="tab" aria-controls="attachment"
                                            aria-selected="false">
                                            <div class="nav-rounded">
                                                <div class="product-icons"><i class="fa-solid fa-gears"></i></div>
                                            </div>
                                            <div class="product-tab-content">
                                                <h6>Ajustes</h6>
                                            </div>
                                        </a></li>
                                    <li class="nav-item {{ auth()->user()->hasRole('Admin') ? '':'d-none' }}">
                                        <a class="nav-link" id="team-project-tab" data-bs-toggle="pill"
                                            href="#team-project" role="tab" aria-controls="team-project"
                                            aria-selected="false">
                                            <div class="nav-rounded">
                                                <div class="product-icons"><i class="fa-regular fa-bell"></i></div>
                                            </div>
                                            <div class="product-tab-content">
                                                <h6>Eliminar / Desactivar cuenta</h6>
                                            </div>
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-9 user-xl-75 col-xl-8 box-col-8e">
                        <div class="row">
                            <div class="col-12">
                                <div class="tab-content" id="add-product-pills-tabContent">

                                    <div class="tab-pane fade show active" id="attachment" role="tabpanel"
                                        aria-labelledby="attachment-tab">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Ajustes</h5>
                                            </div>
                                            <div class="card-body setting-wrapper">
                                                <form wire:submit.prevent="updateUser({{ $user->id }})" class="row g-3 needs-validation" id="formCustomer">
                                                    <input type="hidden" wire:model="reservation_id">
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label" for="validationDefault04">Nombre</label>
                                                        <input class="form-control @error('name') is-invalid @enderror" type="text" placeholder="{{ $user->name }}" wire:model="name" required>
                                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="exampleFormControlTextarea1">Correo</label>
                                                        <input class="form-control @error('email') is-invalid @enderror" type="text" placeholder="{{ $user->email }}" wire:model="email" required>
                                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                    </div>
                                                    <div class="form-group position-relative col-md-6">
                                                        <label class="col-form-label">Password</label>
                                                        <div class="form-input">
                                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" wire:model="password"  required autocomplete="current-password"
                                                                placeholder="*********">
                                                            {{-- <div class="show-hide"><span class="show"> </span></div> --}}
                                                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group position-relative col-md-6">
                                                        <label class="col-form-label">Confirmacion de contraseña</label>
                                                        <div class="form-input">
                                                            <input id="password" type="password" class="form-control @error('password_confirmation') is-invalid @enderror"  wire:model="password_confirmation"  required
                                                                placeholder="*********">
                                                            <div class="show-hide"><span class="show"> </span></div>
                                                            @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="exampleFormControlTextarea1">Role</label>
                                                        <select class="form-select @error('role') is-invalid @enderror" required="" wire:model="role">
                                                            <option selected value>Seleccione...</option>
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                    </div>
                                                    <div class="col-md-12 text-center">
                                                        <button type="submit" class="btn border-dashed-success" id="saveDataCustomer" wire:loading.attr="disabled" wire:target="updateCustomer">
                                                            Guardar <i wire:loading class="fa-solid fa-circle-notch fa-spin" style="color: var(--theme-default)"></i>
                                                        </button>
                                                        {{-- <button type="button" class="btn border-dashed-danger" id="cancelDataCustomer">Cancelar</button> --}}
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade {{ auth()->user()->hasRole('Admin') ? '':'d-none' }}" id="team-project" role="tabpanel"
                                        aria-labelledby="team-project-tab">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Eliminar / Desactivar cuenta</h5>
                                            </div>
                                            <div class="card-body row">
                                                <div class="row g-md-3 g-2">
                                                    <div class="col-md-3"><label class="form-label mb-0">Eliminar cuenta</label></div>
                                                    <div class="col-md-9">

                                                        <div class="common-flex"><a
                                                                class="btn button-light-danger {{ auth()->user()->hasRole('Admin')  && $user->status !== 'deactivate'  ? '' : 'disabled' }}"
                                                                @if(auth()->user()->hasRole('Admin') && $user->status !== 'deactivate')
                                                                    wire:click="$dispatch('disableAccount',{user_id:{{ $user->id }}})"
                                                                @else
                                                                    href="#!"
                                                                @endif
                                                                role="button">Desactivar cuenta</a><a class="btn btn-danger" href="#!"
                                                                role="button">Eliminar cuenta</a></div>
                                                    </div>
                                                </div>
                                                <div class="row g-md-3 g-2">
                                                    <div class="col-md-3"><label class="form-label mb-0">Activar cuenta</label></div>
                                                    <div class="col-md-9">

                                                        <div class="common-flex "><a
                                                                class="btn button-light-info {{ auth()->user()->hasRole('Admin') && $user->status == 'deactivate' ? '' : 'disabled' }}"
                                                                @if($user->status == 'deactivate')
                                                                    {{-- wire:click="$dispatch('enableAccount')" --}}
                                                                    wire:click="$dispatch('activeAccount',{user_id:{{ $user->id }}})"
                                                                @else
                                                                    href="#!"
                                                                @endif
                                                                role="button">Active cuenta</a>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- user profile menu end-->
        </div>
    </div>
</div>
@script
<script>
    console.log('Livewire component loaded');
    $(document).ready(function () {
        $(".show-hide").show();
        $(".show-hide span").addClass("show");

        $(".show-hide span").click(function () {
            if ($(this).hasClass("show")) {
            $('body #password').attr("type", "text");
            $(this).removeClass("show");
            } else {
            $('body #password').attr("type", "password");
            $(this).addClass("show");
            }
        });
        $('form button[type="submit"]').on("click", function () {
            $(".show-hide span").addClass("show");
            $(".show-hide").parent().find('#password').attr("type", "password");
        });
    });

    $wire.on('disableAccount', (event) => {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción desactivará tu cuenta.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, desactivar'
        }).then( async (result) => {
            if (result.isConfirmed) {

                const { value: password } = await Swal.fire({
                    title: "Ingrese su contraseña",
                    text: "Para confirmar la desactivación de la cuenta, ingrese su contraseña.",
                    input: "password",
                    inputLabel: "Password",
                    inputPlaceholder: "Ingrese su contraseña",
                    inputAttributes: {
                        // maxlength: "10",
                        autocapitalize: "off",
                        autocorrect: "off",
                    },
                });
                if (password) {

                    @this.customerPasword = password; // Asigna la contraseña ingresada al componente Livewire
                    $wire.dispatch('disableAccountUser',{id:event.user_id});
                }
            }
        });
    });

    $wire.on('activeAccount', (event) => {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción activará tu cuenta.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, activar'
        }).then((result) => {
            if (result.isConfirmed) {
                $wire.dispatch('activeAccountUser',{id:event.user_id});
            }
        });
    });


    $wire.on('alert', (event) => {
        console.log('Event received:', event);

        // Manejadores para cada método posible
        const handlers = {
            // Cuando se agrega una nota
            updateUser: () => {
                if (event.type === 'success') {
                    // Si el tipo es success, recarga la página
                    $wire.$refresh(); // Refresca el componente
                }
                Toast.fire({
                    icon: event.type,
                    title: event.msj,
                });
            },
            disableAccountCurrend: () => {
                Swal.fire({
                    title: 'Cuenta desactivada',
                    text: 'Tu cuenta ha sido desactivada correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    // auth()->logout(); // Cierra la sesión del usuario

                    $wire.dispatch('logoutUser') // Cierra la sesión del usuario usando Livewire
                    $wire.$refresh(); // Recarga el componente

                });
            },
            disableAccountCurrendError: () => {

                Swal.fire({
                    title: 'Error al desactivar la cuenta',
                    text: event.msj,
                    icon: event.type,
                    confirmButtonText: 'Aceptar'
                });

            },
            activeAccountCurrend: () => {
                Swal.fire({
                    title: event.title,
                    text: event.msj,
                    icon: event.type,
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    // auth()->logout(); // Cierra la sesión del usuario
                    if (event.type === 'success') {
                        // Si el tipo es success, recarga la página
                        $wire.$refresh(); // reloadn component

                    }
                });
            },
            logoutUser: () => {
                Swal.fire({
                    title: event.title,
                    text: event.msj,
                    icon: event.type,
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    // Redirige a la página de inicio o a donde sea necesario
                    if (event.type === 'success') {
                        // Si el tipo es success, recarga la págin
                        location.reload(); // Recarga la página
                        // window.location.href = '/';
                    }
                });
            },
        };
        // Ejecuta el manejador correspondiente o muestra advertencia si no existe
        (handlers[event.method] || (() => {
            Toast.fire({
                icon: "warning",
                title: 'Acción no reconocida',
            });
        }))();
    });
</script>
@endscript
