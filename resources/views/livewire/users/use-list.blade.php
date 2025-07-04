<div>
    <div class="col-12">
        <div class="card">
            <div class="card-header card-no-border text-end">
                <div class="card-header-right-icon"><a class="btn btn-primary f-w-500" href="add-user.html"><i
                            class="fa-solid fa-plus pe-2"></i>Add User</a></div>
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
                                                    wire:click.prevent="$dispatch('update-pass', {reservation_id:{{$item->id}}} )">Cambiar Contraseña</button>
                                                <button class="dropdown-item"
                                                    wire:click.prevent="$dispatch('update-role',{reservation_id:{{$item->id}}})">Cambiar role</button>
                                                <button class="dropdown-item"
                                                    wire:click="$dispatch('update-role',{reservation_id:{{ $item->id }}})">Desactivar</button>

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
</div>
@script
<script>
    $wire.on('show-user',(event) =>{
        console.log(event);
        window.location.href = `${uri}users/${event.user_id}`;
    });
</script>
@endscript
