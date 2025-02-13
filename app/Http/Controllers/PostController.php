<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Post;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Contact;
use App\Models\Comment;
use App\Models\Document;
use App\Models\ConventionCollective;
use App\Models\Event;
use App\Models\Material;
use App\Models\TraitementPaie;
use App\Models\PeriodePaie;
use App\Models\User;
use App\Models\Client;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Support\Facades\Schema;

class PostController extends Controller
{
    public function index()
    {
        // $posts = Post::with('user')->latest()->paginate(10);
        $posts = Post::with('user')->latest()->paginate(10);
        $tickets = Ticket::latest()->take(5)->get();
        // $posts = Post::latest()->paginate(10); // Paginer par 10 éléments par page
        return view('posts.index', compact('posts', 'tickets'));
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

    public function search(Request $request)
    {
        $query = $request->input('query');
        $key = trim($request->get('q'));

        $posts = Post::query()
            ->where('title', 'like', "%{$key}%")
            ->orWhere('content', 'like', "%{$key}%")
            ->orderBy('created_at', 'desc')
            ->get();

        $users = User::where('name', 'LIKE', "%{$key}%")
            ->orWhere('email', 'LIKE', "%{$key}%")
            ->get();

        $clients = Client::where('name', 'LIKE', "%{$key}%")
            ->get();

        $attachments = Attachment::where('filename', 'LIKE', "%{$key}%")
            ->get();

        $audits = Audit::where('event', 'LIKE', "%{$key}%")
            ->get();

        $contacts = collect(); // Remplacer la requête par une collection vide

        $comments = Comment::where('content', 'LIKE', "%{$key}%")
            ->get();

        $documents = Document::query();
        if (Schema::hasColumn('documents', 'title')) {
            $documents->where('title', 'LIKE', "%{$key}%");
        }
        if (Schema::hasColumn('documents', 'content')) {
            $documents->orWhere('content', 'LIKE', "%{$key}%");
        }
        $documents = $documents->get();

        $conventionCollectives = ConventionCollective::where('name', 'LIKE', "%{$key}%")
            ->get();

        $events = Event::where('title', 'LIKE', "%{$key}%")
            ->orWhere('start', 'LIKE', "%{$key}%")
            ->get();

        $materials = Material::where('title', 'LIKE', "%{$key}%")
            ->get();

        $periodesPaie = PeriodePaie::where('reference', 'LIKE', "%{$key}%")
            ->get();

        $traitementsPaie = TraitementPaie::where('reference', 'LIKE', "%{$key}%")
            ->get();

        $recent_posts = Post::query()
            ->where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $results = collect([
            'users' => $users,
            'clients' => $clients,
            'attachments' => $attachments,
            'audits' => $audits,
            'contacts' => $contacts,
            'comments' => $comments,
            'documents' => $documents,
            'conventionCollectives' => $conventionCollectives,
            'events' => $events,
            'materials' => $materials,
            'periodesPaie' => $periodesPaie,
            'traitementsPaie' => $traitementsPaie,
            'key' => $key,
            'posts' => $posts,
            'recent_posts' => $recent_posts,
        ]);

        return view('components.search-results', compact('results', 'query'));
    }
}
