<?php
require_once(__DIR__ . '/models/Task.php');

$taskId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    $taskModel = new Task();
    $task = $taskModel->getTaskById($taskId);
    
    if (!$task) {
        header('Location: /');
        exit;
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    header('Location: /');
    exit;
}

$pageTitle = htmlspecialchars($task['title']) . ' - TaskFlow';

// Start building the HTML content
ob_start();
?>
<div class="relative overflow-hidden">
    <!-- Blurred Blobs -->
    <div class="absolute top-0 left-0 w-72 h-72 bg-blue-600 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
    <div class="absolute top-0 right-0 w-72 h-72 bg-violet-600 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-20 w-72 h-72 bg-indigo-600 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>

    <div class="container mx-auto p-4 relative">
        <div class="max-w-4xl mx-auto">
            <!-- Back Button -->
            <a href="/" class="inline-flex items-center gap-2 text-gray-400 hover:text-violet-400 transition-colors duration-200 mb-6">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Dashboard
            </a>

            <!-- Task Content -->
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-8 border border-gray-700/50 shadow-lg transition-all duration-300 hover:shadow-violet-500/20">
                <!-- Task Header -->
                <div class="mb-8">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 bg-blue-600/20 rounded-xl">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-500 to-violet-600 bg-clip-text text-transparent">
                            <?php echo htmlspecialchars($task['title']); ?>
                        </h1>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <span class="px-4 py-1.5 bg-blue-600/20 text-blue-400 rounded-full text-sm font-medium"><?php echo ucfirst(htmlspecialchars($task['category'])); ?></span>
                        <span class="px-4 py-1.5 bg-violet-600/20 text-violet-400 rounded-full text-sm font-medium"><?php echo ucfirst(htmlspecialchars($task['priority'])); ?> Priority</span>
                        <span class="px-4 py-1.5 bg-gray-600/20 text-gray-400 rounded-full text-sm font-medium">Created: <?php echo date('M j, Y', strtotime($task['created_at'])); ?></span>
                        <?php if ($task['status'] !== 'pending'): ?>
                        <span class="px-4 py-1.5 bg-indigo-600/20 text-indigo-400 rounded-full text-sm font-medium"><?php echo ucfirst(str_replace('_', ' ', $task['status'])); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Task Description -->
                <div class="prose prose-invert max-w-none mb-8">
                    <div class="space-y-6 text-gray-300">
                        <div class="prose prose-invert max-w-none">
                        <?php 
                        // Display the task description as compiled HTML
                        // More aggressive normalization of whitespace in the HTML before displaying
                        $description = preg_replace('/(\r\n|\r|\n|\s)+/', ' ', $task['description']);
                        // Also replace any literal "\r" or "\n" strings that might be in the text
                        $description = str_replace(['\r', '\n', '\\r', '\\n'], ' ', $description);
                        echo $description;
                        ?>
                        </div>
                    </div>
                </div>

                <!-- Task Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tags Section -->
                    <div class="space-y-3">
                        <h3 class="text-lg font-semibold text-violet-400">Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($task['tags'] as $tag): ?>
                            <span class="px-3 py-1 bg-gradient-to-r from-violet-500/10 to-blue-500/10 rounded-full border border-violet-500/20 text-gray-300 text-sm"><?php echo htmlspecialchars($tag); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Priority Section -->
                    <div class="space-y-3">
                        <h3 class="text-lg font-semibold text-violet-400">Priority Level</h3>
                        <div class="flex items-center gap-2">
                            <div class="h-3 w-3 rounded-full bg-gradient-to-r <?php
                            switch($task['priority']) {
                                case 'high':
                                    echo 'from-red-400 to-red-500';
                                    break;
                                case 'medium':
                                    echo 'from-yellow-400 to-yellow-500';
                                    break;
                                case 'low':
                                    echo 'from-green-400 to-green-500';
                                    break;
                            }
                            ?>"></div>
                            <span class="text-gray-300"><?php echo ucfirst($task['priority']); ?> Priority</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();

require_once(__DIR__ . '/layouts/base.php');
?>