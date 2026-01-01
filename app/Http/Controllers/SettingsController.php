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

        return view('admin.settings.index', compact('casablancaFee', 'outsideFee'));
    }

    /**
     * Update delivery fees.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'delivery_fee_casablanca' => ['required', 'numeric', 'min:0', 'max:9999.99'],
            'delivery_fee_outside' => ['required', 'numeric', 'min:0', 'max:9999.99'],
        ]);

        Setting::set('delivery_fee_casablanca', $validated['delivery_fee_casablanca']);
        Setting::set('delivery_fee_outside', $validated['delivery_fee_outside']);

        return redirect()->route('admin.settings.index')
            ->with('success', __('admin.settings_updated'));
    }
}
