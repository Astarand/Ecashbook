<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RazorpayService
{
    private $keyId;
    private $keySecret;
    private $baseUrl = 'https://api.razorpay.com/v1/';

    public function __construct()
    {
        $this->keyId = config('services.razorpay.key');
        $this->keySecret = config('services.razorpay.secret');
    }

    public function createOrder($amount, $currency = 'INR', $receipt = null)
    {
        try {
            $httpClient = Http::withBasicAuth($this->keyId, $this->keySecret);

            // Handle SSL verification based on configuration
            if (!config('services.razorpay.verify_ssl', true)) {
                $httpClient = $httpClient->withOptions([
                    'verify' => false, // Disable SSL verification
                ]);
            }

            $response = $httpClient->post($this->baseUrl . 'orders', [
                'amount' => $amount,
                'currency' => $currency,
                'receipt' => $receipt,
                'payment_capture' => 1
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to create order',
                'error' => $response->json()
            ];
        } catch (\Exception $e) {
            Log::error('Razorpay order creation failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Order creation failed: ' . $e->getMessage()
            ];
        }
    }

    public function verifySignature($orderId, $paymentId, $signature)
    {
        $payload = $orderId . '|' . $paymentId;
        $expectedSignature = hash_hmac('sha256', $payload, $this->keySecret);

        Log::info('Signature verification details', [
            'payload' => $payload,
            'expected_signature' => $expectedSignature,
            'received_signature' => $signature,
            'key_id' => $this->keyId,
            'secret_length' => strlen($this->keySecret)
        ]);

        $isValid = hash_equals($expectedSignature, $signature);

        Log::info('Signature verification result', [
            'is_valid' => $isValid,
            'order_id' => $orderId,
            'payment_id' => $paymentId
        ]);

        return $isValid;
    }

    public function getPaymentDetails($paymentId)
    {
        try {
            $httpClient = Http::withBasicAuth($this->keyId, $this->keySecret);

            // Handle SSL verification based on configuration
            if (!config('services.razorpay.verify_ssl', true)) {
                $httpClient = $httpClient->withOptions([
                    'verify' => false, // Disable SSL verification
                ]);
            }

            $response = $httpClient->get($this->baseUrl . 'payments/' . $paymentId);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to fetch payment details'
            ];
        } catch (\Exception $e) {
            Log::error('Razorpay payment fetch failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Payment fetch failed: ' . $e->getMessage()
            ];
        }
    }
}
