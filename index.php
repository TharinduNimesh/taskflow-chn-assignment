<?php
require_once(__DIR__ . '/models/Task.php');

try {
    $taskModel = new Task();
    $tasks = $taskModel->getAllTasks();
} catch (Exception $e) {
    error_log($e->getMessage());
    $tasks = [];
}

$pageTitle = 'Home - Note App';

// Start building the HTML content
ob_start();
?>
<div class="relative overflow-hidden">
    <!-- Blurred Blobs -->
    <div class="absolute top-0 left-0 w-72 h-72 bg-blue-600 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
    <div class="absolute top-0 right-0 w-72 h-72 bg-violet-600 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-20 w-72 h-72 bg-indigo-600 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>

    <div class="container mx-auto p-4 relative">
        <!-- Header Section -->
        <div class="space-y-6 mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold mb-2 bg-gradient-to-r from-blue-500 to-violet-600 bg-clip-text text-transparent">My Tasks</h1>
                    <p class="dark:text-gray-400">Organize and track your progress</p>
                </div>
                <a href="/create-task.php" class="bg-gradient-to-r from-blue-600 to-violet-600 text-white px-6 py-2 rounded-lg hover:opacity-90 transition-all duration-200 shadow-lg hover:shadow-violet-500/20">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        New Task
                    </span>
                </a>
            </div>
            
            <!-- Filter Section -->
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-4 border border-gray-700/50 shadow-lg transition-all duration-300 hover:shadow-violet-500/20">
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Search Input -->
                    <div class="flex-grow relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" placeholder="Search tasks..." class="w-full pl-10 pr-4 py-2 bg-gray-900/50 border border-gray-700/50 rounded-lg focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500/20 text-gray-300 placeholder-gray-500">
                    </div>
                    
                    <!-- Category Filter -->
                    <div class="flex gap-2 flex-wrap md:flex-nowrap">
                        <button class="filter-category px-4 py-2 rounded-lg bg-violet-600/20 text-violet-400 hover:bg-violet-600/30 transition-colors duration-200 flex items-center gap-2 ring-2 ring-violet-500/50">
                            <span>All</span>
                        </button>
                        <button class="filter-category px-4 py-2 rounded-lg bg-blue-600/20 text-blue-400 hover:bg-blue-600/30 transition-colors duration-200 flex items-center gap-2">
                            <span>Planning</span>
                        </button>
                        <button class="filter-category px-4 py-2 rounded-lg bg-indigo-600/20 text-indigo-400 hover:bg-indigo-600/30 transition-colors duration-200 flex items-center gap-2">
                            <span>Development</span>
                        </button>
                        <button class="filter-category px-4 py-2 rounded-lg bg-violet-600/20 text-violet-400 hover:bg-violet-600/30 transition-colors duration-200 flex items-center gap-2">
                            <span>Testing</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($tasks as $task): ?>
            <a href="/task-view.php?id=<?php echo htmlspecialchars($task['id']); ?>" class="block">
                <div class="task-card group relative bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 shadow-lg transition-all duration-300 hover:shadow-violet-500/20 hover:-translate-y-1 border border-transparent hover:border-violet-500/20" style="transition: opacity 0.3s ease, transform 0.3s ease">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-violet-500/5 rounded-2xl -z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="p-3 bg-blue-600/20 rounded-xl group-hover:bg-blue-600/30 transition-colors duration-300">
                            <?php
                            $icon = '';
                            switch($task['category']) {
                                case 'planning':
                                    $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>';
                                    break;
                                case 'development':
                                    $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path>';
                                    break;
                                case 'testing':
                                    $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                                    break;
                            }
                            ?>
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?php echo $icon; ?></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white group-hover:bg-gradient-to-r group-hover:from-blue-500 group-hover:to-violet-500 group-hover:bg-clip-text group-hover:text-transparent transition-all duration-300"><?php echo htmlspecialchars($task['title']); ?></h3>
                    </div>
                    <p class="text-gray-400 mb-6 line-clamp-2">
                        <?php 
                        // Strip HTML tags, remove \r\n characters, and truncate description to keep it short
                        $plainDescription = strip_tags($task['description']);
                        
                        // Replace all variations of whitespace and line breaks with a single space
                        $plainDescription = preg_replace('/(\r\n|\r|\n|\s)+/', ' ', $plainDescription); 
                        // Also replace any literal "\r" or "\n" strings that might be in the text
                        $plainDescription = str_replace(['\r', '\n', '\\r', '\\n'], ' ', $plainDescription);
                        
                        echo htmlspecialchars(substr($plainDescription, 0, 150) . (strlen($plainDescription) > 150 ? '...' : ''));
                        ?>
                    </p>
                    <div class="flex flex-wrap gap-2">
                        <span class="task-category px-4 py-1.5 bg-blue-600/20 text-blue-400 rounded-full text-sm font-medium group-hover:bg-blue-600/30 transition-colors duration-300"><?php echo ucfirst(htmlspecialchars($task['category'])); ?></span>
                        <span class="task-category px-4 py-1.5 bg-violet-600/20 text-violet-400 rounded-full text-sm font-medium group-hover:bg-violet-600/30 transition-colors duration-300"><?php echo ucfirst(htmlspecialchars($task['priority'])); ?> Priority</span>
                        <?php if (!empty($task['status']) && $task['status'] !== 'pending'): ?>
                        <span class="task-category px-4 py-1.5 bg-indigo-600/20 text-indigo-400 rounded-full text-sm font-medium group-hover:bg-indigo-600/30 transition-colors duration-300"><?php echo ucfirst(str_replace('_', ' ', $task['status'])); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();

require_once(__DIR__ . '/layouts/base.php');
?>