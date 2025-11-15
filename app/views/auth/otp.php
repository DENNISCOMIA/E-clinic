<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>OTP Verification</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white w-full max-w-sm p-6 rounded-xl shadow-lg">
        <h2 class="text-2xl font-semibold text-center mb-2">Enter OTP</h2>
        <p class="text-center text-gray-500 mb-4">
            We sent a 6-digit code to <b><?= $email ?></b>
        </p>

        <form method="POST" action="/auth/verify_otp" class="space-y-4">
            <input type="hidden" name="email" value="<?= $email ?>">

            <input 
                type="text" 
                name="otp" 
                maxlength="6"
                placeholder="Enter OTP"
                required
                class="w-full px-4 py-3 border rounded-lg text-center text-xl tracking-widest focus:ring-2 focus:ring-green-500 focus:outline-none"
            />

            <button 
                type="submit" 
                class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition">
                Verify OTP
            </button>
        </form>
    </div>

</body>
</html>
