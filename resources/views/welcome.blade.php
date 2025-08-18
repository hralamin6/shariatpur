<!DOCTYPE html>
<html lang="en" x-data="mainState" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faridpur City App</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Google Fonts (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Using Inter as the default font */
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Custom scrollbar for better aesthetics */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .dark ::-webkit-scrollbar-track {
            background: #2d3748;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #555;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        .dark ::-webkit-scrollbar-thumb:hover {
            background: #333;
        }
    </style>

    <script>
        // Tailwind dark mode configuration
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#16a34a', // Green color from the screenshot
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js Data Initialization -->
    <!-- This script MUST come BEFORE the Alpine.js script tag -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('mainState', () => ({
                // Dark mode state, initialized from localStorage
                darkMode: localStorage.getItem('darkMode') === 'true',
                toggleDarkMode() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('darkMode', this.darkMode);
                },

                // Active tab for bottom navigation
                activeTab: 'হোম',

                // Data for the service grid items
                services: [
                    { name: 'ডাক্তার', icon: 'bx bxs-user-plus', color: 'text-blue-500' },
                    { name: 'হাসপাতাল', icon: 'bx bxs-hospital', color: 'text-red-500' },
                    { name: 'বাসের সময়সূচি', icon: 'bx bxs-bus', color: 'text-green-500' },
                    { name: 'ট্রেনের সময়সূচি', icon: 'bx bxs-train', color: 'text-sky-500' },
                    { name: 'দর্শনীয় স্থান', icon: 'bx bxs-camera', color: 'text-purple-500' },
                    { name: 'বাসা ভাড়া', icon: 'bx bxs-home-heart', color: 'text-orange-500' },
                    { name: 'শপিং', icon: 'bx bxs-shopping-bag', color: 'text-pink-500' },
                    { name: 'ফায়ার সার্ভিস', icon: 'bx bxs-hot', color: 'text-red-600' },
                    { name: 'কুরিয়ার সার্ভিস', icon: 'bx bxs-truck', color: 'text-yellow-500' },
                    { name: 'থানা-পুলিশ', icon: 'bx bxs-shield-alt-2', color: 'text-blue-700' },
                    { name: 'ওয়েবসাইট', icon: 'bx bx-globe', color: 'text-indigo-500' },
                    { name: 'বিদ্যুৎ অফিস', icon: 'bx bxs-bulb', color: 'text-yellow-400' },
                    { name: 'ডায়াগনস্টিক', icon: 'bx bxs-flask', color: 'text-teal-500' },
                    { name: 'রক্ত', icon: 'bx bxs-droplet', color: 'text-red-500' },
                    { name: 'হোটেল', icon: 'bx bxs-hotel', color: 'text-amber-600' },
                    { name: 'গাড়ি ভাড়া', icon: 'bx bxs-car', color: 'text-gray-600' },
                    { name: 'মিস্ত্রি', icon: 'bx bxs-wrench', color: 'text-gray-500' },
                    { name: 'জরুরী সেবা', icon: 'bx bxs-phone-call', color: 'text-rose-500' },
                    { name: 'চাকরি', icon: 'bx bxs-briefcase', color: 'text-cyan-500' },
                    { name: 'উদ্যোক্তা', icon: 'bx bxs-dollar-circle', color: 'text-lime-500' },
                    { name: 'শিক্ষক', icon: 'bx bxs-graduation', color: 'text-blue-600' },
                    { name: 'পার্লার', icon: 'bx bxs-florist', color: 'text-fuchsia-500' },
                    { name: 'রেস্টুরেন্ট', icon: 'bx bxs-dish', color: 'text-orange-400' },
                    { name: 'ফ্ল্যাট ও জমি', icon: 'bx bxs-building-house', color: 'text-indigo-600' },
                    { name: 'ভিডিও', icon: 'bx bxs-videos', color: 'text-blue-500' },
                    { name: 'নিউজ', icon: 'bx bxs-news', color: 'text-gray-500' },
                    { name: 'অন্যান্য', icon: 'bx bxs-category', color: 'text-teal-500' },
                    { name: 'প্রোফাইল', icon: 'bx bxs-user-circle', color: 'text-green-500' },
                ],

                // Data for the bottom navigation items
                navigation: [
                    { name: 'হোম', icon: 'bx bxs-home' },
                    { name: 'যোগাযোগ', icon: 'bx bxs-chat' },
                    { name: 'নোটিফিকেশন', icon: 'bx bxs-bell' },
                    { name: 'প্রোফাইল', icon: 'bx bxs-user' },
                ]
            }));
        });
    </script>

    <!-- Alpine.js -->
    <!-- The "defer" attribute is important to ensure it runs after the DOM is parsed -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 antialiased">

<!-- Main container -->
<div class="max-w-md mx-auto bg-white dark:bg-gray-800 shadow-lg min-h-screen pb-24">

    <!-- Header -->
    <header class="flex items-center justify-between p-4 sticky top-0 bg-white dark:bg-gray-800 z-10 shadow-sm">
        <button class="text-2xl text-gray-600 dark:text-gray-300">
            <i class='bx bx-menu'></i>
        </button>
        <h1 class="text-2xl font-bold text-primary">
            Faridpur City<span class="font-normal text-gray-500 dark:text-gray-400">app</span>
        </h1>
        <!-- Dark mode toggle button -->
        <button @click="toggleDarkMode" class="text-2xl text-gray-600 dark:text-gray-300">
            <i class='bx' :class="darkMode ? 'bxs-sun' : 'bxs-moon'"></i>
        </button>
    </header>

    <!-- Main Content -->
    <main class="p-4">
        <!-- Image Banner/Slider -->
        <div class="relative rounded-xl overflow-hidden mb-4 shadow-md">
            <img src="https://placehold.co/600x250/a0c4ff/ffffff?text=Faridpur+City"
                 alt="Faridpur City Banner"
                 class="w-full h-auto object-cover"
                 onerror="this.onerror=null;this.src='https://placehold.co/600x250/cccccc/ffffff?text=Image+Not+Found';">
            <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex space-x-2">
                <span class="block w-2 h-2 bg-white rounded-full"></span>
                <span class="block w-2 h-2 bg-white/50 rounded-full"></span>
                <span class="block w-2 h-2 bg-white/50 rounded-full"></span>
            </div>
        </div>

        <!-- Services Grid -->
        <div class="grid grid-cols-4 gap-3 sm:gap-4">
            <!-- Service items data -->
            <template x-for="item in services" :key="item.name">
                <a href="#" class="flex flex-col items-center justify-center text-center p-2 bg-gray-50 dark:bg-gray-700 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="text-3xl mb-1" :class="item.color">
                        <i :class="item.icon"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-600 dark:text-gray-300" x-text="item.name"></span>
                </a>
            </template>
        </div>
    </main>

    <!-- Bottom Navigation -->
    <footer class="fixed bottom-0 left-0 right-0 max-w-md mx-auto bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-[0_-2px_5px_rgba(0,0,0,0.05)]">
        <div class="flex justify-around items-center h-16">
            <template x-for="nav in navigation" :key="nav.name">
                <a href="#" class="flex flex-col items-center justify-center w-full transition-colors duration-200"
                   :class="activeTab === nav.name ? 'text-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary'"
                   @click.prevent="activeTab = nav.name">
                    <i class="text-2xl" :class="nav.icon"></i>
                    <span class="text-xs font-medium" x-text="nav.name"></span>
                </a>
            </template>
        </div>
    </footer>
</div>

</body>
</html>
