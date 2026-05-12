<?php

namespace App\Http\Controllers;

use App\Models\HomeSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeSlideController extends Controller
{
    public function update(Request $request)
    {
        $rules = [];
        foreach (range(1, 5) as $i) {
            $rules['slide_file_'.$i] = 'nullable|image|max:5120';
        }
        $request->validate($rules);

        foreach (range(1, 5) as $i) {
            if (! $request->hasFile('slide_file_'.$i)) {
                continue;
            }

            $slide = HomeSlide::query()->where('slot', $i)->first();
            if ($slide && $slide->image_path && str_starts_with($slide->image_path, 'storage/')) {
                Storage::disk('public')->delete(substr($slide->image_path, strlen('storage/')));
            }

            $stored = $request->file('slide_file_'.$i)->store('home-slides', 'public');
            $path = 'storage/'.$stored;

            HomeSlide::query()->where('slot', $i)->update([
                'image_path' => $path,
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Изображения слайдера на главной обновлены.');
    }
}
