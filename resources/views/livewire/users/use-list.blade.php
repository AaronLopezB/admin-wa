<div>
    <div class="col-12">
        <div class="card">
            <div class="card-header card-no-border text-end">
                <div class="card-header-right-icon"><a class="btn btn-primary f-w-500" wire:click.prevent="$dispatch('addUser')"><i
                            class="fa-solid fa-plus pe-2"></i>Agregar usuario</a></div>
            </div>
            <div class="card-body pt-0 px-0">
                <div class="list-product user-list-table">
                    <div class="table-responsive custom-scrollbar">
                        <table class="table border-bottom-table">
                            <thead>
                                <tr class="border-bottom-dark">
                                    <th scope="col">Folio</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Correo</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Estatus</th>
                                    <th scope="col">Creado</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($user as $item)
                                <tr class="border-bottom-{{ $item->color_status }}" wire:key="user-{{$item->id}}">
                                    <th scope="row">{{ $item->id }}</th>
                                    <td> {{-- <img class="img-30 me-2" src="../assets/images/user/1.jpg" alt="profile"> --}}
                                        {{ $item->name }}
                                    </td>
                                    <td>{{ $item->email }}</td>
                                    <td>
                                        <span class="badge common-align txt-{{ $item->color_role }} rounded-pill badge-l-{{ $item->color_role }} rounded-pill border border-{{ $item->color_role }} ">{!! $item->role_name !!}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-{{ $item->color_status }}"><i class=""></i>{{ $item->status }}</span>

                                    </td>
                                    <td>
                                        {{ $item->created_format }}
                                    </td>

                                    <td>
                                        <div class="dropdown icon-dropdown">
                                            <button class="btn dropdown-toggle" id="dealDropdown1" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false"><i
                                                    class="icon-more-alt"></i></button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dealDropdown1">

                                                <button class="dropdown-item"

                                                    wire:click.prevent="$dispatch('show-user', {user_id:{{$item->id}}} )">Detalles del usuario</button>
                                                <button class="dropdown-item"
                                                    wire:click.prevent="$dispatch('update-pass', {user_id:{{$item->id}}} )">Cambiar Contraseña</button>
                                                <button class="dropdown-item"
                                                    wire:click.prevent="$dispatch('update-role',{user_id:{{$item->id}}})">Cambiar role</button>
                                                <button class="dropdown-item"
                                                    wire:click="$dispatch('update-status',{user_id:{{ $item->id }}})">{{ ($item->status != 'deactivate') ? 'Desactivar':'Activar' }}</button>

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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modalUpdatePassword" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalgetbootstrap" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div
                    class="modal-toggle-wrapper social-profile text-start dark-sign-up">
                    <h3 class="modal-header justify-content-center border-0">Cambiar contraseña</h3>
                    <div class="modal-body">
                        <form class="row g-3 needs-validation" wire:submit.prevent="editPassword">
                            <input type="hidden" id="user_id" wire:model="user_id">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="exampleFormControlInput1">Nueva contraseña</label>
                                        <input class="form-control @error('password') is-invalid @enderror" id="exampleFormControlInput1" type="password" wire:model="password" placeholder="*********">
                                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="exampleFormControlInput1">Confirmar contraseña</label>
                                    <input class="form-control @error('password_confirmation') is-invalid @enderror" id="exampleFormControlInput1" type="password" wire:model="password_confirmation" placeholder="*********">
                                    @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-primary" type="submit"> Cambiar contraseña </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="modalShowRoles" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalgetbootstrap" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div
                    class="modal-toggle-wrapper social-profile text-start dark-sign-up">
                    <h3 class="modal-header justify-content-center border-0">Cambiar contraseña</h3>
                    <div class="modal-body">
                        <form class="row g-3 needs-validation" wire:submit.prevent="updateRole">
                            <input type="hidden" id="user_id" wire:model="user_id">
                            <div class="col-md-12">
                                <div class="mb-3" wire:ignore>
                                    <label class="form-label" for="allRoles">Nueva contraseña</label>
                                        <select class="form-select" required="" wire:model="role" id="allRoles">
                                        </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-primary" type="submit"> Cambiar rol </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@script
<script>
    $wire.on('show-user',(event) =>{
        window.location.href = `${uri}users/${event.user_id}`;
    });
    $wire.on('addUser', () => {
        window.location.href = `${uri}users/create`;
    })
    $wire.on('update-pass',(event) =>{
        $("#modalUpdatePassword").modal('show');
        $wire.set('user_id',event.user_id);
    });
    $wire.on('validUser', async (event) => {
        $("#modalUpdatePassword").modal('hide');
        const { value: password } = await Swal.fire({
            title: "Ingrese su contraseña",
            text: "Para confirmar el cambio de la contraseña, ingrese su contraseña.",
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
            console.log(password);

            @this.authPassword = password; // Asigna la contraseña ingresada al componente Livewire
            $wire.dispatch('updatePassword');
        }
    });
    $wire.on('showRoles',(event) => {
        // console.log(event);

        @this.user_id = event.user_id
        let roles = '';
        event.roles.forEach(element => {
            roles += `<option value="${element.name}">${element.name}</option>`
        });

        $("#allRoles").html(`<option selected value>Seleccione...</option>${roles}`);
        $("#modalShowRoles").modal('show');
    });

    $wire.on('update-status', (event) => {
        Swal.fire({
            title: '¿Está seguro?',
            text: "¿Desea cambiar el estatus del usuario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cambiar estatus'
        }).then((result) => {
            if (result.isConfirmed)
            {
                console.log(event,'update Status');
                $wire.dispatch('updateStatus', { user_id: event.user_id });
            }
        });
    });

    $wire.on('alert', (event) => {
        console.log('Event received:', event);
        // Manejadores para cada método posible
        const handlers = {
            // Cuando se agrega una nota
            updatePassword: () => {

                Toast.fire({
                    icon: event.type,
                    title: event.msj,
                });
                $wire.$refresh(); // Refresca el componente
            },
            updatePasswordError: () => {

                Toast.fire({
                    icon: event.type,
                    title: event.msj,
                });
                $wire.$refresh(); // Refresca el componente
            },
            updateRole:() => {
                $("#modalShowRoles").modal('hide');
                Toast.fire({
                    icon: event.type,
                    title: event.msj,
                });
                setTimeout(() => {

                    $wire.$refresh();
                }, timeout = 2000);
            },
            updateStatus:() => {

                Toast.fire({
                    icon: event.type,
                    title: event.msj,
                });
                if (event.type == 'success') {

                    setTimeout(() => {

                        $wire.$refresh();
                    }, timeout = 2000);
                }
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
</script>
@endscript
