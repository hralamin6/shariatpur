<?php

namespace App\Livewire\Web\News;

use App\Models\News;
use App\Models\NewsComment;
use App\Models\NewsLike;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class NewsDetailsComponent extends Component
{
    public string $slug;

    public ?News $news = null;

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
        $this->news = News::with(['category','user','upazila'])->where('status','active')->where('slug', $slug)->first();
        if (! $this->news) {
            abort(404);
        }
        $this->recordView();
    }

    protected function recordView(): void
    {
        if (method_exists(session(), 'isStarted') && ! session()->isStarted()) {
            session()->start();
        }
        $sessionId = session()->getId();
        $ip = request()->ip();
        $key = 'news_viewed_'.$this->news->id.'_'.$sessionId;
        if (! Cache::has($key)) {
            $ipKey = $sessionId ? null : ('news_viewed_'.$this->news->id.'_ip_'.$ip);
            if ($ipKey && Cache::has($ipKey)) {
                return;
            }
            $this->news->increment('views');
            Cache::put($key, true, now()->addMinutes(60));
            if ($ipKey) { Cache::put($ipKey, true, now()->addMinutes(60)); }
        }
    }

    public function toggleLike(): void
    {
        if (! auth()->check()) {
            $this->redirect(route('login'));
            return;
        }
        $like = NewsLike::query()->where('news_id',$this->news->id)->where('user_id', auth()->id())->first();
        if ($like) { $like->delete(); }
        else { NewsLike::create(['news_id'=>$this->news->id,'user_id'=>auth()->id()]); }
    }

    public function addComment(): void
    {
        if (! auth()->check()) { $this->redirect(route('login')); return; }
        $this->validate();
        NewsComment::create([
            'news_id' => $this->news->id,
            'user_id' => auth()->id(),
            'body' => $this->commentText,
        ]);
        $this->commentText = '';
    }

    public function deleteComment(int $commentId): void
    {
        if (! auth()->check()) { $this->redirect(route('login')); return; }
        $comment = $this->news->comments()->whereKey($commentId)->first();
        if (! $comment) { abort(404); }
        $isAdmin = optional(auth()->user()->role)->slug === 'admin';
        $isNewsOwner = $this->news->user_id === auth()->id();
        $isCommentOwner = $comment->user_id === auth()->id();
        if (! ($isAdmin || $isNewsOwner || $isCommentOwner)) { abort(403); }
        $comment->delete();
    }

    public function render()
    {
        $photoUrl = null;
        if ($this->news && method_exists($this->news, 'getFirstMediaUrl')) {
            $photoUrl = $this->news->getFirstMediaUrl('news', 'avatar') ?: $this->news->getFirstMediaUrl('news');
        }
        $related = News::query()->with('category')
            ->where('status','active')
            ->where('id','!=',$this->news->id)
            ->where('news_category_id', $this->news->news_category_id)
            ->latest()->limit(6)->get();
        $comments = $this->news->comments()->with('user')->latest()->get();
        $likesCount = $this->news->likes()->count();
        $viewsCount = (int) ($this->news->views ?? 0);
        $liked = $this->news->isLikedBy(auth()->user());
        $shareUrl = rawurlencode(route('web.news.details', $this->news->slug));
        $shareText = rawurlencode($this->news->title);

        return view('livewire.web.news.news-details-component', [
            'news' => $this->news,
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

