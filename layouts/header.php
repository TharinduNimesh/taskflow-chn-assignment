<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="sticky top-4 z-50 mx-4">
    <nav class="container mx-auto flex justify-between items-center bg-gray-800/90 backdrop-blur-sm border border-gray-700/50 text-white p-6 rounded-2xl shadow-lg shadow-violet-500/10">
        <div class="flex items-center gap-2">
            <div class="text-2xl font-bold">
                <span class="bg-gradient-to-r from-blue-500 to-violet-600 bg-clip-text text-transparent">Task</span>
                <span class="bg-gradient-to-r from-violet-600 to-indigo-600 bg-clip-text text-transparent">Flow</span>
            </div>
        </div>
        <div class="flex items-center space-x-6">
            <?php if (isset($_SESSION['user'])): ?>
                <a href="/" class="hover:text-violet-400 transition-colors duration-200">Dashboard</a>
                <div class="relative group">
                    <div class="flex items-center gap-3 cursor-pointer">
                        <span class="text-gray-300"><?php echo $_SESSION['user']['name'] ?? 'User'; ?></span>
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-violet-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                    <!-- Dropdown menu -->
                    <div class="absolute right-0 mt-2 w-48 rounded-lg bg-gray-800 border border-gray-700/50 shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                        <div class="p-2">
                            <a href="/logout.php" class="block px-4 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700/50 hover:text-violet-400 transition-all duration-200">
                                Sign Out
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="flex items-center space-x-4">
                    <a href="/signin.php" class="hover:text-violet-400 transition-colors duration-200">Sign In</a>
                    <a href="/signup.php" class="px-4 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-violet-600 hover:from-blue-600 hover:to-violet-700 transition-all duration-200">Sign Up</a>
                </div>
            <?php endif; ?>
        </div>
    </nav>
</header>