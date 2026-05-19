<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function index()
    {
        $settings = WebsiteSetting::all()->pluck('value', 'key');
        return Inertia::render('Admin/Settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name'           => 'required|string|max:100',
            'site_tagline'        => 'nullable|string|max:200',
            'email'               => 'nullable|email|max:100',
            'phone_1'             => 'nullable|string|max:20',
            'phone_2'             => 'nullable|string|max:20',
            'address'             => 'nullable|string|max:300',
            'delivery_charge'     => 'nullable|numeric|min:0',
            'free_delivery_above' => 'nullable|numeric|min:0',
            'facebook'            => 'nullable|url|max:200',
            'instagram'           => 'nullable|url|max:200',
            'maintenance_mode'    => 'boolean',
        ]);

        foreach ($data as $key => $value) {
            WebsiteSetting::set($key, $value ?? '');
        }

        return back()->with('success', 'Settings saved successfully.');
    }
}
