<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Post;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        // $posts = Post::with('user')->latest()->paginate(10);
        $posts = Post::with('user')->latest()->paginate(10);
        $tickets = Ticket::latest()->take(5)->get(); 
        // $posts = Post::latest()->paginate(10); // Paginer par 10 éléments par page
        return view('posts.index', compact('posts','tickets'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'attachments.*' => 'file|max:10240', // 10MB max

        ]);

        $post = auth()->user()->posts()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'is_published' => $request->has('is_published'),
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments');
                $post->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('posts.index')->with('success', 'Post créé avec succès.');
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'attachments.*' => 'file|max:10240', // 10MB max
        ]);

        $post->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'is_published' => $request->has('is_published'),
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments');
                $post->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('posts.index')->with('success', 'Post mis à jour avec succès.');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        // Supprimer les fichiers attachés
        foreach ($post->attachments as $attachment) {
            Storage::delete($attachment->path);
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post supprimé avec succès.');
    }

    public function removeAttachment(Attachment $attachment)
    {
        $this->authorize('update', $attachment->post);

        Storage::delete($attachment->path);
        $attachment->delete();

        return back()->with('success', 'Pièce jointe supprimée avec succès.');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

}