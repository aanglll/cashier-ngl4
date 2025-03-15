<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = Setting::first();
        return view('backend.setting.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico,webp,avif|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico,webp,avif|max:2048',
            'site_title' => 'nullable|string|max:255',
            'receipt_header' => 'nullable|string',
            'receipt_footer' => 'nullable|string',
        ]);

        $setting = Setting::first();

        if ($request->hasFile('site_logo')) {
            if ($setting->site_logo) {
                Storage::disk('public')->delete($setting->site_logo);
            }
            $siteLogoPath = $request->file('site_logo')->store('settings', 'public');
            $setting->site_logo = $siteLogoPath;
        }

        if ($request->hasFile('favicon')) {
            if ($setting->favicon) {
                Storage::disk('public')->delete($setting->favicon);
            }
            $faviconPath = $request->file('favicon')->store('settings', 'public');
            $setting->favicon = $faviconPath;
        }

        $setting->update($request->except(['site_logo', 'favicon']));

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
