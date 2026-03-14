<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AuthorController extends Controller
{
    public function index(): View
    {
        $authors = Author::withCount('books')->latest()->paginate(15);
        return view('admin.authors.index', compact('authors'));
    }

    public function create(): View
    {
        return view('admin.authors.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'bio'        => ['nullable', 'string'],
            'photo'      => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('authors', 'public');
        }

        unset($data['photo']);
        Author::create($data);

        return redirect()->route('admin.authors.index')
            ->with('success', 'Autor creado correctamente.');
    }

    public function edit(Author $author): View
    {
        return view('admin.authors.edit', compact('author'));
    }

    public function update(Request $request, Author $author): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'bio'        => ['nullable', 'string'],
            'photo'      => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            if ($author->photo_path) {
                Storage::disk('public')->delete($author->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('authors', 'public');
        }

        unset($data['photo']);
        $author->update($data);

        return redirect()->route('admin.authors.index')
            ->with('success', 'Autor actualizado correctamente.');
    }

    public function destroy(Author $author): RedirectResponse
    {
        if ($author->photo_path) {
            Storage::disk('public')->delete($author->photo_path);
        }

        $author->delete();

        return redirect()->route('admin.authors.index')
            ->with('success', 'Autor eliminado.');
    }
}
