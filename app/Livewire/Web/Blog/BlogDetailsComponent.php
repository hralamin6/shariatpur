<?php

namespace App\Livewire\Web\Blog;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\BlogLike;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class BlogDetailsComponent extends Component
{
    public string $slug;

    public ?Blog $blog = null;

    public string $commentText = '';

    protected function rules(): array
    {
        return [
            'commentText' => 'required|string|max:1000',
        ];
    }

    public function mount(string $slug): void
    {
        $this->slug = $slug;
        $this->blog = Blog::with(['blogCategory', 'user', 'upazila'])->where('status', 'active')->where('slug', $slug)->first();
        if (! $this->blog) {
            abort(404);
        }

        $this->recordView();
    }

    protected function recordView(): void
    {
        // Ensure session available
        if (method_exists(session(), 'isStarted') && ! session()->isStarted()) {
            session()->start();
        }

        $sessionId = session()->getId();
        $ip = request()->ip();
        $key = 'blog_viewed_'.$this->blog->id.'_'.$sessionId;

        if (! Cache::has($key)) {
            // Fallback to IP if no session id
            $ipKey = $sessionId ? null : ('blog_viewed_'.$this->blog->id.'_ip_'.$ip);

            if ($ipKey && Cache::has($ipKey)) {
                return; // already counted for this IP recently
            }

            $this->blog->increment('views');

            // remember for 60 minutes
            Cache::put($key, true, now()->addMinutes(60));
            if ($ipKey) {
                Cache::put($ipKey, true, now()->addMinutes(60));
            }
        }
    }

    public function toggleLike(): void
    {
        if (! auth()->check()) {
            $this->redirect(route('login'));
            return;
        }

        $like = BlogLike::query()
            ->where('blog_id', $this->blog->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($like) {
            $like->delete();
        } else {
            BlogLike::create([
                'blog_id' => $this->blog->id,
                'user_id' => auth()->id(),
            ]);
        }
    }

    public function addComment(): void
    {
        if (! auth()->check()) {
            $this->redirect(route('login'));
            return;
        }

        $this->validate();

        Comment::create([
            'blog_id' => $this->blog->id,
            'user_id' => auth()->id(),
            'body' => $this->commentText,
        ]);

        $this->commentText = '';
    }

    public function deleteComment(int $commentId): void
    {
        if (! auth()->check()) {
            $this->redirect(route('login'));
            return;
        }

        $comment = $this->blog->comments()->whereKey($commentId)->first();
        if (! $comment) {
            abort(404);
        }

        $isAdmin = optional(auth()->user()->role)->slug === 'admin';
        $isBlogOwner = $this->blog->user_id === auth()->id();
        $isCommentOwner = $comment->user_id === auth()->id();

        if (! ($isAdmin || $isBlogOwner || $isCommentOwner)) {
            abort(403);
        }

        $comment->delete();
    }

    public function render()
    {
        $photoUrl = null;
        if ($this->blog && method_exists($this->blog, 'getFirstMediaUrl')) {
            $photoUrl = $this->blog->getFirstMediaUrl('blog', 'avatar') ?: $this->blog->getFirstMediaUrl('blog');
        }

        $related = Blog::query()
            ->with('blogCategory')
            ->where('status', 'active')
            ->where('id', '!=', $this->blog->id)
            ->where('blog_category_id', $this->blog->blog_category_id)
            ->latest()
            ->limit(6)
            ->get();

        $comments = $this->blog->comments()->with('user')->latest()->get();
        $likesCount = $this->blog->likes()->count();
        $viewsCount = (int) ($this->blog->views ?? 0);
        $liked = $this->blog->isLikedBy(auth()->user());

        $shareUrl = rawurlencode(route('web.blog.details', $this->blog->slug));
        $shareText = rawurlencode($this->blog->title);

        return view('livewire.web.blog.blog-details-component', [
            'blog' => $this->blog,
            'photoUrl' => $photoUrl,
            'related' => $related,
            'comments' => $comments,
            'likesCount' => $likesCount,
            'viewsCount' => $viewsCount,
            'liked' => $liked,
            'shareUrl' => $shareUrl,
            'shareText' => $shareText,
        ])->layout('components.layouts.web');
    }
}
