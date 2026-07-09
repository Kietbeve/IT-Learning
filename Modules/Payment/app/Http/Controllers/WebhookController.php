<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Payment\Models\Order;
use Modules\Payment\Services\OrderService;
use Modules\Payment\Services\PayOSService;

class WebhookController
{
    /**
     * Handle PayOS webhook notification
     */
    public function handlePayOS(Request $request)
    {
        try {
            Log::info('PayOS Webhook received', ['data' => $request->all()]);

            $payOS = app(PayOSService::class);
            $webhookData = $request->all();

            try {
                $verifiedData = $payOS->verifyWebhookData($webhookData);
            } catch (\Exception $e) {
                Log::warning('Invalid webhook signature', ['error' => $e->getMessage()]);
                return response()->json(['error' => 'Invalid signature'], 401);
            }

            $orderCode = null;
            if (is_array($verifiedData)) {
                $orderCode = $verifiedData['orderCode'] ?? $verifiedData['data']['orderCode'] ?? null;
            } elseif (is_object($verifiedData)) {
                $orderCode = $verifiedData->orderCode ?? null;
            }

            if (! $orderCode) {
                Log::error('PayOS Webhook: Missing order code', ['data' => $webhookData]);

                return response()->json(['error' => 'Missing order code'], 400);
            }

            $order = Order::where('order_code', (string) $orderCode)->first();

            if (! $order) {
                Log::error('PayOS Webhook: Order not found', ['order_code' => $orderCode]);

                return response()->json(['error' => 'Order not found'], 200);
            }

            $paymentInfo = $payOS->getPaymentInfo((int) $orderCode);
            $status = $paymentInfo['status'] ?? null;

            Log::info('PayOS Webhook: Payment info retrieved', [
                'order_code' => $orderCode,
                'status' => $status,
            ]);

            if ($status === 'PAID') {
                $processed = app(OrderService::class)->processSuccessfulPayment($orderCode, $paymentInfo);

                if ($processed) {
                    Log::info('PayOS Webhook: Payment processed successfully', ['order_code' => $orderCode]);
                } else {
                    Log::info('PayOS Webhook: Order already processed, skipping.', ['order_code' => $orderCode]);
                }

                return response()->json(['success' => true, 'message' => 'Payment processed'], 200);
            }

            Log::warning('PayOS Webhook: Payment not successful', [
                'order_code' => $orderCode,
                'status' => $status,
            ]);

            return response()->json(['success' => false, 'status' => $status], 200);

        } catch (\Exception $e) {
            Log::error('PayOS Webhook: Exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
