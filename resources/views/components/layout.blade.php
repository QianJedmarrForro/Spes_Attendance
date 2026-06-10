<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'QR Attendance System' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pastelBlue: {
                            50: '#f0f7ff',
                            100: '#e6f0ff',
                            200: '#cfe3ff',
                            300: '#a8ccff',
                            400: '#7fb2ff',
                            500: '#5b96ff',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-pastelBlue-50 min-h-screen flex">

    <div class="flex-1 flex flex-col">
        
        <main class="p-6 flex-1 flex justify-center items-start">
            {{ $slot }}
        </main>
    </div>

</body>
</html>
