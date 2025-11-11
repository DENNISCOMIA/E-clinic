<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>eClinic Admin - Findings & Prescription</title>

  <!-- ✅ Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- ✅ TomSelect CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans">
<div class="flex">

  <!-- ✅ Sidebar -->
  <aside class="w-80 bg-sky-400 min-h-screen fixed flex flex-col justify-between px-6 py-8 border-r-4 border-indigo-200">
    <div>
      <div class="flex justify-center mb-6">
        <img src="<?= base_url() ?>/public/pic.img/logo_with__text-removebg-preview.png" alt="eClinic Logo" class="h-40 w-40 object-contain">
      </div>
      <nav class="space-y-6">
        <a href="<?= site_url('admin/dashboard') ?>" class="block text-white font-semibold text-lg rounded hover:bg-white/20 transition">Admin Dashboard</a>
        <a href="<?= site_url('admin/appointments') ?>" class="block text-white font-semibold text-lg rounded hover:bg-white/20 transition">Appointment Management</a>
        <a href="<?= site_url('admin/findings') ?>" class="block text-white font-semibold text-lg rounded bg-white/25">Findings & Prescription</a>
        <a href="<?= site_url('admin/records') ?>" class="block text-white font-semibold text-lg rounded hover:bg-white/20 transition">Patients Records</a>
      </nav>
    </div>
    <div class="mb-2">
      <a href="<?= site_url('auth/logout') ?>" class="block bg-white text-sky-400 text-lg font-bold py-2 px-4 rounded-full text-center hover:bg-sky-50 transition">
        Log Out
      </a>
    </div>
  </aside>

  <!-- ✅ Main Content -->
  <main class="ml-80 flex-1 p-10">
    <h1 class="text-sky-400 text-3xl font-bold text-center mb-8">Add Findings & Prescription</h1>

    <!-- ✅ Messages -->
    <?php if (!empty($successMessage)): ?>
      <div class="max-w-4xl mx-auto mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow">
        <?= htmlspecialchars($successMessage) ?>
      </div>
    <?php elseif (!empty($errorMessage)): ?>
      <div class="max-w-4xl mx-auto mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow">
        <?= htmlspecialchars($errorMessage) ?>
      </div>
    <?php endif; ?>

    <!-- ✅ Findings & Prescription Form -->
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">
      <form method="POST" class="space-y-6">

        <!-- Select Appointment -->
        <div>
          <label for="appointment_id" class="block text-lg font-semibold text-gray-700 mb-2">
            Select Appointment
          </label>
          <select name="appointment_id" id="appointment_id" required
                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-400">
            <option value="">-- Search and select an accepted appointment --</option>
            <?php if (!empty($acceptedAppointments)): ?>
              <?php foreach ($acceptedAppointments as $appt): ?>
                <option value="<?= htmlspecialchars($appt['id']) ?>">
                  [ID: <?= htmlspecialchars($appt['id']) ?>]
                  <?= htmlspecialchars($appt['fullname']) ?> -
                  <?= htmlspecialchars($appt['appointment_date']) ?> -
                  <?= htmlspecialchars($appt['purpose']) ?>
                </option>
              <?php endforeach; ?>
            <?php else: ?>
              <option disabled>No accepted appointments available</option>
            <?php endif; ?>
          </select>
        </div>

        <!-- Findings -->
        <div>
          <label for="findings" class="block text-lg font-semibold text-gray-700 mb-2">Findings</label>
          <textarea id="findings" name="findings" rows="4" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-400"><?= isset($findings) ? htmlspecialchars($findings) : '' ?></textarea>
        </div>

        <!-- Prescription -->
        <div>
          <label for="prescription" class="block text-lg font-semibold text-gray-700 mb-2">Prescription</label>
          <textarea id="prescription" name="prescription" rows="4" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-400"><?= isset($prescription) ? htmlspecialchars($prescription) : '' ?></textarea>
        </div>

        <!-- Submit Button -->
        <div class="text-right">
          <button type="submit"
                  class="bg-sky-400 text-white font-semibold py-2 px-6 rounded-lg hover:bg-sky-500 transition">
            Submit
          </button>
        </div>

      </form>
    </div>
  </main>
</div>

<!-- ✅ TomSelect JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
  new TomSelect('#appointment_id', {
    create: false,
    sortField: { field: "text", direction: "asc" },
    placeholder: "-- Search and select an accepted appointment --",
    maxOptions: 1000,
    allowEmptyOption: true,
    persist: true,
    preload: true
  });
</script>

</body>
</html>
