<?php

namespace Modules\Payment\Services;

use PayOS\Models\V2\PaymentRequests\CreatePaymentLinkRequest;
use PayOS\PayOS;

class PayOSService
{
    protected PayOS $payos;

    public function __construct()
    {
        $this->payos = new PayOS(
            clientId: config('payment.payos.client_id'),
            apiKey: config('payment.payos.api_key'),
            checksumKey: config('payment.payos.checksum_key'),
        );
    }

    public function createPaymentLink(
        int $orderCode,
        int $amount,
        string $description,
        string $returnUrl,
        string $cancelUrl,
        ?string $buyerName = null,
        ?string $buyerEmail = null,
        ?string $buyerPhone = null,
        ?array $items = null,
        ?int $expiredAt = null,
    ): array {
        $request = new CreatePaymentLinkRequest(
            orderCode: $orderCode,
            amount: $amount,
            description: $description,
            cancelUrl: $cancelUrl,
            returnUrl: $returnUrl,
            buyerName: $buyerName,
            buyerEmail: $buyerEmail,
            buyerPhone: $buyerPhone,
            items: $items,
            expiredAt: $expiredAt,
        );

        $response = $this->payos->paymentRequests->create($request, ['asArray' => true]);

        return $response;
    }

    public function getPaymentInfo(int $orderCode): array
    {
        return $this->payos->paymentRequests->get($orderCode, ['asArray' => true]);
    }

    public function cancelPaymentLink(int $orderCode, ?string $reason = null): array
    {
        return $this->payos->paymentRequests->cancel($orderCode, $reason, ['asArray' => true]);
    }

    public function verifyWebhookData(array $webhookBody)
    {
        return $this->payos->webhooks->verify($webhookBody);
    }
}
