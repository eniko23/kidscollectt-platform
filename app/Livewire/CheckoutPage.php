<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CheckoutPage extends Component
{
    // Sepet verileri
    public array $items = [];
    public int $subtotal = 0;
    public int $shipping = 0;
    public int $tax = 0;
    public int $total = 0;

    // Kupon verileri
    public ?Coupon $appliedCoupon = null;
    public int $discount = 0;

    // Giriş durumu
    public string $checkoutType = 'guest';
    public bool $sameAsShipping = true;

    // Fatura Adresi
    public ?int $billingAddressId = null;
    public string $billingFirstName = '';
    public string $billingLastName = '';
    public string $billingPhone = '';
    public string $billingAddressLine1 = '';
    public string $billingAddressLine2 = '';
    public string $billingDistrict = '';
    public string $billingCity = '';
    public string $billingCountry = 'Turkey';

    // Teslimat Adresi
    public ?int $shippingAddressId = null;
    public string $shippingFirstName = '';
    public string $shippingLastName = '';
    public string $shippingPhone = '';
    public string $shippingAddressLine1 = '';
    public string $shippingAddressLine2 = '';
    public string $shippingDistrict = '';
    public string $shippingCity = '';
    public string $shippingCountry = 'Turkey';

    // Ödeme yöntemi
    public string $paymentMethod = 'credit_card';
    public $savedAddresses = [];

    public function mount()
    {
        $cartService = app(CartService::class);
        $this->items = $cartService->getItems();
        $this->subtotal = $cartService->getSubtotal();
        $this->appliedCoupon = $cartService->getAppliedCoupon();

        if (empty($this->items)) {
            return redirect()->route('cart.index');
        }

        $this->calculateTotals();

        if (Auth::check()) {
            $this->checkoutType = 'logged_in';
            $this->savedAddresses = Auth::user()->addresses;

            $defaultBilling = Auth::user()->addresses()->where('is_default_billing', true)->first();
            $defaultShipping = Auth::user()->addresses()->where('is_default_shipping', true)->first();

            if ($defaultBilling) {
                $this->loadBillingAddress($defaultBilling->id);
            }

            if ($defaultShipping) {
                $this->loadShippingAddress($defaultShipping->id);
            } else {
                $this->loadShippingAddress($defaultBilling?->id);
            }
        }
    }

    public function calculateTotals()
    {
        $this->discount = 0;

        if ($this->appliedCoupon) {
            if ($this->appliedCoupon->min_amount && $this->subtotal < $this->appliedCoupon->min_amount) {
                app(CartService::class)->removeCoupon();
                $this->appliedCoupon = null;
            } else {
                if ($this->appliedCoupon->type == 'fixed') {
                    $this->discount = $this->appliedCoupon->value;
                } elseif ($this->appliedCoupon->type == 'percent') {
                    $this->discount = (int) ($this->subtotal * ($this->appliedCoupon->percent_off / 100));
                }
            }
        }

        $this->shipping = $this->subtotal >= 100000 ? 0 : 9900;

        $this->tax = 0;
        foreach ($this->items as $item) {
            $product = $item['variant']->product;
            $vatRate = $product->vat_rate ?? 0;
            $itemSubtotal = $item['subtotal'];
            // Kullanıcı isteği üzerine: KDV tutarı, KDV dahil fiyatın %vat_rate kadarı olacak.
            // Örn: Fiyat 110 TL, KDV %10 -> KDV Tutarı = 11 TL (Normalde 10 TL olması gerekirken)
            $this->tax += (int) ($itemSubtotal * ($vatRate / 100));
        }

        $this->total = $this->subtotal - $this->discount + $this->shipping;

        if ($this->total < 0) {
            $this->total = 0;
        }
    }

    public function updatedSameAsShipping($value)
    {
        if ($value) {
            $this->billingFirstName = $this->shippingFirstName;
            $this->billingLastName = $this->shippingLastName;
            $this->billingPhone = $this->shippingPhone;
            $this->billingAddressLine1 = $this->shippingAddressLine1;
            $this->billingAddressLine2 = $this->shippingAddressLine2;
            $this->billingDistrict = $this->shippingDistrict;
            $this->billingCity = $this->shippingCity;
            $this->billingCountry = $this->shippingCountry;
        }
    }

    public function loadBillingAddress($addressId)
    {
        if (!$addressId) return;

        $address = Address::find($addressId);
        if ($address && Auth::check() && $address->user_id === Auth::id()) {
            $this->billingAddressId = $address->id;
            $this->billingFirstName = $address->first_name;
            $this->billingLastName = $address->last_name;
            $this->billingPhone = $address->phone ?? '';
            $this->billingAddressLine1 = $address->address_line_1;
            $this->billingAddressLine2 = $address->address_line_2 ?? '';
            $this->billingDistrict = $address->district;
            $this->billingCity = $address->city;
            $this->billingCountry = $address->country;
        }
    }

    public function loadShippingAddress($addressId)
    {
        if (!$addressId) return;

        $address = Address::find($addressId);
        if ($address && Auth::check() && $address->user_id === Auth::id()) {
            $this->shippingAddressId = $address->id;
            $this->shippingFirstName = $address->first_name;
            $this->shippingLastName = $address->last_name;
            $this->shippingPhone = $address->phone ?? '';
            $this->shippingAddressLine1 = $address->address_line_1;
            $this->shippingAddressLine2 = $address->address_line_2 ?? '';
            $this->shippingDistrict = $address->district;
            $this->shippingCity = $address->city;
            $this->shippingCountry = $address->country;
        }
    }

    public function submitOrder(CartService $cartService)
    {
        if ($this->sameAsShipping) {
            $this->billingFirstName = $this->shippingFirstName;
            $this->billingLastName = $this->shippingLastName;
            $this->billingPhone = $this->shippingPhone;
            $this->billingAddressLine1 = $this->shippingAddressLine1;
            $this->billingAddressLine2 = $this->shippingAddressLine2;
            $this->billingDistrict = $this->shippingDistrict;
            $this->billingCity = $this->shippingCity;
            $this->billingCountry = $this->shippingCountry;
        }

        $this->validate([
            'shippingFirstName' => 'required|string|max:255',
            'shippingLastName' => 'required|string|max:255',
            'shippingPhone' => 'required|string|max:255',
            'shippingAddressLine1' => 'required|string|max:255',
            'shippingDistrict' => 'required|string|max:255',
            'shippingCity' => 'required|string|max:255',
            'billingFirstName' => 'required|string|max:255',
            'billingLastName' => 'required|string|max:255',
            'billingPhone' => 'required|string|max:255',
            'billingAddressLine1' => 'required|string|max:255',
            'billingDistrict' => 'required|string|max:255',
            'billingCity' => 'required|string|max:255',
        ]);

        $order = $this->createOrder($cartService);

        if (!$order) {
            return;
        }

        if ($this->paymentMethod === 'credit_card') {
            session(['order_id_for_payment' => $order->id]);
            return redirect()->route('payment.process');
        } else {
            session()->flash('order_created', $order->id);
            return redirect()->route('payment.thank-you');
        }
    }

    public function createOrder(CartService $cartService)
    {
        try {
            return DB::transaction(function () use ($cartService) {
                $shippingAddressString = sprintf(
                    "%s %s\n%s\n%s\n%s, %s\n%s\nTelefon: %s",
                    $this->shippingFirstName,
                    $this->shippingLastName,
                    $this->shippingAddressLine1,
                    $this->shippingAddressLine2 ?? '',
                    $this->shippingDistrict,
                    $this->shippingCity,
                    $this->shippingCountry,
                    $this->shippingPhone
                );

                $orderData = [
                    'total_price' => $this->total,
                    'status' => $this->paymentMethod === 'credit_card' ? 'pending_payment' : 'processing',
                    'shipping_address' => $shippingAddressString,
                    'payment_method' => $this->paymentMethod,
                ];

                if (Auth::check()) {
                    $orderData['user_id'] = Auth::id();
                }

                $order = Order::create($orderData);

                foreach ($this->items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_variant_id' => $item['variant_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);

                    $variant = ProductVariant::find($item['variant_id']);
                    if ($variant) {
                        $variant->decrement('stock', $item['quantity']);
                    } else {
                        throw new \Exception("Stokta olmayan bir ürün (ID: {$item['variant_id']}) sipariş edilmeye çalışıldı.");
                    }
                }

                $cartService->clear();
                return $order;
            });
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Sipariş oluşturma hatası: ' . $e->getMessage());
            session()->flash('error_checkout', 'Siparişiniz oluşturulurken beklenmedik bir hata oluştu: ' . $e->getMessage());
            return null;
        }
    }

    public function render()
    {
        return view('livewire.checkout-page');
    }
}
