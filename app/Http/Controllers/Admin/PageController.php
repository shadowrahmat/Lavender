<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SitePage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function edit(string $slug)
    {
        $page = SitePage::findBySlug($slug);

        return Inertia::render('Admin/Content/PageEditor', [
            'slug' => $slug,
            'page' => $page ? [
                'title'        => $page->title,
                'last_updated' => $page->last_updated,
                'sections'     => $page->sections ?? [],
            ] : null,
        ]);
    }

    public function update(Request $request, string $slug)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:200',
            'last_updated' => 'nullable|string|max:100',
            'sections'     => 'array',
            'sections.*.heading' => 'required|string|max:300',
            'sections.*.body'    => 'required|string',
        ]);

        SitePage::updateOrCreate(
            ['slug' => $slug],
            [
                'title'        => $data['title'],
                'last_updated' => $data['last_updated'] ?? '',
                'sections'     => $data['sections'] ?? [],
            ]
        );

        return back()->with('success', 'Page saved successfully.');
    }
}
