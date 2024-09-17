<h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 my-2">@lang("users statistics")</h1>
<div class="grid grid-cols-1 justify-between md:grid-cols-2 lg:grid-cols-4 gap-2 capitalize">
    <!-- Total Users -->
    <div class="flex gap-4 bg-white border dark:border-gray-600 dark:bg-darker p-4 rounded-lg shadow-md">
        <div class="rounded-full bg-yellow-500 p-4 text-white flex items-center justify-center">
            <i class='bx bx-user text-3xl'></i>
        </div>
        <div class="flex flex-col justify-center">
            <p class="font-semibold text-gray-600 dark:text-gray-200">Total Users</p>
            <p class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $totalUsers }}</p>
        </div>
    </div>

    <!-- Active Users -->
    <div class="flex gap-4 bg-white border dark:border-gray-600 dark:bg-darker p-4 rounded-lg shadow-md">
        <div class="rounded-full bg-green-500 p-4 text-white flex items-center justify-center">
            <i class='bx bx-check-circle text-3xl'></i>
        </div>
        <div class="flex flex-col justify-center">
            <p class="font-semibold text-gray-600 dark:text-gray-200">Active Users</p>
            <p class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $activeUsers }}</p>
        </div>
    </div>

    <!-- Verified Users -->
    <div class="flex gap-4 bg-white border dark:border-gray-600 dark:bg-darker p-4 rounded-lg shadow-md">
        <div class="rounded-full bg-blue-500 p-4 text-white flex items-center justify-center">
            <i class='bx bx-envelope text-3xl'></i>
        </div>
        <div class="flex flex-col justify-center">
            <p class="font-semibold text-gray-600 dark:text-gray-200">Verified Users</p>
            <p class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $verifiedUsers }}</p>
        </div>
    </div>

    <!-- Recently Active Users -->
    <div class="flex gap-4 bg-white border dark:border-gray-600 dark:bg-darker p-4 rounded-lg shadow-md">
        <div class="rounded-full bg-purple-500 p-4 text-white flex items-center justify-center">
            <i class='bx bx-time text-3xl'></i>
        </div>
        <div class="flex flex-col justify-center">
            <p class="font-semibold text-gray-600 dark:text-gray-200">Recently Active Users</p>
            <p class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $recentlyActiveUsers }}</p>
        </div>
    </div>

    <!-- Total Sessions -->
    <div class="flex gap-4 bg-white border dark:border-gray-600 dark:bg-darker p-4 rounded-lg shadow-md">
        <div class="rounded-full bg-red-500 p-4 text-white flex items-center justify-center">
            <i class='bx bx-line-chart text-3xl'></i>
        </div>
        <div class="flex flex-col justify-center">
            <p class="font-semibold text-gray-600 dark:text-gray-200">Total Sessions</p>
            <p class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $totalSessions }}</p>
        </div>
    </div>

    <!-- Users by Role -->
    <div class="md:col-span-2 md:row-span-2 bg-white border dark:border-gray-600 dark:bg-darker p-6 rounded-lg shadow-md">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="rounded-full bg-indigo-500 p-3 text-white flex items-center justify-center">
                    <i class='bx bx-group text-3xl'></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-700 dark:text-gray-200">Users by Role</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Distribution of users based on roles</p>
                </div>
            </div>
            <button class="bg-indigo-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-600 transition">
                Manage Roles
            </button>
        </div>

        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($usersByRole as $role)
                <li class="flex items-center gap-4 p-4 bg-gray-100 dark:bg-darkBg rounded-lg">
                    <div class="rounded-full bg-indigo-100 dark:bg-indigo-600 p-3 flex items-center justify-center w-12 h-12">
                        <span class="text-lg font-bold text-indigo-500 dark:text-indigo-100">{{ $role->count }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-700 dark:text-gray-200">{{ $role->role->name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $role->count }} users</p>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 my-2">post & category statistics</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 capitalize">

    <!-- Total Posts -->
    <div class="flex gap-4 bg-white border dark:border-gray-600 dark:bg-darker p-4 rounded-lg shadow-md">
        <div class="rounded-full bg-yellow-500 p-4 text-white flex items-center justify-center">
            <i class='bx bx-file text-3xl'></i>
        </div>
        <div class="flex flex-col justify-center">
            <p class="text-lg font-semibold text-gray-600 dark:text-gray-200">Total Posts</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $totalPosts }}</p>
        </div>
    </div>

    <!-- Total Categories -->
    <div class="flex gap-4 bg-white border dark:border-gray-600 dark:bg-darker p-4 rounded-lg shadow-md">
        <div class="rounded-full bg-blue-500 p-4 text-white flex items-center justify-center">
            <i class='bx bx-category text-3xl'></i>
        </div>
        <div class="flex flex-col justify-center">
            <p class="text-lg font-semibold text-gray-600 dark:text-gray-200">Total Categories</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $totalCategories }}</p>
        </div>
    </div>

    <!-- Published Posts -->
    <div class="flex gap-4 bg-white border dark:border-gray-600 dark:bg-darker p-4 rounded-lg shadow-md">
        <div class="rounded-full bg-green-500 p-4 text-white flex items-center justify-center">
            <i class='bx bx-check-circle text-3xl'></i>
        </div>
        <div class="flex flex-col justify-center">
            <p class="text-lg font-semibold text-gray-600 dark:text-gray-200">Published Posts</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $publishedPosts }}</p>
        </div>
    </div>

    <!-- Draft Posts -->
    <div class="flex gap-4 bg-white border dark:border-gray-600 dark:bg-darker p-4 rounded-lg shadow-md">
        <div class="rounded-full bg-red-500 p-4 text-white flex items-center justify-center">
            <i class='bx bx-edit text-3xl'></i>
        </div>
        <div class="flex flex-col justify-center">
            <p class="text-lg font-semibold text-gray-600 dark:text-gray-200">Draft Posts</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $draftPosts }}</p>
        </div>
    </div>

    <!-- Posts by Category -->
    <div class="col-span-1 md:col-span-2 lg:col-span-4">
        <div class="bg-white border dark:border-gray-600 dark:bg-darker p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Posts by Category</h2>
            <ul class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($postsByCategory as $category)
                    <li class="flex gap-4 bg-gray-100 dark:bg-darkBg p-4 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="rounded-full bg-indigo-500 p-3 text-white">
                                {{ $category->posts_count }}
                            </div>
                        </div>
                        <div class="flex flex-col justify-center">
                            <p class="font-semibold text-gray-700 dark:text-gray-200">{{ $category->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $category->posts_count }} posts</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Recent Posts -->
    <div class="col-span-1 md:col-span-2 lg:col-span-4">
        <div class="bg-white border dark:border-gray-600 dark:bg-darker p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Recent Posts (Last 7 Days)</h2>
            <ul class="space-y-4">
                @foreach($recentPosts as $post)
                    <li class="flex gap-4 items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-full bg-yellow-500 p-3 text-white">
                                <i class='bx bx-calendar text-xl'></i>
                            </div>
                        </div>
                        <div class="flex-grow">
                            <p class="font-semibold text-gray-700 dark:text-gray-200">{{ $post->title }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $post->created_at->format('M d, Y') }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 my-2">messages statistics</h1>

