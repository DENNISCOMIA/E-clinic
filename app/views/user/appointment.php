<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>eClinic Appointment</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
<div class="flex">
    <!-- Sidebar -->
    <aside class="w-80 bg-sky-400 min-h-screen fixed flex flex-col justify-between px-6 py-8 border-r-4 border-indigo-200">
      <div>
        <!-- Logo -->
        <div class="flex justify-center mb-6">
          <img src="<?= base_url() ?>/public/pic.img/logo_with__text-removebg-preview.png" alt="eClinic Logo" class="h-40 w-40 object-contain">
        </div>

        <!-- Navigation -->
        <nav class="space-y-6">
          <a href="<?= site_url('user/home') ?>" class="text-white font-semibold text-lg block rounded hover:bg-white/20">Home</a>
          <a href="<?= site_url('user/profile') ?>" class="text-white font-semibold text-lg block rounded hover:bg-white/20">Profile Management</a>
          <a href="<?= site_url('user/appointment') ?>" class="text-white font-semibold text-lg block rounded hover:bg-white/20">Appointment</a>
          <a href="<?= site_url('user/history') ?>" class="text-white font-semibold text-lg block rounded hover:bg-white/20">History</a>
        </nav>
      </div>

      <!-- Logout Button -->
      <form action="<?= site_url('auth/logout') ?>" method="GET" class="mt-6">
    <button class="bg-white text-sky-400 font-bold text-lg py-2 px-4 rounded-full w-full hover:bg-sky-50">
        Log Out
    </button>
</form>

    </aside>

    <!-- Main Content -->
    <main class="ml-80 flex-1 p-8">
      <h1 class="text-sky-400 text-3xl font-bold text-center mb-8">Book an Appointment</h1>

      <?php if (!empty($error_message)): ?>
          <div class="max-w-4xl mx-auto mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
              <?= htmlspecialchars($error_message) ?>
          </div>
      <?php elseif (!empty($success_message)): ?>
          <div class="max-w-4xl mx-auto mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
              <?= htmlspecialchars($success_message) ?>
          </div>
      <?php endif; ?>

      <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <form method="POST" class="space-y-6">
          <div>
            <label for="fullname" class="block text-gray-600 font-medium mb-2">Full Name</label>
            <input type="text" name="fullname" id="fullname"
                   value="<?= htmlspecialchars($profile['full_name'] ?? '') ?>"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-400" required>
          </div>

          <div class="flex flex-wrap gap-6">
            <div class="w-full sm:w-1/2 lg:w-5/12">
              <label for="age" class="block text-gray-600 font-medium mb-2">Age</label>
              <input type="number" name="age" id="age"
                     value="<?= htmlspecialchars($profile['age'] ?? '') ?>"
                     class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-400" required>
            </div>

            <div class="w-full sm:w-1/2 lg:w-5/12">
              <label for="contact" class="block text-gray-600 font-medium mb-2">Contact Number</label>
              <input type="tel" name="contact" id="contact"
                     value="<?= htmlspecialchars($profile['phone'] ?? '') ?>"
                     maxlength="11" pattern="09\d{9}" title="Enter an 11-digit contact number starting with 09."
                     class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-400" required>
            </div>
          </div>

       <div>
  <label for="appointment_date" class="block text-gray-600 font-medium mb-2">Appointment Date</label>
  <input type="date" name="appointment_date" id="appointment_date"
         value="<?= htmlspecialchars($_POST['appointment_date'] ?? '') ?>"
         min="<?= date('Y-m-d') ?>"
         class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-400" required>
  <p id="dateError" class="text-red-500 text-sm mt-1 hidden">Invalid date! You cannot select a past date.</p>
</div>

<script>
  const dateInput = document.getElementById('appointment_date');
  const dateError = document.getElementById('dateError');

  // Set min attribute dynamically to today
  const today = new Date();
  today.setHours(0,0,0,0); // Remove time portion
  const todayStr = today.toISOString().split('T')[0];
  dateInput.setAttribute('min', todayStr);

  // Validate on input
  dateInput.addEventListener('input', () => {
    const selectedDate = new Date(dateInput.value);
    selectedDate.setHours(0,0,0,0); // Remove time portion for comparison

    if (selectedDate < today) {
      dateError.classList.remove('hidden'); // Show error
    } else {
      dateError.classList.add('hidden'); // Hide error
    }
  });

  // Prevent form submission if date is invalid
  const form = dateInput.closest('form');
  form.addEventListener('submit', (e) => {
    const selectedDate = new Date(dateInput.value);
    selectedDate.setHours(0,0,0,0);

    if (selectedDate < today || !dateInput.value) {
      e.preventDefault();
      dateError.classList.remove('hidden');
    }
  });
</script>


          <div>
            <label for="purpose" class="block text-gray-600 font-medium mb-2">Purpose</label>
            <textarea name="purpose" id="purpose" rows="5" placeholder="e.g., Consultation, Check-up"
                      class="w-full px-4 py-2 border border-gray-300 rounded-md resize-none focus:ring-2 focus:ring-sky-400" required><?= htmlspecialchars($_POST['purpose'] ?? '') ?></textarea>
          </div>

          <div class="flex justify-end">
            <button type="submit" class="bg-sky-400 text-white font-semibold py-2 px-6 rounded-lg hover:bg-sky-500">
              <?= !empty($_POST) ? 'Update Appointment' : 'Book Appointment' ?>
            </button>
          </div>
        </form>
      </div>
    </main>
</div>
</body>
</html>
