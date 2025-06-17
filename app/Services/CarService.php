<?php

namespace App\Services;

use App\Models\Code;
use App\Models\ShoppingCar;
use InvalidArgumentException;
use Illuminate\Support\Facades\Cache;

class CarService
{
    public $identity;
    public function __construct()
    {
        $this->identity = Cache::get('identityCar') ?? $this->identity;
    }

    private function setIdentity()
    {
        $identifier = sha1('WorldAdv' . microtime(true) . date('dmY') . rand(11111, 99999));
        Cache::put('identityCar', $identifier, now()->addWeek());
        return $identifier;
    }

    public function getCar()
    {
        return ShoppingCar::with('carros', 'code')->where('identity', $this->identity)->get();
    }

    /**
     * Agrega un nuevo item al carrito o actualiza uno existente.
     *
     * @param array $data Datos del item a agregar o actualizar.
     * @return ShoppingCar El item agregado o actualizado.
     * @throws InvalidArgumentException Si la cantidad disponible es menor o igual a cero.
     */
    public function add($data)
    {
        // Si no hay identidad, la genera y la asigna
        if (is_null($this->identity)) {
            $this->identity = $this->setIdentity();
        }

        // Busca el primer item del carrito con la identidad actual
        $carItem = ShoppingCar::where('identity', $this->identity)->first();
        // dd($carItem, $data); // Descomentar para depuración

        // Si no existe ningún item en el carrito, crea uno nuevo
        if (!$carItem) {
            if ($data['available'] <= 0) {
                throw new InvalidArgumentException("La cantidad debe ser mayor a cero");
            }
            $item = new ShoppingCar([
                'identity' => $this->identity,
                'date' => $data['date'],
                'hour' => $data['hour'],
                'person_car' => json_encode($data['reservation']['carGuest']),
                'is_gift' => $data['is_gift'],
                'available' => $data['available'],
                'price' => $data['price'],
                'total' => $data['car']['total'],
                'totalGuests' => $data['reservation']['totalGuest'],
            ]);
            // Asocia el carro al item
            $item->carros()->associate($data['car']['id']);
            $item->save();
            return $item;
        }

        // Busca si ya existe un item con el mismo car_id
        $item = $carItem->where('car_id', $data['car']['id'])->first();
        if ($item) {
            // Si existe, actualiza sus datos
            $item->update([
                'date' => $data['date'],
                'hour' => $data['hour'],
                'person_car' => json_encode($data['reservation']['carGuest']),
                'is_gift' => $data['is_gift'],
                'available' => $data['available'],
                'price' => $data['price'],
                'total' => $data['car']['total'],
                'totalGuests' => $data['reservation']['totalGuest'],
            ]);
        } else {
            // Si no existe, crea un nuevo item
            if ($data['available'] <= 0) {
                throw new InvalidArgumentException("La cantidad debe ser mayor a cero");
            }
            $item = new ShoppingCar([
                'identity' => $this->identity,
                'date' => $data['date'],
                'hour' => $data['hour'],
                'person_car' => json_encode($data['reservation']['carGuest']),
                'is_gift' => $data['is_gift'],
                'available' => $data['available'],
                'price' => $data['price'],
                'total' => $data['car']['total'],
                'totalGuests' => $data['reservation']['totalGuest'],
            ]);
            // Asocia el carro al item
            $item->carros()->associate($data['car']['id']);
            $item->save();
        }
        return $item;
    }

    public function removeAllItem()
    {
        session()->forget('cli');
        return ShoppingCar::where('identity', $this->identity)->delete();
    }

    public function removeItem($id)
    {
        return ShoppingCar::where('identity', $this->identity)->where('id', $id)->delete();
    }

    public function addCode($code)
    {
        // dd($code);
        $car = ShoppingCar::where('identity', $this->identity)->get();
        foreach ($car as $item) {
            $item->code()->associate($code);
            $item->save();
        }
        return $car;
    }
}
