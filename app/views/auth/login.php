<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - eClinic</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="flex w-full max-w-4xl bg-white rounded-lg shadow-xl overflow-hidden">
        <!-- Left Section with Logo -->
        <div class="w-1/2 bg-gradient-to-b from-sky-100 to-white flex flex-col items-center justify-center p-8">
            <img src="<?= base_url() ?>/public/pic.img/logo_with__text-removebg-preview.png" 
                 alt="eClinic Logo" class="w-48 h-48 object-contain mb-4">
            <p class="text-gray-600 font-medium">Your trusted Barangay health partner</p>
        </div>

        <!-- Right Section - Login Form -->
        <div class="w-1/2 p-10 flex flex-col justify-center">
            <h2 class="text-3xl font-bold mb-6 text-center text-gray-800 tracking-wide">LOG IN</h2>

            <?php if (!empty($message)) : ?>
                <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= site_url('auth/login') ?>" class="space-y-5">
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm
                                  focus:outline-none focus:ring-sky-500 focus:border-sky-500"
                           placeholder="Enter your email">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm
                                  focus:outline-none focus:ring-sky-500 focus:border-sky-500"
                           placeholder="Enter your password">
                </div>

                <button type="submit"
                        class="w-full bg-sky-500 text-white py-2 px-4 rounded-md font-semibold
                               hover:bg-sky-600 transition">
                    LOG IN
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-gray-600">
                Don’t have an account?
                <a href="<?= site_url('auth/register') ?>" class="text-sky-600 font-semibold hover:underline">Create Account</a>
            </p>
        </div>
    </div>

</body>
</html>