<div class="grid grid-cols-1 justify-between md:grid-cols-2 lg:grid-cols-4 gap-2 capitalize">
    <!-- Total Conversations -->
    <div class="flex gap-4 bg-white border dark:border-gray-600 dark:bg-darker p-4 rounded-lg shadow-md">
        <div class="rounded-full bg-blue-500 p-4 text-white flex items-center justify-center">
            <i class='bx bx-conversation text-3xl'></i>
        </div>
        <div class="flex flex-col justify-center">
            <p class="font-semibold text-gray-600 dark:text-gray-200">Total Conversations</p>
            <p class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $totalConversations }}</p>
        </div>
    </div>

    <!-- Total Messages -->
    <div class="flex gap-4 bg-white border dark:border-gray-600 dark:bg-darker p-4 rounded-lg shadow-md">
        <div class="rounded-full bg-green-500 p-4 text-white flex items-center justify-center">
            <i class='bx bx-message text-3xl'></i>
        </div>
        <div class="flex flex-col justify-center">
            <p class="font-semibold text-gray-600 dark:text-gray-200">Total Messages</p>
            <p class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $totalMessages }}</p>
        </div>
    </div>

    <!-- Unread Messages -->
    <div class="flex gap-4 bg-white border dark:border-gray-600 dark:bg-darker p-4 rounded-lg shadow-md">
        <div class="rounded-full bg-red-500 p-4 text-white flex items-center justify-center">
            <i class='bx bx-envelope text-3xl'></i>
        </div>
        <div class="flex flex-col justify-center">
            <p class="font-semibold text-gray-600 dark:text-gray-200">Unread Messages</p>
            <p class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $unreadMessages }}</p>
        </div>
    </div>
