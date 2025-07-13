<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Prescription System - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CDN (atau ganti dengan asset jika sudah install) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #6a85e6 0%, #b9b6e5 100%);
            min-height: 100vh;
        }
        .card-3d {
            transition: transform 0.4s cubic-bezier(.25,.8,.25,1), box-shadow 0.4s;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        .card-3d:hover {
            transform: rotateY(8deg) scale(1.03);
            box-shadow: 0 16px 48px 0 rgba(31, 38, 135, 0.45);
        }
        .fade-in {
            animation: fadeIn 0.7s;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px);}
            to { opacity: 1; transform: translateY(0);}
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="flex flex-col md:flex-row gap-8 fade-in">
        <!-- Login Card -->
        <div class="card-3d bg-white rounded-2xl shadow-lg p-8 w-96 flex flex-col items-center" id="loginCard">
            <div class="bg-blue-900 rounded-t-xl w-full flex flex-col items-center py-6 mb-6">
                <div class="text-4xl mb-2">
                    <svg width="40" height="40" fill="none" viewBox="0 0 24 24"><path fill="#fff" d="M12 2a7 7 0 0 1 7 7c0 2.5-1.5 4.5-3.5 6.5l-1.5 1.5-1.5-1.5C6.5 13.5 5 11.5 5 9a7 7 0 0 1 7-7Z"/><circle cx="12" cy="9" r="3" fill="#6a85e6"/></svg>
                </div>
                <h2 class="text-white text-2xl font-bold">E-Prescription System</h2>
                <p class="text-blue-200 text-sm">Silakan login untuk melanjutkan</p>
            </div>
            <form method="POST" action="{{ route('login') }}" class="w-full">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 mb-1" for="email">Email</label>
                    <input id="email" type="email" name="email" required autofocus
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-yellow-100"
                        placeholder="Email">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-1" for="password">Password</label>
                    <input id="password" type="password" name="password" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-yellow-100"
                        placeholder="Password">
                </div>
                <button type="submit"
                    class="w-full py-2 mt-2 rounded-lg bg-gradient-to-r from-blue-500 to-blue-700 text-white font-semibold shadow-md hover:scale-105 transition-transform duration-200">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            <div class="mt-4 text-sm text-gray-600">
                Belum punya akun?
                <button onclick="toggleForm()" class="text-blue-600 hover:underline">Daftar disini</button>
            </div>
        </div>
        <!-- Register Card (hidden by default) -->
        <div id="registerCard" class="card-3d bg-white rounded-2xl shadow-lg p-8 w-96 flex flex-col items-center hidden">
            <div class="bg-green-700 rounded-t-xl w-full flex flex-col items-center py-6 mb-6">
                <div class="text-4xl mb-2">
                    <svg width="40" height="40" fill="none" viewBox="0 0 24 24"><path fill="#fff" d="M12 2a7 7 0 0 1 7 7c0 2.5-1.5 4.5-3.5 6.5l-1.5 1.5-1.5-1.5C6.5 13.5 5 11.5 5 9a7 7 0 0 1 7-7Z"/><circle cx="12" cy="9" r="3" fill="#34d399"/></svg>
                </div>
                <h2 class="text-white text-2xl font-bold">Registrasi Akun</h2>
                <p class="text-green-200 text-sm">Daftar untuk membuat akun baru</p>
            </div>
            <form method="POST" action="{{ route('register') }}" class="w-full">
                @csrf
                <div class="mb-3">
                    <label class="block text-gray-700 mb-1" for="name">Nama</label>
                    <input id="name" type="text" name="name" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 bg-green-50"
                        placeholder="Nama Lengkap">
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 mb-1" for="email">Email</label>
                    <input id="email" type="email" name="email" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 bg-green-50"
                        placeholder="Email">
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 mb-1" for="password">Password</label>
                    <input id="password" type="password" name="password" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 bg-green-50"
                        placeholder="Password">
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 mb-1" for="password_confirmation">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 bg-green-50"
                        placeholder="Konfirmasi Password">
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 mb-1" for="role">Role</label>
                    <select id="role" name="role" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-400 bg-green-50">
                        <option value="pasien" selected>Pasien</option>
                        <option value="dokter">Dokter</option>
                        <option value="apoteker">Apoteker</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit"
                    class="w-full py-2 mt-2 rounded-lg bg-gradient-to-r from-green-500 to-green-700 text-white font-semibold shadow-md hover:scale-105 transition-transform duration-200">
                    <i class="fas fa-user-plus"></i> Daftar
                </button>
            </form>
            <div class="mt-4 text-sm text-gray-600">
                Sudah punya akun?
                <button onclick="toggleForm()" class="text-green-600 hover:underline">Login disini</button>
            </div>
        </div>
    </div>
    <script>
        function toggleForm() {
            const login = document.getElementById('loginCard');
            const register = document.getElementById('registerCard');
            login.classList.toggle('hidden');
            register.classList.toggle('hidden');
        }
    </script>
</body>
</html> 