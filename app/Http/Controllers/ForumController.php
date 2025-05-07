<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ForumPost;
use App\Models\ForumComment;
use Illuminate\Support\Facades\Auth;
use App\Models\ForumPostVote;
use App\Models\ForumCommentVote;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ForumController extends Controller
{
    public function index(Request $request)
    {
        $query = ForumPost::with('user')->withCount('comments');

        switch ($request->get('sort')) {
            case 'rating':
                $query->orderByDesc('upvotes');
                break;
            case 'comments':
                $query->orderByDesc('comments_count');
                break;
            default:
                $query->orderByDesc('created_at');
        }

        $posts = $query->get();

        return view('forum.index', compact('posts'));
    }

    public function create()
    {
        return view('forum.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        ForumPost::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('forum.index');
    }

    public function pin(ForumPost $post)
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            $post->pinned = !$post->pinned;
            $post->save();
        } else {
            abort(403); 
        }

        return back();
    }

    public function show(ForumPost $post)
    {
        $sort = request('sort', 'newest');

        $post->load([
            'user',
            'votes',
            'comments' => function ($query) {
                $query->withCount('votes')
                    ->with('user', 'votes');
            }
        ]);

        $comments = $post->comments->sortByDesc(function ($comment) use ($sort) {
            return [
                $comment->pinned ? 1 : 0,
                $sort === 'rating' ? $comment->votes_count : $comment->created_at->timestamp
            ];
        });

        return view('forum.show', compact('post', 'comments'));
    }

    public function comment(Request $request, ForumPost $post)
    {
        if (in_array(auth()->user()->role, ['guest', 'blocked'])) {
            return redirect()->route('forum.show', $post)->with('error', 'Negalite komentuoti.');
        }

        $request->validate(['comment' => 'required|string']);

        ForumComment::create([
            'forum_post_id' => $post->id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
        ]);

        return back();
    }
    public function upvote(ForumPost $post)
    {
        $userId = auth()->id();

        if ($post->hasVotedBy($userId)) {
            return back()->with('error', 'Negalite balsuoti keletą kartų.');
        }

        $post->increment('upvotes');
        ForumPostVote::create([
            'forum_post_id' => $post->id,
            'user_id' => $userId,
        ]);

        return back();
    }

    public function upvoteComment(ForumComment $comment)
    {
        $userId = auth()->id();

        if ($comment->hasVotedBy($userId)) {
            return back()->with('error', 'Negalite balsuoti keletą kartų.');
        }

        ForumCommentVote::create([
            'forum_comment_id' => $comment->id,
            'user_id' => $userId,
        ]);

        return back();
    }

    public function edit(ForumPost $post)
    {
        if (auth()->id() !== $post->user_id && auth()->user()->role !== 'admin') {
            abort(403);
        }
        return view('forum.edit', compact('post'));
    }

    public function update(Request $request, ForumPost $post)
    {
        if (auth()->id() !== $post->user_id && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('forum_images', 'public');
            $post->image_path = $imagePath;
        }

        $post->update([
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return redirect()->route('forum.show', $post)->with('success', 'Įrašas atnaujintas.');
    }

    public function pinComment(ForumComment $comment)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        $comment->pinned = !$comment->pinned;
        $comment->save();

        return back();
    }


}


?>
