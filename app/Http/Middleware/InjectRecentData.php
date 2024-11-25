<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Post;

class InjectRecentData
{
    public function handle(Request $request, Closure $next)
    {
        $tickets = Ticket::latest()->take(5)->get();
        $posts = Post::latest()->take(5)->get();

        view()->share('tickets', $tickets);
        view()->share('posts', $posts);

        return $next($request);
    }
}