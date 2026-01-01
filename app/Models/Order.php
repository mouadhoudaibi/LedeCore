<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'order_number',
        'status',
        'total_amount',
        'delivery_fee',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'status' => 'string',
        ];
    }

    /**
     * Get the order items for the order.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Generate WhatsApp message URL with order details.
     */
    public function getWhatsAppUrl(): ?string
    {
        if (!$this->customer_phone) {
            return null;
        }

        // Clean phone number (remove spaces, dashes, etc.)
        $phone = preg_replace('/[^0-9+]/', '', $this->customer_phone);
        
        // If phone doesn't start with +, add country code (Morocco: +212)
        if (!str_starts_with($phone, '+')) {
            // Remove leading 0 if present
            $phone = ltrim($phone, '0');
            $phone = '+212' . $phone;
        }

        // Build message
        $locale = app()->getLocale();
        $message = $this->formatWhatsAppMessage($locale);

        // Encode message for URL
        $encodedMessage = urlencode($message);

        return "https://wa.me/{$phone}?text={$encodedMessage}";
    }

    /**
     * Generate simple WhatsApp URL without message (for quick contact).
     */
    public function getWhatsAppContactUrl(): ?string
    {
        if (!$this->customer_phone) {
            return null;
        }

        // Clean phone number (remove spaces, dashes, etc.)
        $phone = preg_replace('/[^0-9+]/', '', $this->customer_phone);
        
        // If phone doesn't start with +, add country code (Morocco: +212)
        if (!str_starts_with($phone, '+')) {
            // Remove leading 0 if present
            $phone = ltrim($phone, '0');
            $phone = '+212' . $phone;
        }

        return "https://wa.me/{$phone}";
    }

    /**
     * Format WhatsApp message based on locale.
     */
    private function formatWhatsAppMessage(string $locale): string
    {
        // Ensure orderItems are loaded
        if (!$this->relationLoaded('orderItems')) {
            $this->load('orderItems.product');
        }

        $lines = [];
        
        if ($locale === 'fr') {
            $lines[] = "Bonjour {$this->customer_name},";
            $lines[] = "";
            $lines[] = "Commande: {$this->order_number}";
            $lines[] = "Statut: " . __('common.' . $this->status);
            $lines[] = "Total: " . number_format($this->total_amount, 2) . " MAD";
            $lines[] = "";
            $lines[] = "Articles:";
            foreach ($this->orderItems as $item) {
                $lines[] = "- {$item->product->name} (x{$item->quantity})";
            }
            $lines[] = "";
            $lines[] = "Merci pour votre commande!";
        } else {
            $lines[] = "Hello {$this->customer_name},";
            $lines[] = "";
            $lines[] = "Order: {$this->order_number}";
            $lines[] = "Status: " . __('common.' . $this->status);
            $lines[] = "Total: " . number_format($this->total_amount, 2) . " MAD";
            $lines[] = "";
            $lines[] = "Items:";
            foreach ($this->orderItems as $item) {
                $lines[] = "- {$item->product->name} (x{$item->quantity})";
            }
            $lines[] = "";
            $lines[] = "Thank you for your order!";
        }

        return implode("\n", $lines);
    }

    /**
     * Check if order is visible to clients (not hidden refused orders).
     * Refused orders are hidden from clients after 24 hours from updated_at.
     */
    public function isVisibleToClient(): bool
    {
        // If order is not refused, it's always visible
        if ($this->status !== 'refused') {
            return true;
        }

        // Refused orders are visible for 24 hours after being refused (based on updated_at)
        $refusedAt = $this->updated_at;
        $hoursSinceRefused = now()->diffInHours($refusedAt);

        // Visible if less than 24 hours have passed since refusal
        return $hoursSinceRefused < 24;
    }
}
