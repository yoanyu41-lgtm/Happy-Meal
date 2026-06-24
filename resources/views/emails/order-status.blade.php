<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Happy Meal - Order Status Update</title>
</head>
<body style="margin: 0; padding: 0; background-color: #121214; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #e7e5e4;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #121214; padding: 20px 0;">
        <tr>
            <td align="center">
                <!-- Outer Container -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #1c1917; border: 1px solid #2e2a27; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.4);">
                    
                    <!-- Header Banner -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); padding: 30px 20px;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">Happy Meal</h1>
                            <p style="margin: 5px 0 0 0; color: rgba(255,255,255,0.85); font-size: 14px; font-weight: 500;">
                                Premium Online Food Delivery
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 30px 24px;">
                            
                            <!-- Greeting & Status Header -->
                            <h2 style="margin: 0 0 10px 0; color: #ffffff; font-size: 20px; font-weight: 700;">
                                {{ __('Order Status Update') }}
                            </h2>
                            <p style="margin: 0 0 24px 0; color: #a8a29e; font-size: 15px; line-height: 1.5;">
                                Hello <strong>{{ $order->customer_name }}</strong>, your order status has been updated. Here are the details of your order.
                            </p>

                            <!-- Current Status Highlight -->
                            @php
                                $statusTexts = [
                                    'pending' => ['km' => 'កំពុងរង់ចាំការបញ្ជាក់', 'en' => 'Pending Confirmation'],
                                    'confirmed' => ['km' => 'បានបញ្ជាក់ការបញ្ជាទិញ', 'en' => 'Confirmed & In Queue'],
                                    'preparing' => ['km' => 'កំពុងរៀបចំ និងចម្អិនម្ហូប', 'en' => 'Preparing Food'],
                                    'out_for_delivery' => ['km' => 'កំពុងដឹកជញ្ជូនទៅកាន់អ្នក', 'en' => 'Out for Delivery'],
                                    'delivered' => ['km' => 'បានដឹកជញ្ជូនជោគជ័យ', 'en' => 'Delivered'],
                                ];
                                $currentStatus = $statusTexts[$order->status] ?? ['km' => $order->status, 'en' => $order->status];
                            @endphp
                            <div style="background-color: #292524; border-left: 4px solid #f97316; border-radius: 6px; padding: 16px; margin-bottom: 24px;">
                                <div style="font-size: 13px; color: #a8a29e; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">
                                    Current Status / ស្ថានភាពបច្ចុប្បន្ន
                                </div>
                                <div style="font-size: 18px; font-weight: 800; color: #f97316; margin-bottom: 4px;">
                                    {{ $currentStatus['en'] }}
                                </div>
                                <div style="font-size: 16px; font-weight: 700; color: #e7e5e4;">
                                    {{ $currentStatus['km'] }}
                                </div>
                                
                                @if($order->estimated_delivery_minutes)
                                    <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #44403c; font-size: 13px; color: #d6d3d1;">
                                        Estimated Delivery Time: <strong>{{ $order->estimated_delivery_minutes }} minutes</strong>
                                    </div>
                                @endif
                                
                                @if($order->payment_verified)
                                    <div style="margin-top: 6px; font-size: 13px; color: #10b981; font-weight: 700; display: flex; align-items: center; gap: 4px;">
                                        ✓ Payment Verified / ការបង់ប្រាក់ត្រូវបានផ្ទៀងផ្ទាត់រួចរាល់
                                    </div>
                                @endif
                            </div>

                            <!-- Order Details Card -->
                            <h3 style="margin: 0 0 10px 0; color: #ffffff; font-size: 16px; font-weight: 700; border-bottom: 1px solid #2e2a27; padding-bottom: 8px;">
                                {{ __('Order Summary') }} (ID: #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }})
                            </h3>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 14px; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 6px 0; color: #a8a29e;" width="35%">{{ __('Customer') }}</td>
                                    <td style="padding: 6px 0; color: #ffffff; font-weight: 600;">{{ $order->customer_name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; color: #a8a29e;">{{ __('Phone') }}</td>
                                    <td style="padding: 6px 0; color: #ffffff;">{{ $order->customer_phone }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; color: #a8a29e; vertical-align: top;">{{ __('Address') }}</td>
                                    <td style="padding: 6px 0; color: #ffffff; line-height: 1.4;">{{ $order->customer_address }}</td>
                                </tr>
                            </table>

                            <!-- Items Table -->
                            <h3 style="margin: 0 0 10px 0; color: #ffffff; font-size: 16px; font-weight: 700; border-bottom: 1px solid #2e2a27; padding-bottom: 8px;">
                                {{ __('Items Ordered') }}
                            </h3>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 14px; margin-bottom: 24px;">
                                @foreach($order->items as $item)
                                    <tr style="border-bottom: 1px solid #292524;">
                                        <td style="padding: 10px 0; vertical-align: top;">
                                            <span style="color: #ffffff; font-weight: 600;">
                                                {{ $item->product ? $item->product->name : __('Deleted Product') }}
                                            </span>
                                            <span style="color: #a8a29e; margin-left: 5px;">(x{{ $item->quantity }})</span>
                                            
                                            @if(!empty($item->options))
                                                <div style="font-size: 12px; color: #f97316; margin-top: 3px; line-height: 1.3;">
                                                    @if(isset($item->options['spice']))
                                                        <div>{{ __('Spice Level') }}: {{ __($item->options['spice']) }}</div>
                                                    @endif
                                                    @if(isset($item->options['sweetness']))
                                                        <div>{{ __('Sweetness Level') }}: {{ $item->options['sweetness'] }}</div>
                                                    @endif
                                                    @if(isset($item->options['ice']))
                                                        <div>{{ __('Ice Level') }}: {{ $item->options['ice'] }}</div>
                                                    @endif
                                                    @if(isset($item->options['addons']) && count($item->options['addons']) > 0)
                                                        <div>{{ __('Add-ons') }}: 
                                                            @foreach($item->options['addons'] as $addon)
                                                                {{ $item->product ? $item->product->getAddonLabel($addon) : __($addon) }}{{ !$loop->last ? ', ' : '' }}
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td align="right" style="padding: 10px 0; color: #ffffff; font-weight: 600; vertical-align: top;">
                                            ${{ number_format($item->price * $item->quantity, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                                
                                <!-- Total Row -->
                                <tr>
                                    <td style="padding: 15px 0 0 0; color: #ffffff; font-size: 16px; font-weight: 700;">
                                        {{ __('Total') }}
                                    </td>
                                    <td align="right" style="padding: 15px 0 0 0; color: #f97316; font-size: 18px; font-weight: 800;">
                                        ${{ number_format($order->total_amount, 2) }}
                                    </td>
                                </tr>
                            </table>

                            <!-- Interactive Button -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="padding: 15px 0 10px 0;">
                                        <table border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td align="center" style="border-radius: 99px; background-color: #f97316;">
                                                    <a href="{{ route('checkout.success', $order->id) }}" target="_blank" style="display: inline-block; padding: 14px 30px; font-size: 14px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 99px; letter-spacing: 0.5px;">
                                                        Track Order / តាមដានការបញ្ជាទិញ
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer Content -->
                    <tr>
                        <td align="center" style="padding: 24px; background-color: #1c1917; border-top: 1px solid #2e2a27; font-size: 12px; color: #78716c;">
                            <p style="margin: 0 0 8px 0;">
                                Happy Meal - Delicious Online Food Ordering
                            </p>
                            <p style="margin: 0;">
                                &copy; {{ date('Y') }} Happy Meal. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
