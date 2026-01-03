<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index(): View
    {
        $casablancaFee = Setting::get('delivery_fee_casablanca', '30.00');
        $outsideFee = Setting::get('delivery_fee_outside', '50.00');
        $whatsappUrl = Setting::get('social_whatsapp', '');
        $facebookUrl = Setting::get('social_facebook', '');
        $instagramUrl = Setting::get('social_instagram', '');
        $tiktokUrl = Setting::get('social_tiktok', '');

        return view('admin.settings.index', compact('casablancaFee', 'outsideFee', 'whatsappUrl', 'facebookUrl', 'instagramUrl', 'tiktokUrl'));
    }

    /**
     * Update delivery fees and social media links.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'delivery_fee_casablanca' => ['required', 'numeric', 'min:0', 'max:9999.99'],
            'delivery_fee_outside' => ['required', 'numeric', 'min:0', 'max:9999.99'],
            'social_whatsapp' => ['nullable', 'url', 'max:255'],
            'social_facebook' => ['nullable', 'url', 'max:255'],
            'social_instagram' => ['nullable', 'url', 'max:255'],
            'social_tiktok' => ['nullable', 'url', 'max:255'],
        ]);

        Setting::set('delivery_fee_casablanca', $validated['delivery_fee_casablanca']);
        Setting::set('delivery_fee_outside', $validated['delivery_fee_outside']);
        Setting::set('social_whatsapp', $validated['social_whatsapp'] ?? '');
        Setting::set('social_facebook', $validated['social_facebook'] ?? '');
        Setting::set('social_instagram', $validated['social_instagram'] ?? '');
        Setting::set('social_tiktok', $validated['social_tiktok'] ?? '');

        return redirect()->route('admin.settings.index')
            ->with('success', __('admin.settings_updated'));
    }
}
