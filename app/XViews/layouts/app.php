<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Austrian ERP' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <main class="min-h-screen flex items-center justify-center">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <?= $content ?>
        </div>
    </main>
</body>
</html>
