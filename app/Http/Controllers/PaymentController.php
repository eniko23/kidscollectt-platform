<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    // 1. ADIM: TOKEN ALMA VE FORMU GÖSTERME
    public function payment()
    {
        $orderId = session('order_id_for_payment');
        if (!$orderId) {
            return redirect()->route('home');
        }

        $order = Order::with('items.variant.product')->find($orderId);
        if (!$order) {
            return redirect()->route('home');
        }

        // --- PayTR Ayarları ---
        $merchant_id   = '527634';
        $merchant_key  = '1Kq2utn1gjxUcKEH';
        $merchant_salt = 'Zkz9BtGnmL1N4WzL';

        // Müşteri Bilgileri
        $email = $user->email ?? 'misafir@kidscollectt.com';
        
        $user_name = Auth::user()->name ?? 'Misafir Kullanıcı';
        // Basitçe ASCII'ye çevirip garantiye alalım
        $user_name = iconv('UTF-8', 'ASCII//TRANSLIT', $user_name) ?: 'Musteri';

        // Adres: Kullanıcı tablosunda değil, Sipariş tablosunda aranır
        $user_address = $order->shipping_address ?? 'Teslimat adresi girilmedi';
        
        // Telefon: Senin users tablondaki 'phone' sütunu
        $user_phone = $user->phone ?? '05555555555';

        // --- Tutar ---
        // Veritabanında kuruş tutuyorsan (örn: 59990) direkt al.
        // Eğer TL tutuyorsan (599.90) * 100 yapmalısın.
        $payment_amount = (int) $order->total_price; 

        $merchant_oid = $order->id . 'RND' . time();
        $merchant_ok_url   = route('payment.success');
        $merchant_fail_url = route('payment.fail');

if ($user_name === '' || strlen($user_name) < 2) {
    $user_name = 'Musteri';
}

// Türkçe / özel karakter temizle
$user_name = iconv('UTF-8', 'ASCII//TRANSLIT', $user_name);

        $user_address = $order->shipping_address ?? 'Adres bilgisi yok';
        $user_phone   = '05555555555';

        $merchant_ok_url   = route('payment.success');
        $merchant_fail_url = route('payment.fail');

        // --- SEPET (PAYTR FORMAT) ---
        $user_basket = [];
        foreach ($order->items as $item) {
            $user_basket[] = [
                $item->variant->product->name ?? 'Ürün',
                number_format($item->price / 100, 2, '.', ''), // Kuruş ayracı nokta (.) olmalı
                (int) $item->quantity
            ];
        }
        $user_basket = base64_encode(json_encode($user_basket));

        // IP (local / prod güvenli)
        $user_ip = request()->ip();



        $timeout_limit  = 30;
        $debug_on       = 0;
        $test_mode      = 0;
        $no_installment = 0;
        $max_installment = 0;
        $currency = 'TL';

        // HASH
        $hash_str = $merchant_id
            . $user_ip
            . $merchant_oid
            . $email
            . $payment_amount
            . $user_basket
            . $no_installment
            . $max_installment
            . $currency
            . $test_mode;

        $paytr_token = base64_encode(
            hash_hmac('sha256', $hash_str . $merchant_salt, $merchant_key, true)
        );

        // POST
        $post_vals = [
            'merchant_id'      => $merchant_id,
            'user_ip'          => $user_ip,
            'merchant_oid'     => $merchant_oid,
            'email'            => $email,
            'payment_amount'   => $payment_amount,
            'paytr_token'      => $paytr_token,
            'user_basket'      => $user_basket,
            'debug_on'         => $debug_on,
            'no_installment'   => $no_installment,
            'max_installment'  => $max_installment,
            'user_name'        => $user_name,
            'user_address'     => $user_address,
            'user_phone'       => $user_phone,
            'merchant_ok_url'  => $merchant_ok_url,
            'merchant_fail_url'=> $merchant_fail_url,
            'timeout_limit'    => $timeout_limit,
            'currency'         => $currency,
            'test_mode'        => $test_mode
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.paytr.com/odeme/api/get-token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_vals));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return 'PAYTR Bağlantı Hatası: ' . curl_error($ch);
        }
        curl_close($ch);

        $result = json_decode($result, true);

        if ($result['status'] === 'success') {
            return view('payment', ['token' => $result['token']]);
        }

        return 'PAYTR Token Hatası: ' . $result['reason'];
    }

    // 2. ADIM: CALLBACK
    public function callback(Request $request)
    {
        $post = $request->all();

        $merchant_key  = '1Kq2utn1gjxUcKEH';
        $merchant_salt = 'Zkz9BtGnmL1N4WzL';

        $hash = base64_encode(
            hash_hmac(
                'sha256',
                $post['merchant_oid'] . $merchant_salt . $post['status'] . $post['total_amount'],
                $merchant_key,
                true
            )
        );

        if ($hash !== $post['hash']) {
            return 'PAYTR notification failed: bad hash';
        }

        $orderIdParts = explode('RND', $post['merchant_oid']);
        $orderId = $orderIdParts[0];

        $order = Order::find($orderId);
        if (!$order) {
            return 'Order not found';
        }

        if ($post['status'] === 'success') {
            if (!in_array($order->status, ['processing', 'completed'])) {
                $order->status = 'processing';
                $order->save();
            }
        } else {
            $order->status = 'cancelled';
            $order->save();
        }

        return response('OK');
    }

    public function success()
    {
        return view('payment.result', [
            'status' => 'success',
            'message' => 'Ödeme başarıyla alındı!'
        ]);
    }

    public function fail()
    {
        return view('payment.result', [
            'status' => 'fail',
            'message' => 'Ödeme işlemi başarısız oldu.'
        ]);
    }
}
