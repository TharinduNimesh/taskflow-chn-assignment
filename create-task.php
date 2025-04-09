<?php
session_start();
require_once(__DIR__ . '/config/database.php');
require_once(__DIR__ . '/models/Task.php');

// Redirect to login if not authenticated
if (!isset($_SESSION['user'])) {
    header('Location: /signin.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $taskModel = new Task();
        $taskId = $taskModel->createTask(
            $_POST['title'],
            $_POST['description'],
            $_POST['category'],
            $_POST['priority'],
            $_SESSION['user']['id']
        );

        // Handle tags
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        if (!empty($_POST['tags'])) {
            $tags = explode(',', $_POST['tags']);
            
            foreach ($tags as $tagName) {
                $tagName = trim(mysqli_real_escape_string($conn, $tagName));
                
                // Insert tag if it doesn't exist
                $query = "INSERT IGNORE INTO tags (name) VALUES (?)";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 's', $tagName);
                mysqli_stmt_execute($stmt);

                // Get tag ID
                $query = "SELECT id FROM tags WHERE name = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 's', $tagName);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $tag = mysqli_fetch_assoc($result);

                // Create task-tag relationship
                $query = "INSERT INTO task_tags (task_id, tag_id) VALUES (?, ?)";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 'ii', $taskId, $tag['id']);
                mysqli_stmt_execute($stmt);
            }
        }

        // Redirect to task view page
        header("Location: /task-view.php?id=" . $taskId);
        exit;

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$pageTitle = 'Create Task - TaskFlow';
$content = <<<HTML
<div class="relative overflow-hidden">
    <!-- Blurred Blobs -->
    <div class="absolute top-0 left-0 w-72 h-72 bg-blue-600 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
    <div class="absolute top-0 right-0 w-72 h-72 bg-violet-600 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-20 w-72 h-72 bg-indigo-600 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>

    <div class="container mx-auto p-4 relative">
        <div class="max-w-3xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold mb-2 bg-gradient-to-r from-blue-500 to-violet-600 bg-clip-text text-transparent">Create New Task</h1>
                <p class="dark:text-gray-400">Add a new task to your workflow</p>
            </div>

            <!-- Task Form -->
            <form action="/create-task.php" method="POST" class="space-y-6" id="createTaskForm">
                <!-- Title Input -->
                <div class="space-y-2">
                    <label for="title" class="block text-sm font-medium text-gray-300">Task Title</label>
                    <input type="text" id="title" name="title" required
                        class="w-full px-4 py-2 bg-gray-800/50 border border-gray-700/50 rounded-lg focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500/20 text-gray-300 placeholder-gray-500"
                        placeholder="Enter task title">
                </div>

                <!-- Description Input -->
                <div class="space-y-2">
                    <label for="description" class="block text-sm font-medium text-gray-300">Description</label>
                    <textarea id="description" name="description" rows="8"
                        class="w-full px-4 py-2 bg-gray-800/50 border border-gray-700/50 rounded-lg focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500/20 text-gray-300 placeholder-gray-500"
                        placeholder="Enter task description"></textarea>
                    <script>
                        // Initialize TinyMCE
                        tinymce.init({
                            selector: '#description',
                            plugins: 'lists link image code table',
                            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | link image | code',
                            skin: 'oxide-dark',
                            content_css: 'dark',
                            height: 300,
                            menubar: false,
                            branding: false,
                            promotion: false,
                            setup: function(editor) {
                                // Add required validation after TinyMCE is initialized
                                editor.on('init', function() {
                                    const form = document.getElementById('createTaskForm');
                                    form.addEventListener('submit', function(e) {
                                        const content = editor.getContent();
                                        if (!content) {
                                            e.preventDefault();
                                            alert('Description is required');
                                        }
                                    });
                                });
                            }
                        });
                    </script>
                </div>

                <!-- Category Selection -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-300">Category</label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4" id="categoryContainer">
                        <label class="relative flex cursor-pointer items-center justify-center rounded-xl border border-gray-700/50 bg-gray-800/50 p-4 hover:border-violet-500/20 hover:shadow-lg hover:shadow-violet-500/10 transition-all duration-200">
                            <input type="radio" name="category" value="planning" class="absolute h-0 w-0 opacity-0" required>
                            <div class="flex flex-col items-center space-y-2">
                                <div class="rounded-full bg-gradient-to-br from-blue-500/20 to-violet-600/20 p-2">
                                    <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <span class="text-gray-300">Planning</span>
                            </div>
                        </label>
                        <label class="relative flex cursor-pointer items-center justify-center rounded-xl border border-gray-700/50 bg-gray-800/50 p-4 hover:border-violet-500/20 hover:shadow-lg hover:shadow-violet-500/10 transition-all duration-200">
                            <input type="radio" name="category" value="development" class="absolute h-0 w-0 opacity-0">
                            <div class="flex flex-col items-center space-y-2">
                                <div class="rounded-full bg-gradient-to-br from-violet-500/20 to-indigo-600/20 p-2">
                                    <svg class="h-6 w-6 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </div>
                                <span class="text-gray-300">Development</span>
                            </div>
                        </label>
                        <label class="relative flex cursor-pointer items-center justify-center rounded-xl border border-gray-700/50 bg-gray-800/50 p-4 hover:border-violet-500/20 hover:shadow-lg hover:shadow-violet-500/10 transition-all duration-200">
                            <input type="radio" name="category" value="testing" class="absolute h-0 w-0 opacity-0">
                            <div class="flex flex-col items-center space-y-2">
                                <div class="rounded-full bg-gradient-to-br from-indigo-500/20 to-blue-600/20 p-2">
                                    <svg class="h-6 w-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <span class="text-gray-300">Testing</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Tags Input -->
                <div class="space-y-2">
                    <label for="tags" class="block text-sm font-medium text-gray-300">Tags</label>
                    <div class="relative">
                        <div id="tags-container" class="flex flex-wrap gap-2 p-2 bg-gray-800/50 border border-gray-700/50 rounded-lg min-h-[42px]">
                            <input type="text" id="tag-input" 
                                class="flex-grow bg-transparent outline-none text-gray-300 placeholder-gray-500 min-w-[120px]"
                                placeholder="Type and press Enter to add tags">
                        </div>
                        <input type="hidden" id="tags" name="tags" value="">
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const tagInput = document.getElementById('tag-input');
                            const tagsContainer = document.getElementById('tags-container');
                            const hiddenInput = document.getElementById('tags');
                            const tags = new Set();

                            function createTag(text) {
                                const tag = document.createElement('div');
                                tag.className = 'flex items-center gap-1 px-3 py-1 bg-gradient-to-r from-violet-500/10 to-blue-500/10 rounded-full border border-violet-500/20';
                                
                                const tagText = document.createElement('span');
                                tagText.className = 'text-gray-300 text-sm';
                                tagText.textContent = text;
                                
                                const removeBtn = document.createElement('button');
                                removeBtn.className = 'text-gray-400 hover:text-violet-400 transition-colors duration-200';
                                removeBtn.innerHTML = '×';
                                removeBtn.onclick = function() {
                                    tags.delete(text);
                                    tag.remove();
                                    updateHiddenInput();
                                };
                                
                                tag.appendChild(tagText);
                                tag.appendChild(removeBtn);
                                return tag;
                            }

                            function updateHiddenInput() {
                                hiddenInput.value = Array.from(tags).join(',');
                            }

                            tagInput.addEventListener('keydown', function(e) {
                                if (e.key === 'Enter' || e.key === ',') {
                                    e.preventDefault();
                                    const text = this.value.trim();
                                    if (text && !tags.has(text)) {
                                        tags.add(text);
                                        tagsContainer.insertBefore(createTag(text), this);
                                        updateHiddenInput();
                                        this.value = '';
                                    }
                                }
                            });

                            tagsContainer.addEventListener('click', function() {
                                tagInput.focus();
                            });
                        });
                    </script>
                </div>

                <!-- Priority Selection -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-300">Priority</label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4" id="priorityContainer">
                        <label class="relative flex cursor-pointer items-center justify-center rounded-xl border border-gray-700/50 bg-gray-800/50 p-4 hover:border-green-500/20 hover:shadow-lg hover:shadow-green-500/10 transition-all duration-200">
                            <input type="radio" name="priority" value="low" class="absolute h-0 w-0 opacity-0" required>
                            <div class="flex items-center space-x-3">
                                <div class="h-3 w-3 rounded-full bg-gradient-to-r from-green-400 to-green-500"></div>
                                <span class="text-gray-300">Low</span>
                            </div>
                        </label>
                        <label class="relative flex cursor-pointer items-center justify-center rounded-xl border border-gray-700/50 bg-gray-800/50 p-4 hover:border-yellow-500/20 hover:shadow-lg hover:shadow-yellow-500/10 transition-all duration-200">
                            <input type="radio" name="priority" value="medium" class="absolute h-0 w-0 opacity-0">
                            <div class="flex items-center space-x-3">
                                <div class="h-3 w-3 rounded-full bg-gradient-to-r from-yellow-400 to-yellow-500"></div>
                                <span class="text-gray-300">Medium</span>
                            </div>
                        </label>
                        <label class="relative flex cursor-pointer items-center justify-center rounded-xl border border-gray-700/50 bg-gray-800/50 p-4 hover:border-red-500/20 hover:shadow-lg hover:shadow-red-500/10 transition-all duration-200">
                            <input type="radio" name="priority" value="high" class="absolute h-0 w-0 opacity-0">
                            <div class="flex items-center space-x-3">
                                <div class="h-3 w-3 rounded-full bg-gradient-to-r from-red-400 to-red-500"></div>
                                <span class="text-gray-300">High</span>
                            </div>
                        </label>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Function to handle selection styling
                        function handleSelection(container, colorClass) {
                            const labels = container.querySelectorAll('label');
                            const inputs = container.querySelectorAll('input[type="radio"]');
                            
                            inputs.forEach((input, index) => {
                                input.addEventListener('change', () => {
                                    // Remove selection styling from all labels
                                    labels.forEach(label => {
                                        label.classList.remove('ring-2');
                                        label.classList.remove(colorClass);
                                    });
                                    
                                    // Add selection styling to selected label
                                    if (input.checked) {
                                        labels[index].classList.add('ring-2');
                                        labels[index].classList.add(colorClass);
                                    }
                                });
                            });
                        }

                        // Initialize selection handlers
                        handleSelection(document.getElementById('categoryContainer'), 'ring-violet-500/50');
                        handleSelection(document.getElementById('priorityContainer'), 'ring-violet-500/50');
                    });
                </script>

                <!-- Submit Button -->
                <div class="flex justify-end gap-4">
                    <a href="/" class="px-6 py-2 bg-gray-700/50 text-gray-300 rounded-lg hover:bg-gray-700/70 transition-all duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-violet-600 text-white px-6 py-2 rounded-lg hover:opacity-90 transition-all duration-200 shadow-lg hover:shadow-violet-500/20">
                        Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
HTML;

require_once(__DIR__ . '/layouts/base.php');