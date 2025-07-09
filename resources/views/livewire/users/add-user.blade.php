<div>
    <form wire:submit.prrevent="createUser">
        <div class="row">

            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Nuevo perfil</h5>
                        <div class="card-options"><a class="card-options-collapse" href="#"
                                data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a
                                class="card-options-remove" href="#" data-bs-toggle="card-remove"><i
                                    class="fe fe-x"></i></a></div>
                    </div>
                    <div class="card-body">
                        <form class="custom-input">
                            {{-- <div class="row mb-2">
                                    <div class="profile-title">
                                        <div class="d-flex"> <img class="img-70 rounded-circle" alt=""
                                                src="../assets/images/user/7.jpg">
                                            <div class="flex-grow-1">
                                                <h5 class="mb-1">WILLIAM C. JENNINGS</h5>
                                                <p>DESIGNER</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <h6 class="form-label">Bio</h6><textarea class="form-control" rows="5"
                                        placeholder="On the other hand, we denounce with righteous indignation"></textarea>
                                </div> --}}
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input class="form-control @error('name') is-invalid @enderror" type="text" placeholder="your user"
                                    wire:model="name">
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input class="form-control @error('email') is-invalid @enderror" type="email" placeholder="your-email@domain.com"
                                    wire:model="email">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input class="form-control @error('password') is-invalid @enderror" type="password" placeholder="**********"
                                    wire:model="password">
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirmar password</label>
                                <input class="form-control @error('password_confirmation') is-invalid @enderror" type="password" placeholder="**********"
                                    wire:model="password_confirmation">
                                    @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Datos extra</h5>
                        <div class="card-options"><a class="card-options-collapse" href="#"
                                data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a
                                class="card-options-remove" href="#" data-bs-toggle="card-remove"><i
                                    class="fe fe-x"></i></a></div>
                    </div>
                    <div class="card-body">
                        <div class="row custom-input">
                            <div class="col-xxl-5 box-col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="companyName">Locacion</label>
                                    <select class="form-control btn-square  @error('location') is-invalid @enderror" id="customCountry" wire:model="location">
                                        <option value="0">--Select--</option>
                                        <option value="orlando">Orlando</option>
                                        <option value="miami">Miami</option>
                                        <option value="davenport">Davenport</option>
                                        <option value="madrid">Madrid</option>
                                    </select>
                                    @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-sm-6 col-xxl-3 box-col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="keyUser">Clave del vendedor</label>
                                    <input class="form-control @error('key') is-invalid @enderror" id="keyUser" type="text"
                                        placeholder="WA-test25#" wire:model="key">
                                        @error('key') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-sm-6 col-xxl-4 box-col-6">
                                <div class="mb-3 form-check form-switch form-check-inline">
                                    <label class="form-label" for="customAddress"> Activar </label>
                                <input class="form-check-input check-size" id="flexSwitchCheckDefault2" type="checkbox" role="switch" wire:model="status" wire:checked='status'>
                                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Roles y permisos</h5>
                        <div class="card-options"><a class="card-options-collapse" href="#"
                                data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a
                                class="card-options-remove" href="#" data-bs-toggle="card-remove"><i
                                    class="fe fe-x"></i></a></div>
                    </div>
                    <div class="card-body" wire:ignore>
                        <div class="vertical-scroll scroll-demo scroll-b-none">

                            <div class="row custom-input">
                                <div class="col-xxl-5 box-col-12">
                                    <div class="mb-3"><label class="form-label" for="customCountry">Role</label>
                                        <select class="form-control btn-square @error('role') is-invalid @enderror" id="customCountry"
                                            wire:change="showPermissions($event.target.value)" wire:model="role">
                                            <option>--Select--</option>
                                            @foreach ($roles as $item)

                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-xxl-5 box-col-12" wire:ignore>
                                    <label class="form-label" for="customCountry">Permisos asignados al rol</label>

                                    <div id="permissions-list">

                                    </div>
                                    {{-- @foreach ($permissions as $permission)
                                            <div class="form-check">
                                                <input class="form-check-input" id="permission-{{$item->id}}"
                                    type="checkbox" value="{{ $item->name }}" disabled >
                                    <label class="form-check-label"
                                        for="permission-{{$item->id}}">{{ $permission->name }}</label>
                                </div>
                                @endforeach --}}


                            </div>

                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button class="btn btn-primary" type="submit">Crear Usuario</button>
                </div>
            </div>

        </div>
    </form>
</div>
@assets
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/scrollable.css')}}">
    <script src="{{asset('assets/js/scrollable/perfect-scrollbar.min.js')}}"></script>
@endassets
@script
<script>
    $wire.on('showPermissions',(event) => {
        let permissionList = '';
        if (event.permissions.length > 0) {
            event.permissions.forEach(element => {
                permissionList += `
                                            <div class="form-check">
                                                <input class="form-check-input" id="permission-${element.id}" type="checkbox" value="${element.name}" disabled checked>
                                                <label class="form-check-label" for="permission-${element.id}">${element.name}</label>
                                            </div>`;
            });
        } else {
            permissionList = '<p>No hay permisos asignados a este rol.</p>';
        }
        document.getElementById('permissions-list').innerHTML = permissionList;
    });

    $wire.on('alert', (event) => {
        console.log('Event received:', event);

        // Manejadores para cada método posible
        const handlers = {
            // Cuando se agrega una nota
            createUser: () => {
                if (event.type === 'success') {
                    // Si el tipo es success, recarga la página
                    $wire.$refresh(); // Refresca el componente
                    setTimeout(() => {
                        location.href = `${uri}users`;
                    }, 3000); // Espera 3 segundos antes de recargar
                }
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
</script>
@endscript
