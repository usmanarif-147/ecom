<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px 12px; text-align: left; }
        th { background: #f5f5f5; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <p>Thank you for your order, {{ $order->customer_name }}!</p>

    <p><strong>Order number:</strong> {{ $order->order_number }}</p>
    <p><strong>Status:</strong> {{ $order->status->name }}</p>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Line total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
            <tr>
                <td>{{ $item->product['title'] }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ number_format($item->total_amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="total">Total</td>
                <td class="total">${{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <p>
        <strong>Payment:</strong>
        @if ($order->payment_method === 'cod')
            Pay on delivery
        @else
            {{ $order->payment_method }}
        @endif
    </p>
</body>
</html>
