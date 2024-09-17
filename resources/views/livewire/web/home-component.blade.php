
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 dark:bg-gray-900">
    <!-- Header -->
    <header class="w-full text-center py-10 bg-white dark:bg-gray-800 shadow-md">
        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-gray-100">Welcome to Your Livewire Starter</h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 mt-4">Your project is ready to start building amazing things!</p>
        <a href="{{route('app.dashboard')}}" wire:navigate class="capitalize hidden md:block">dashboard</a>

    </header>

    <!-- Main Content -->
    <main class="flex-grow w-full max-w-6xl mx-auto p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card 1 -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Get Started</h2>
                <p class="mt-4 text-gray-600 dark:text-gray-400">
                    Explore the documentation to understand how to build features with Livewire and Tailwind CSS.
                </p>
                <a href="https://laravel-livewire.com/docs/2.x/quickstart" target="_blank" class="mt-6 inline-block bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                    Learn Livewire
                </a>
            </div>

            <!-- Card 2 -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Tailwind CSS</h2>
                <p class="mt-4 text-gray-600 dark:text-gray-400">
                    Use Tailwind CSS to rapidly build modern, responsive UI components.
                </p>
                <a href="https://tailwindcss.com/docs" target="_blank" class="mt-6 inline-block bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
                    Explore Tailwind
                </a>
            </div>

            <!-- Card 3 -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Community Support</h2>
                <p class="mt-4 text-gray-600 dark:text-gray-400">
                    Join the Livewire and Tailwind communities for help and inspiration on your next project.
                </p>
                <a href="https://github.com/livewire/livewire" target="_blank" class="mt-6 inline-block bg-purple-600 text-white py-2 px-4 rounded hover:bg-purple-700">
                    Join the Community
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full text-center py-4 bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400">
        <p>&copy; 2024 Your Project. Built with Livewire & Tailwind CSS.</p>
    </footer>
</div>