</div>

<h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 my-2">Notifications Statistics</h1>

<div class="grid grid-cols-1 justify-between md:grid-cols-2 lg:grid-cols-4 gap-2 capitalize">
    <!-- Total Notifications -->
    <div class="flex gap-4 bg-white border dark:border-gray-600 dark:bg-darker p-4 rounded-lg shadow-md">
        <div class="rounded-full bg-yellow-500 p-4 text-white flex items-center justify-center">
            <i class='bx bx-bell text-3xl'></i>
        </div>
        <div class="flex flex-col justify-center">
            <p class="font-semibold text-gray-600 dark:text-gray-200">Total Notifications</p>
            <p class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $totalNotifications }}</p>
        </div>
    </div>

    <!-- Unread Notifications -->
    <div class="flex gap-4 bg-white border dark:border-gray-600 dark:bg-darker p-4 rounded-lg shadow-md">
        <div class="rounded-full bg-red-500 p-4 text-white flex items-center justify-center">
            <i class='bx bx-envelope text-3xl'></i>
        </div>
        <div class="flex flex-col justify-center">
            <p class="font-semibold text-gray-600 dark:text-gray-200">Unread Notifications</p>
            <p class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $unreadNotifications }}</p>
        </div>
    </div>

    <!-- Read Notifications -->
    <div class="flex gap-4 bg-white border dark:border-gray-600 dark:bg-darker p-4 rounded-lg shadow-md">
        <div class="rounded-full bg-green-500 p-4 text-white flex items-center justify-center">
            <i class='bx bx-check-circle text-3xl'></i>
        </div>
        <div class="flex flex-col justify-center">
            <p class="font-semibold text-gray-600 dark:text-gray-200">Read Notifications</p>
            <p class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $readNotifications }}</p>
        </div>
    </div>

    <!-- Notifications by Type -->
    <div class="md:col-span-2 md:row-span-2 bg-white border dark:border-gray-600 dark:bg-darker p-6 rounded-lg shadow-md">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="rounded-full bg-indigo-500 p-3 text-white flex items-center justify-center">
                    <i class='bx bx-bell text-3xl'></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-700 dark:text-gray-200">Notifications by Type</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Overview of notifications by category</p>
                </div>
            </div>
            <button class="bg-indigo-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-600 transition">
                Manage Notifications
            </button>
        </div>

        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($notificationsByType as $type => $count)
                <li class="flex items-center gap-4 p-4 bg-gray-100 dark:bg-darkBg rounded-lg">
                    <div class="rounded-full bg-indigo-100 dark:bg-indigo-600 p-3 flex items-center justify-center w-12 h-12">
                        <span class="text-lg font-bold text-indigo-500 dark:text-indigo-100">{{ $count }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-700 dark:text-gray-200">{{ ucfirst($type) }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $count }} notifications</p>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

</div>
