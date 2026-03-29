<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('position')->orderByDesc('created_at')->get();

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'link_url' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'integer', 'min:0'],
            'image' => ['required', 'image', 'max:4096'],
        ]);

        $storedPath = $request->file('image')->store('banners', 'public');
        $imagePath = 'storage/' . $storedPath;

        Banner::create([
            'title' => $validated['title'],
            'link_url' => $validated['link_url'] ?? null,
            'position' => $validated['position'] ?? 0,
            'image_path' => $imagePath,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Đã tạo banner.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'link_url' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $imagePath = $banner->image_path;

        if ($request->hasFile('image')) {
            $storedPath = $request->file('image')->store('banners', 'public');
            $imagePath = 'storage/' . $storedPath;
        }

        $banner->update([
            'title' => $validated['title'],
            'link_url' => $validated['link_url'] ?? null,
            'position' => $validated['position'] ?? 0,
            'image_path' => $imagePath,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Đã cập nhật banner.');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Đã xóa banner.');
    }
}

