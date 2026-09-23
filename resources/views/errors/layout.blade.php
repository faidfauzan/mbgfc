<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - MBG FC</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1f2937">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #111827; /* Tailwind gray-900 */
            color: #f3f4f6; /* Tailwind gray-100 */
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background-image: radial-gradient(circle at top right, #1f2937, #111827);
        }
        .error-container {
            text-align: center;
            padding: 2rem;
            background: rgba(31, 41, 55, 0.8);
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(75, 85, 99, 0.5);
            max-width: 500px;
            width: 90%;
            animation: fadeIn 0.5s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .error-code {
            font-size: 5rem;
            font-weight: 800;
            background: -webkit-linear-gradient(45deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }
        .error-message {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        .error-details {
            color: #9ca3af;
            margin-bottom: 2rem;
        }
        .btn-back {
            display: inline-block;
            background: linear-gradient(to right, #3b82f6, #2563eb);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.3);
        }
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(37, 99, 235, 0.4);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">@yield('code')</div>
        <div class="error-message">@yield('message')</div>
        <div class="error-details">@yield('details')</div>
        <a href="{{ url('/') }}" class="btn-back">Kembali ke Beranda</a>
    </div>
</body>
</html>
