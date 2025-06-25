<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class NetelipSms
{
    public function send($data)
    {
        $url = config('services.netelip.sms_url');
        $post = [
            "token"       => config('services.netelip.token'),
            "from"        => config('services.netelip.sender', 'ATV WA'),
            "destination" => $data['phone'],
            "message"     => $data['msj']
        ];

        $request = curl_init($url);
        curl_setopt($request, CURLOPT_POST, 1);
        curl_setopt($request, CURLOPT_POSTFIELDS, $post);
        curl_setopt($request, CURLOPT_TIMEOUT, 180);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($request);
        if ($response !== false) {
            $response_code = curl_getinfo($request, CURLINFO_HTTP_CODE);
            // dd($response_code);
            switch ($response_code) {
                case 200:
                    return ['reply' => 'success', 'msj' => 'Mensaje enviado con exito'];
                    break;
                default:
                    return ['reply' => 'error', 'msj' => 'No se pudo mandar el mensaje'];
                    // $msj ="Error";
            };
        } else {
            $errorMessage = curl_error($request);
            curl_close($request);
            throw new \RuntimeException("cURL error: $errorMessage");
            Log::error("Error al enviar el sms $errorMessage");
            return ['reply' => 'error', 'msj' => 'No se pudo mandar el mensaje'];
        }
    }
}
