<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Austrian ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Austrian ERP Login</h2>
            </div>
            <form class="mt-8 space-y-6" action="/login" method="POST">
                <div>
                    <label>E-Mail</label>
                    <input type="email" name="email" required class="mt-1 block w-full rounded-md border-gray-300">
                </div>
                <div>
                    <label>Passwort</label>
                    <input type="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                    Anmelden
                </button>
            </form>
        </div>
    </div>
</body>
</html>
