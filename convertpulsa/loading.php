<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading...</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f7fafc; /* bg-gray-100 */
        }
    </style>
</head>
<body>
    <div class="flex flex-col items-center">
        <div class="loader border-t-4 border-b-4 border-blue-500 rounded-full w-16 h-16 animate-spin"></div>
        <p class="text-xl font-semibold text-gray-700 mt-4">Processing your request...</p>
    </div>
</body>
</html>