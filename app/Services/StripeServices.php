<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Traits\CunsumeSExternalServices;

class StripeServices
{
    use CunsumeSExternalServices;

    protected $baseUri;

    protected $key;

    protected $secret;

    public function __construct()
    {
        $this->baseUri = config('secret.stripe.base_uri');
        $this->key = config('secret.stripe.key');
        $this->secret = config('secret.stripe.secret');
    }

    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $headers['Authorization'] = $this->resolveAccesToken();
    }

    public function decodeResponse($response)
    {
        return json_decode($response);
    }

    public function resolveAccesToken()
    {
        return "Bearer {$this->secret}";
    }

    public function handlePayment($payment_method, $value)
    {
        $intent = $this->createIntent($value, config('secret.stripe.dv'), $payment_method);
        session()->put('paymentIntentId', $intent->id);
        return $intent;
    }

    public function handleApproval()
    {
        if (session()->has('paymentIntentId')) {
            $paymentIntentId = session()->get('paymentIntentId');
            $paymentIntent = $this->retrievePaymentIntent($paymentIntentId);
            // dd($paymentIntent);
            $confirm = $this->confirmPayment($paymentIntentId);
            // dd($confirm);
            if ($confirm->status === 'succeeded') {
                $corrency = strtoupper($confirm->currency);
                $amount = $confirm->amount / $this->resolveFactor($corrency);
                return ['response' => 'payment', 'payment' => "Gracias hemos resivido tu pago de: {$amount} {$corrency}"];
            }
            if ($confirm->status === 'requires_action') {
                $clientSecret = $confirm->client_secret;
                return ['response' => 'auth', 'payment' => "Necesitamos su autorizacion para poder finalizar su compra", 'authStripe' => $clientSecret];
            }
            return ['response' => 'failed', 'payment' => 'Los datos de su tarjeta no son correctos'];
        }
        return ['response' => 'failed', 'payment' => 'No se pudo generar el pago'];
    }

    public function retrievePaymentIntent($paymentIntentId)
    {
        return $this->makeRequest(
            'GET',
            "/v1/payment_intents/{$paymentIntentId}"
        );
    }

    public function createIntent($value, $currency, $paymentMehod)
    {
        return $this->makeRequest(
            'POST',
            '/v1/payment_intents',
            [],
            [
                'amount' => round($value * $this->resolveFactor($currency)),
                'currency' => strtolower($currency),
                'payment_method_types' => ['card'],
                // 'confirm' => true,
                'payment_method' => $paymentMehod,
                'confirmation_method' => 'manual',
                'use_stripe_sdk' => true
            ]
        );
    }

    public function confirmPayment($paymentIntentId)
    {
        return $this->makeRequest(
            'POST',
            "/v1/payment_intents/{$paymentIntentId}/confirm",
            [],
            // ['return_url'=>'https://world-adventures.es/reservation']
            // ['return_url'=>route('aprov.payment')]
            // ['return_url'=>url('/reservation/process/payment/auth')]
        );
    }

    public function resolveFactor($currency)
    {
        $zeroDecimalCurrencies = ['JPY'];
        if (in_array(strtoupper($currency), $zeroDecimalCurrencies)) {
            return 1;
        }
        return 100;
    }
}
