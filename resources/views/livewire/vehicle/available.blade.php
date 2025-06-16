<div>
    <div class="row seller-wrapper">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="availableVehicle" class="row g-3">
                        <div class="row g-3 custom-input">
                            <div class="col-xl col-md-6" >
                                <label class="form-label" for="datetime-local">Fecha: </label>
                                <div class="input-group flatpicker-calender" wire:ignore>
                                    <input class="form-control flatpickr-input" id="human-friendly" placeholder="dd/mm/yyyy"
                                        type="text" readonly="readonly" wire:model="date" >
                                    </div>
                                    @error('date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-xl col-md-6">
                                <label class="form-label">Hora</label>
                                <select class="form-select" wire:model="hour">
                                    <option selected="" value="">Seleccione</option>
                                    <option value="10:00">10:00</option>
                                    <option value="15:00">15:00</option>
                                    <option value="18:00">18:00</option>
                                </select>
                                @error('hour') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                            <div class="col d-flex justify-content-start align-items-center m-t-40">
                                <button type="submit" wire:click.prevent="availableVehicle" wire:loading.attr="disabled" wire:target="availableVehicle"
                                    class="btn btn-primary f-w-500" >Validar</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <div class="product-grid">

        <div class="row gy-3 d-none" wire:loading.class.remove="d-none" wire:target="availableVehicle">
            <div class="col-sm-6">
                        <div class="placeholder-body">
                        <div class="placeholder-start">
                            <div class="square"></div>
                        </div>
                        <div class="placeholder-end">
                            <div class="placeholder-line placeholder-h-17 w-25 mb-2"></div>
                            <div class="placeholder-line"></div>
                            <div class="placeholder-line placeholder-h-8 w-50"></div>
                            <div class="placeholder-line w-75"></div>
                        </div>
                        </div>
            </div>
            <div class="col-sm-6">
                        <div class="placeholder-body">
                        <div class="placeholder-start">
                            <div class="square"></div>
                        </div>
                        <div class="placeholder-end">
                            <div class="placeholder-line placeholder-h-17 w-25 mb-2"></div>
                            <div class="placeholder-line"></div>
                            <div class="placeholder-line placeholder-h-8 w-50"></div>
                            <div class="placeholder-line w-75"></div>
                        </div>
                        </div>
            </div>
        </div>
        <div class="product-wrapper-grid" wire:loading.class="d-none" wire:target="availableVehicle">
            <livewire:vehicle.vehicles :vehicles="$vehicles" :key="'vehicles-'.now()->timestamp"  />
        </div>
    </div>
</div>
@script
<script>
    $(document).ready(function () {
        flatpickr("#human-friendly", {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            minDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                // Actualiza el modelo de Livewire con la nueva fecha seleccionada
                @this.set('date', dateStr);
            }
        });
    });
    $wire.on('alert', (event) => {
        console.log('Event received:', event);

        // Manejadores para cada método posible
        const handlers = {
            // Cuando se agrega una nota
            validateVehicle: () => {

                Toast.fire({
                    icon: event.type,
                    title: event.msj,
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
