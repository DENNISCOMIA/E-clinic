<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>eClinic Admin - Appointment Management</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
<div class="flex">
    <!-- Sidebar -->
  <aside class="w-80 bg-sky-400 min-h-screen fixed flex flex-col justify-between px-6 py-8 border-r-4 border-indigo-200">
    <div>
      <div class="flex justify-center mb-6">
        <img src="<?= base_url() ?>/public/pic.img/logo_with__text-removebg-preview.png" alt="eClinic Logo" class="h-40 w-40 object-contain">
      </div>
      <nav class="space-y-6">
        <a href="<?= site_url('admin/dashboard') ?>" class="text-white font-semibold text-lg block rounded transition-colors hover:bg-white/20">Admin Dashboard</a>
        <a href="<?= site_url('admin/appointments') ?>" class="text-white font-semibold text-lg block rounded transition-colors hover:bg-white/20">Appointment Management</a>
        <a href="<?= site_url('admin/findings') ?>" class="text-white font-semibold text-lg block rounded transition-colors hover:bg-white/20">Findings & Prescription</a>
        <a href="<?= site_url('admin/records') ?>" class="text-white font-semibold text-lg block rounded transition-colors hover:bg-white/20">Patients Records</a>
      </nav>
    </div>
    <div class="mb-2">
      <a href="<?= site_url('auth/logout') ?>" class="bg-white text-sky-400 text-lg font-bold py-2 px-20 rounded-full w-full hover:bg-sky-50">
        Log Out
      </a>
    </div>
  </aside>

    <!-- Main Content -->
    <main class="ml-80 flex-1 p-10">
      <h1 class="text-sky-400 text-3xl font-bold text-center mb-8">Appointment Management</h1>

      <?php if (!empty($success_message)): ?>
        <div class="max-w-4xl mx-auto mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
          <?= htmlspecialchars($success_message) ?>
        </div>
      <?php elseif (!empty($error_message)): ?>
        <div class="max-w-4xl mx-auto mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
          <?= htmlspecialchars($error_message) ?>
        </div>
      <?php endif; ?>

      <div class="max-w-6xl mx-auto bg-white rounded-lg shadow-lg p-8 overflow-x-auto">
        <table class="w-full text-center border-collapse">
          <thead class="bg-sky-400 text-white">
            <tr>
              <th class="py-3 px-6">Full Name</th>
              <th class="py-3 px-6">Age</th>
              <th class="py-3 px-6">Contact</th>
              <th class="py-3 px-6">Appointment Date</th>
              <th class="py-3 px-6">Purpose</th>
              <th class="py-3 px-6">Action</th>
            </tr>
          </thead>
          <tbody class="text-gray-700">
            <?php foreach ($appointments as $appointment): ?>
              <tr class="border-b hover:bg-gray-50">
                <td class="py-3 px-6"><?= htmlspecialchars($appointment['fullname']) ?></td>
                <td class="py-3 px-6"><?= htmlspecialchars($appointment['age']) ?></td>
                <td class="py-3 px-6"><?= htmlspecialchars($appointment['contact']) ?></td>
                <td class="py-3 px-6"><?= htmlspecialchars($appointment['appointment_date']) ?></td>
                <td class="py-3 px-6"><?= htmlspecialchars($appointment['purpose']) ?></td>
                <td class="py-3 px-6 flex justify-center gap-2">
                  <a href="<?= site_url('admin/manageAppointments?accept_id=' . $appointment['id']) ?>" 
                     class="bg-green-500 text-white px-4 py-1 rounded hover:bg-green-600 transition">
                    Accept
                  </a>
                  <a href="<?= site_url('admin/manageAppointments?cancel_id=' . $appointment['id']) ?>" 
                     class="bg-red-500 text-white px-4 py-1 rounded hover:bg-red-600 transition">
                    Cancel
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </main>
</div>
</body>
</html>
