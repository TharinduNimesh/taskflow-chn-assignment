<?php
session_start();
require_once(__DIR__ . '/models/User.php');

// Redirect if already logged in
if (isset($_SESSION['user'])) {
    header('Location: /');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $userModel = new User();
        $user = $userModel->authenticate($_POST['email'], $_POST['password']);
        
        if ($user) {
            $_SESSION['user'] = $user;
            header('Location: /');
            exit;
        } else {
            $error = "Invalid email or password";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - TaskFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-gray-800/90 backdrop-blur-sm border border-gray-700/50 p-8 rounded-2xl shadow-lg shadow-violet-500/10">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-white">Sign in to your account</h2>
                <?php if ($error): ?>
                    <div class="mt-3 p-3 bg-red-500/10 border border-red-500/50 rounded-lg">
                        <p class="text-red-500 text-center"><?php echo htmlspecialchars($error); ?></p>
                    </div>
                <?php endif; ?>
            </div>
            <form class="mt-8 space-y-6" action="" method="POST">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email" class="sr-only">Email address</label>
                        <input id="email" name="email" type="email" required 
                            class="appearance-none rounded-t-lg relative block w-full px-3 py-4 border border-gray-700 bg-gray-900/50 placeholder-gray-500 text-white focus:outline-none focus:ring-violet-500 focus:border-violet-500 focus:z-10"
                            placeholder="Email address">
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" required 
                            class="appearance-none rounded-b-lg relative block w-full px-3 py-4 border border-gray-700 bg-gray-900/50 placeholder-gray-500 text-white focus:outline-none focus:ring-violet-500 focus:border-violet-500 focus:z-10"
                            placeholder="Password">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" 
                            class="h-4 w-4 bg-gray-900 border-gray-700 rounded text-violet-600 focus:ring-violet-500">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-300">Remember me</label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-violet-400 hover:text-violet-500">Forgot your password?</a>
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-blue-500 to-violet-600 hover:from-blue-600 hover:to-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-all duration-200">
                        Sign in
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-400">
                        Don't have an account? 
                        <a href="/signup.php" class="font-medium text-violet-400 hover:text-violet-500">Sign up</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>