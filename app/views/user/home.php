<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>eClinic Home</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<body class="bg-white font-sans">

  <div class="flex">
    <!-- Sidebar -->
    <aside class="w-80 bg-sky-400 min-h-screen fixed flex flex-col justify-between px-6 py-8 border-r-4 border-indigo-200">
      <div>
        <div class="flex justify-center mb-6">
          <img src="<?= base_url() ?>/public/pic.img/logo_with__text-removebg-preview.png" alt="eClinic Logo" class="h-40 w-40 object-contain">
        </div>
        <nav class="space-y-6">
          <a href="<?= site_url('user/home') ?>" class="text-white font-semibold text-lg block rounded transition-colors hover:bg-white/20">Home</a>
          <a href="<?= site_url('user/profile') ?>" class="text-white font-semibold text-lg block rounded transition-colors hover:bg-white/20">Profile Management</a>
          <a href="<?= site_url('user/appointment') ?>" class="text-white font-semibold text-lg block rounded transition-colors hover:bg-white/20">Appointment</a>
          <a href="<?= site_url('user/history') ?>" class="text-white font-semibold text-lg block rounded transition-colors hover:bg-white/20">History</a>
        </nav>
      </div>

      <form action="<?= site_url('auth/logout') ?>" method="GET" class="mt-6">
        <button class="bg-white text-sky-400 font-bold text-lg py-2 px-4 rounded-full w-full hover:bg-sky-50">Log Out</button>
      </form>
    </aside>

    <!-- Main Content -->
    <main class="ml-80 flex-1 p-10">

<!-- ✅ SESSION ALERTS -->
<?php if (!empty($_SESSION['flash_success'])): ?>
  <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 text-center shadow-md">
    <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
  </div>
<?php elseif (!empty($_SESSION['flash_error'])): ?>
  <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 text-center shadow-md">
    <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
  </div>
<?php endif; ?>


      <div class="flex flex-col items-center mt-24">
        <h1 class="text-sky-400 text-4xl font-bold mb-4 text-center">Your Health, Our Priority</h1>
        <p class="text-black text-lg text-center mb-8">Access quality healthcare services from the comfort of your home.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full">
          <div class="bg-sky-400 text-white rounded-lg p-6 shadow-lg hover:bg-sky-500 transition">
            <h3 class="text-xl font-semibold mb-2">Book Appointments</h3>
            <p>Schedule consultations or check-ups easily with healthcare professionals.</p>
          </div>
          <div class="bg-sky-400 text-white rounded-lg p-6 shadow-lg hover:bg-sky-500 transition">
            <h3 class="text-xl font-semibold mb-2">Manage Your Profile</h3>
            <p>Keep your health records up to date and accessible anytime.</p>
          </div>
          <div class="bg-sky-400 text-white rounded-lg p-6 shadow-lg hover:bg-sky-500 transition">
            <h3 class="text-xl font-semibold mb-2">View History</h3>
            <p>Review past appointments and track your health journey.</p>
          </div>
        </div>

        <a href="<?= site_url('user/appointment') ?>" class="mt-10 bg-sky-400 text-white font-bold text-lg py-3 px-6 rounded-full hover:bg-sky-500 transition">Book an Appointment</a>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mt-10">
          <div class="bg-white border-4 border-sky-400 rounded-lg p-3 shadow-md hover:scale-105 hover:shadow-lg hover:border-blue-600 transition-transform">
            <img src="<?= base_url() ?>\public\pic.img\6105020446618535243.jpg" alt="Doctor Consultation" class="w-60 h-60 object-cover rounded-md mx-auto" />
          </div>
          <div class="bg-white border-4 border-sky-400 rounded-lg p-3 shadow-md hover:scale-105 hover:shadow-lg hover:border-blue-600 transition-transform">
            <img src="<?= base_url() ?>\public\pic.img\6105020446618535241.jpg" alt="Telehealth Service" class="w-60 h-60 object-cover rounded-md mx-auto" />
          </div>
          <div class="bg-white border-4 border-sky-400 rounded-lg p-3 shadow-md hover:scale-105 hover:shadow-lg hover:border-blue-600 transition-transform">
            <img src="<?= base_url() ?>\public\pic.img\6105020446618535242.jpg" alt="Clinic Interior" class="w-60 h-60 object-cover rounded-md mx-auto" />
          </div>
        </div>
      </div>

      <!-- ABOUT -->
      <section class="mt-24 bg-sky-50 py-16 px-8 rounded-lg shadow-inner text-center">
        <h2 class="text-3xl font-bold text-sky-500 mb-6">About Us</h2>
        <p class="text-gray-700 text-lg max-w-4xl mx-auto leading-relaxed">
          eClinic is a community-based digital healthcare platform designed to bring medical services closer to every household.
          Our goal is to simplify healthcare management, making it accessible, efficient, and secure — anytime, anywhere.
        </p>
      </section>

      <!-- CONTACT -->
      <section class="mt-16 bg-white py-16 px-8 rounded-lg shadow-md border border-sky-100">
        <h2 class="text-3xl font-bold text-sky-500 text-center mb-10">Contact Us</h2>

        <div class="max-w-3xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-center mb-12">
          <div class="flex flex-col items-center">
            <div class="bg-sky-100 text-sky-500 rounded-full p-4 mb-3">📞</div>
            <h3 class="font-semibold text-lg text-gray-800">Telephone</h3>
            <p class="text-gray-600">(042) 123-4567</p>
          </div>
          <div class="flex flex-col items-center">
            <div class="bg-sky-100 text-sky-500 rounded-full p-4 mb-3">📧</div>
            <h3 class="font-semibold text-lg text-gray-800">Email</h3>
            <p class="text-gray-600">eclinic.naujan@gmail.com</p>
          </div>
          <div class="flex flex-col items-center">
            <div class="bg-sky-100 text-sky-500 rounded-full p-4 mb-3">📍</div>
            <h3 class="font-semibold text-lg text-gray-800">Location</h3>
            <p class="text-gray-600">Naujan, Oriental Mindoro</p>
          </div>
        </div>

        <!-- Contact Form -->
        <form method="POST" action="<?= site_url('user/send_message') ?>" class="max-w-3xl mx-auto space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <input type="text" name="name" placeholder="Your Name" required class="border border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-sky-400">
            <input type="email" name="email" placeholder="Your Email" required class="border border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-sky-400">
          </div>
          <textarea name="message" placeholder="Your Message" rows="5" required class="border border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-sky-400"></textarea>
          <div class="text-center">
            <button type="submit" class="bg-sky-400 text-white px-8 py-3 rounded-full font-semibold hover:bg-sky-500 transition">
              Send Message
            </button>
          </div>
        </form>
      </section>

      <!-- MAP -->
      <section class="mt-16 mb-10 bg-sky-50 py-10 px-8 rounded-lg shadow-inner">
        <h2 class="text-3xl font-bold text-sky-500 text-center mb-6">Find Us Here</h2>
        <div id="map" class="w-full h-96 rounded-lg shadow-md border-4 border-sky-400"></div>
      </section>

      <script>
        const map = L.map('map').setView([13.2746, 121.2354], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
        L.marker([13.2746, 121.2354])
          .addTo(map)
          .bindPopup('<b>eClinic - Naujan</b><br>Visit us here!')
          .openPopup();
      </script>
    </main>
  </div>
</body>
</html>
