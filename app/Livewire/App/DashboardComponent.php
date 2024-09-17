<?php

namespace App\Livewire\App;

use App\Events\LiveMessageSent;
use App\Events\MessageSent;
use App\Models\Category;
use App\Models\Conversation;
use App\Models\Livechat;
use App\Models\Message;
use App\Models\Post;
use App\Models\User;
use App\Notifications\MessageSentNotification;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class DashboardComponent extends Component
{

    use LivewireAlert, WithFileUploads;
    public $body;
    public $photo=[];
    public $image_url;
    public $paginateVar = 10;
    #[On('broadcastLiveMessageReceived')]
    public function broadcastLiveMessageReceived()
    {

        $msg_id = $this->data->last()->id;
        $this->dispatch('scrollBottom', message_id: "$msg_id", new_message: true);
    }

    public function logoutUser($sessionId)
    {
        DB::table('sessions')->where('id', $sessionId)->delete();

        $this->alert('success', __('Session deleted successfully'));
    }
    public function getDataProperty()
    {
        $message_count = Livechat::count();
        return Livechat::with('user')->skip($message_count-$this->paginateVar)->take($this->paginateVar)->get();
    }
    public function sendMessage()
    {
        $this->authorize('app.chats.create');

        $this->validate([
            'body' => 'required|max:2555',
            'photo.*' => 'nullable|image|max:2048', // 2MB Max
            'image_url' => 'nullable|url', // 2MB Max
        ]);

        $data = Livechat::create([
                'user_id' => auth()->id(),
                'body' => $this->body,
            ]);
            if ($this->image_url!=null) {
                $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION);
                $media =  $data->addMediaFromUrl($this->image_url)->usingFileName($data->id. '.' . $extension)->toMediaCollection('liveChatImages');
                $path = storage_path("app/public/Livechat/".$media->id.'/'. $media->file_name);
                if (file_exists($path)) {
                    unlink($path);
                }

            }elseif($this->photo!=null){
                foreach ($this->photo as $p) {
                    $media = $data->addMedia($p->getRealPath())->usingFileName($data->id. '.' . $p->extension())->toMediaCollection('liveChatImages');
                    $path = storage_path("app/public/Livechat/".$media->id.'/'. $media->file_name);
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }

            }
            $body = $this->body;
            $msg_id = $data->id;
        $this->dispatch('whisperTypingEnd');
            $this->dispatch('scrollBottom', message_id: "$msg_id", new_message: true);
            $this->reset('body', 'image_url', 'photo');
            broadcast(new LiveMessageSent(auth()->id(), $body))->toOthers();

    }
    function loadMore()
    {
        $this->paginateVar = $this->paginateVar + 10;
        $msd_id = $this->data->slice(-$this->paginateVar+10, 1)->first()->id;
        $this->dispatch('updatedHeight', ($msd_id));
        # code...
    }


    public function messageDelete(Livechat $message)
    {
        $this->authorize('app.chats.delete');

        $message->delete();
        $this->alert('success', __('Message deleted successfully!'));
    }
    #[Title('Create Post')]
    public function render()
    {
        $this->authorize('app.dashboard');

        $userStatistics = User::select(
            DB::raw('COUNT(*) as totalUsers'),
            DB::raw('COUNT(CASE WHEN status = "active" THEN 1 END) as activeUsers'),
            DB::raw('COUNT(CASE WHEN email_verified_at IS NOT NULL THEN 1 END) as verifiedUsers'),
            DB::raw('COUNT(CASE WHEN last_seen >= "' . now()->subDay() . '" THEN 1 END) as recentlyActiveUsers')
        )->first();
        $usersByRole = User::with('role')->select('role_id', DB::raw('count(*) as count'))
            ->groupBy('role_id')
            ->get();
        $totalSessions = DB::table('sessions')->count();


        $postStatistics = Post::select(
            DB::raw('COUNT(*) as totalPosts'),
            DB::raw('COUNT(CASE WHEN status = "published" THEN 1 END) as publishedPosts'),
            DB::raw('COUNT(CASE WHEN status = "draft" THEN 1 END) as draftPosts'),
            DB::raw('COUNT(CASE WHEN created_at >= "' . now()->subDay(7) . '" THEN 1 END) as recentPosts')
        )->first();

        // Count posts by category in a single query
        $postsByCategory = Category::withCount('posts')->whereHas('posts')->get();

        // Count total categories
        $totalCategories = Category::count();
        $recentPosts = Post::where('created_at', '>=', now()->subDays(7))->take(10)->orderBy('updated_at', 'desc')->get();



        $messageStatistics = Message::select(
            DB::raw('COUNT(*) as totalMessages'),
            DB::raw('COUNT(CASE WHEN `read` = 0 THEN 1 END) as unreadMessages'))->first();
        $totalConversations = Conversation::count();

        $totalNotifications = auth()->user()->notifications()->count();

        // Unread Notifications
        $unreadNotifications = auth()->user()->notifications()->whereNull('read_at')->count();

        // Read Notifications
        $readNotifications = auth()->user()->notifications()->whereNotNull('read_at')->count();

        // Notifications by Type (if applicable)

        $notifications = auth()->user()->notifications()->get();

        $notificationsByType = $notifications->groupBy(function($notification) {
            $data = $notification->data;

            // If the data field is a string, decode it, otherwise use it directly
            if (is_string($data)) {
                $data = json_decode($data, true);
            }

            // Get the 'type' from the data (either decoded or already in array form)
            return data_get($data, 'type', 'unknown');
        })->map(function($group) {
            return $group->count();
        });


        // Get all active sessions and corresponding users
        $activeSessions = DB::table('sessions')->whereNotNull('user_id')->get();

        // Map the session data to users
        $loggedInUsers = $activeSessions->map(function ($session) {
            $user = User::find($session->user_id);
            return (object)[
                'user' => $user,
                'ip_address' => $session->ip_address,
                'last_activity' => $session->last_activity,
                'session_id' => $session->id,
            ];
        });


        return view('livewire.app.dashboard-component',
            [
                'totalUsers' => $userStatistics->totalUsers,
                'activeUsers' => $userStatistics->activeUsers,
                'verifiedUsers' => $userStatistics->verifiedUsers,
                'recentlyActiveUsers' => $userStatistics->recentlyActiveUsers,
                'usersByRole' => $usersByRole,
                'totalSessions' => $totalSessions,

                'totalPosts' => $postStatistics->totalPosts,
                'publishedPosts' => $postStatistics->publishedPosts,
                'draftPosts' => $postStatistics->draftPosts,
                'recentPosts' => $recentPosts,
                'postsByCategory' => $postsByCategory,
                'totalCategories' => $totalCategories,

                'totalConversations' => $totalConversations,
                'totalMessages' => $messageStatistics->totalMessages,
                'unreadMessages' => $messageStatistics->unreadMessages,


                'totalNotifications' => $totalNotifications,
                'unreadNotifications' => $unreadNotifications,
                'readNotifications' => $readNotifications,
                'notificationsByType' => $notificationsByType,


                'loggedInUsers' => $loggedInUsers,

                'items' => $this->data,
            ]
        );
    }


}
