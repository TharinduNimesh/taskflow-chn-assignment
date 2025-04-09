<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            900: '#1e1b4b',
                            800: '#2e1065',
                            700: '#4c1d95',
                            600: '#5b21b6',
                        },
                    }
                }
            }
        }
    </script>
    <title><?php echo $pageTitle ?? 'Note'; ?></title>
    <?php if (isset($includeFilterJS) && $includeFilterJS): ?>
    <script src="/assets/js/filter.js" defer></script>
    <?php endif; ?>
    <script src="https://cdn.tiny.cloud/1/95xr7v31l63ip4euwlcfvn8fszp3duwbqsm1wdrt8d8i0p88/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body class="dark:bg-gray-900 min-h-screen flex flex-col justify-between">
    <?php include_once(__DIR__ . '/header.php'); ?>
    
    <main class="flex-grow py-10">
        <?php echo $content ?? ''; ?>
    </main>

    <?php include_once(__DIR__ . '/footer.php'); ?>
</body>
</html>