<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('checkout.receipt') }} - {{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20mm;
            background: white;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #9333ea;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #9333ea;
            margin-bottom: 10px;
        }
        .receipt-title {
            font-size: 18px;
            color: #666;
            margin-top: 10px;
        }
        .order-info {
            width: 100%;
            margin-bottom: 25px;
        }
        .order-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-info-label {
            font-weight: bold;
            width: 40%;
            padding: 5px 0;
            color: #555;
        }
        .order-info-value {
            padding: 5px 0;
            color: #333;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #9333ea;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        .customer-info {
            margin-bottom: 25px;
        }
        .customer-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .customer-info-label {
            font-weight: bold;
            width: 30%;
            padding: 4px 0;
            color: #555;
        }
        .customer-info-value {
            padding: 4px 0;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead {
            background-color: #f3f4f6;
        }
        th {
            text-align: left;
            padding: 10px;
            font-weight: bold;
            color: #333;
            border-bottom: 2px solid #ddd;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .original-price {
            text-decoration: line-through;
            color: #999;
            font-size: 11px;
            margin-right: 5px;
        }
        .promo-price {
            color: #dc2626;
            font-weight: bold;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9fafb;
        }
        .grand-total {
            font-size: 16px;
            color: #9333ea;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header with Logo -->
        <div class="header">
            <div class="logo">LedeCore</div>
            <div class="receipt-title">{{ __('checkout.receipt') }}</div>
        </div>

        <!-- Order Information -->
        <div class="order-info">
            <table>
                <tr>
                    <td class="order-info-label">{{ __('checkout.order_number') }}:</td>
                    <td class="order-info-value">{{ $order->order_number }}</td>
                </tr>
                <tr>
                    <td class="order-info-label">{{ __('checkout.order_date') }}:</td>
                    <td class="order-info-value">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                <tr>
                    <td class="order-info-label">{{ __('checkout.order_status') }}:</td>
                    <td class="order-info-value">{{ __('common.' . $order->status) }}</td>
                </tr>
            </table>
        </div>

        <!-- Customer Information -->
        <div class="section-title">{{ __('common.customer_information') }}</div>
        <div class="customer-info">
            <table>
                <tr>
                    <td class="customer-info-label">{{ __('common.customer_name') }}:</td>
                    <td class="customer-info-value">{{ $order->customer_name }}</td>
                </tr>
                <tr>
                    <td class="customer-info-label">{{ __('common.customer_email') }}:</td>
                    <td class="customer-info-value">{{ $order->customer_email }}</td>
                </tr>
                <tr>
                    <td class="customer-info-label">{{ __('common.customer_phone') }}:</td>
                    <td class="customer-info-value">{{ $order->customer_phone }}</td>
                </tr>
                <tr>
                    <td class="customer-info-label">{{ __('checkout.shipping_address') }}:</td>
                    <td class="customer-info-value">{{ $order->shipping_address }}</td>
                </tr>
            </table>
        </div>

        <!-- Order Items -->
        <div class="section-title">{{ __('common.order_items') }}</div>
        <table>
            <thead>
                <tr>
                    <th>{{ __('common.product') }}</th>
                    <th class="text-center">{{ __('common.quantity') }}</th>
                    <th class="text-right">{{ __('common.price') }}</th>
                    <th class="text-right">{{ __('common.total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">
                            @php
                                $hasPromo = $item->unit_price < $item->product->price;
                            @endphp
                            @if($hasPromo)
                                <span class="original-price">{{ number_format($item->product->price, 2) }} MAD</span>
                                <span class="promo-price">{{ number_format($item->unit_price, 2) }} MAD</span>
                            @else
                                {{ number_format($item->unit_price, 2) }} MAD
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($item->total_price, 2) }} MAD</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right total-row">{{ __('checkout.subtotal') }}:</td>
                    <td class="text-right total-row">{{ number_format($order->total_amount, 2) }} MAD</td>
                </tr>
                @if($order->delivery_fee > 0)
                    <tr>
                        <td colspan="3" class="text-right total-row">{{ __('checkout.delivery_fee') }}:</td>
                        <td class="text-right total-row">{{ number_format($order->delivery_fee, 2) }} MAD</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="3" class="text-right grand-total">{{ __('checkout.total') }}:</td>
                    <td class="text-right grand-total">{{ number_format($order->total_amount + $order->delivery_fee, 2) }} MAD</td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>{{ __('checkout.receipt_footer') }}</p>
            <p>{{ __('checkout.payment_method_cod') }}</p>
        </div>
    </div>
</body>
</html>

