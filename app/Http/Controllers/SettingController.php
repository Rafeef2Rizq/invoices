<?php
// app/Http/Controllers/SettingController.php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Http\Requests\SettingRequest;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = auth()->user()->setting
            ?? new Setting(['invoice_color' => '#212529', 'invoice_prefix' => 'INV']);

        return view('settings.edit', compact('setting'));
    }

    public function update(SettingRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();

        // Handle logo upload
        if ($request->hasFile('company_logo')) {
            // Delete old logo if exists
            if ($user->setting?->company_logo) {
                Storage::disk('public')->delete($user->setting->company_logo);
            }
            $data['company_logo'] = $request->file('company_logo')
                ->store('logos', 'public');
        }

        // Remove logo from data if not uploaded (keep existing)
        if (!$request->hasFile('company_logo')) {
            unset($data['company_logo']);
        }

        // updateOrCreate — no need to check if exists
        Setting::updateOrCreate(
            ['user_id' => $user->id],
            array_merge($data, ['user_id' => $user->id])
        );

        return redirect()->route('settings.edit')
            ->with('success', 'Settings saved successfully.');
    }

    public function deleteLogo()
    {
        $setting = auth()->user()->setting;

        if ($setting?->company_logo) {
            Storage::disk('public')->delete($setting->company_logo);
            $setting->update(['company_logo' => null]);
        }

        return back()->with('success', 'Logo removed.');
    }
}