<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Login | TuneIn</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        body {
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
        }
        .music-bar {
            animation: bounce 1.2s ease-in-out infinite alternate;
        }
        @keyframes bounce {
            from { height: 4px; }
            to { height: 20px; }
        }
    </style>
</head>
<body class="flex items-center justify-center h-screen overflow-hidden">

<div class="absolute inset-0 opacity-20 pointer-events-none">
    <i class="fas fa-music text-white text-9xl absolute top-10 left-10 rotate-12"></i>
    <i class="fas fa-guitar text-white text-9xl absolute bottom-10 right-10 -rotate-12"></i>
</div>

<div class="glass-effect p-10 rounded-3xl shadow-2xl w-full max-w-md relative z-10 border border-white/20">
    
    <div class="text-center mb-8">
        <div class="flex justify-center items-end gap-1 mb-4">
            <div class="w-1.5 bg-pink-500 music-bar" style="animation-delay: 0.1s"></div>
            <div class="w-1.5 bg-purple-500 music-bar" style="animation-delay: 0.3s"></div>
            <div class="w-1.5 bg-blue-500 music-bar" style="animation-delay: 0.2s"></div>
            <div class="w-1.5 bg-green-500 music-bar" style="animation-delay: 0.4s"></div>
        </div>
        <h2 class="text-3xl font-extrabold text-white tracking-tight">Selamat datang di kursus music!</h2>
        <p class="text-blue-200 text-sm mt-2">Login untuk memulai semuanya!!</p>
    </div>

    @if(session('error'))
        <div class="mb-6 bg-red-500/20 border border-red-500/50 text-red-200 px-4 py-2 rounded-lg text-sm flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="/login">
        @csrf

        <div class="mb-6 group">
            <label class="block mb-2 text-sm font-medium text-gray-300">Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i class="fas fa-envelope"></i>
                </span>
                <input type="email" name="email" 
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-10 py-3 text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder-gray-500" 
                    placeholder="name@studio.com" required>
            </div>
        </div>

        <div class="mb-8 group">
            <label class="block mb-2 text-sm font-medium text-gray-300">Password</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i class="fas fa-lock"></i>
                </span>
                <input type="password" name="password" 
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-10 py-3 text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder-gray-500" 
                    placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit" 
            class="w-full bg-gradient-to-r from-purple-600 to-blue-500 text-white font-bold py-3 rounded-xl hover:from-purple-500 hover:to-blue-400 transform hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-purple-500/25 flex items-center justify-center gap-2">
            <i class="fas fa-play"></i>
            Log In
        </button>
    </form>

   
</div>

</body>
</html>