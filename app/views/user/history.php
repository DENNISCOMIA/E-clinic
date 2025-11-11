<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>eClinic Appointment History</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
<div class="flex">
    <!-- Sidebar -->
    <aside class="w-80 bg-sky-400 min-h-screen fixed flex flex-col justify-between px-6 py-8 border-r-4 border-indigo-200">
      <div>
        <!-- Logo -->
        <div class="flex justify-center mb-6">
          <img src="<?= base_url() ?>/public/pic.img/logo_with__text-removebg-preview.png" alt="eClinic Logo" class="h-32 w-32 object-contain">
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
      <h1 class="text-sky-400 text-3xl font-bold text-center mb-8">Appointment History</h1>

<div class="max-w-6xl mx-auto bg-white rounded-lg shadow-lg p-6 overflow-x-auto">

  <form action="<?= site_url('user/delete_history') ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete selected records?');">
    <table class="w-full table-auto border-collapse">
      <thead>
        <tr class="bg-sky-400 text-white">
          <th class="px-4 py-3 text-left">
            <input type="checkbox" id="selectAll" class="w-4 h-4 cursor-pointer"> <!-- NEW -->
          </th>
          <th class="px-4 py-3 text-left">Appointment Date</th>
          <th class="px-4 py-3 text-left">Purpose</th>
          <th class="px-4 py-3 text-left">Status</th>
          <th class="px-4 py-3 text-left">Findings</th>
          <th class="px-4 py-3 text-left">Prescription</th>
        </tr>
      </thead>
      <tbody class="text-gray-700">
        <?php if (!empty($history) && is_array($history)): ?>
          <?php foreach ($history as $row): ?>
            <?php
              $appointment_id = $row['id'] ?? ''; // make sure your DB table has 'id'
              $appointment_date = $row['appointment_date'] ?? '';
              $purpose = $row['purpose'] ?? '';
              $status = strtolower(trim($row['status'] ?? 'pending'));
              $findings = $row['Findings'] ?? '';
              $prescription = $row['Prescription'] ?? '';

              if (!empty($findings) && !empty($prescription)) {
                  $status = 'completed';
              }

              $class = match($status) {
                  'accepted', 'completed' => 'text-green-600',
                  'pending' => 'text-yellow-500',
                  'cancelled' => 'text-red-500',
                  default => 'text-gray-500'
              };
            ?>
            <tr class="border-t border-gray-200 hover:bg-gray-50 transition">
              <td class="px-4 py-3">
                <input type="checkbox" name="delete_ids[]" value="<?= $appointment_id ?>" class="rowCheckbox w-4 h-4 cursor-pointer"> <!-- NEW -->
              </td>
              <td class="px-6 py-4"><?= !empty($appointment_date) ? date("F j, Y", strtotime($appointment_date)) : '-' ?></td>
              <td class="px-6 py-4"><?= htmlspecialchars($purpose) ?></td>
              <td class="px-6 py-4"><span class="<?= $class ?> font-medium"><?= htmlspecialchars(ucfirst($status)) ?></span></td>
              <td class="px-6 py-4"><?= !empty($findings) ? htmlspecialchars($findings) : '-' ?></td>
              <td class="px-6 py-4"><?= !empty($prescription) ? htmlspecialchars($prescription) : '-' ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr class="border-t border-gray-200">
            <td class="px-4 py-3 text-center" colspan="6">No appointment history available.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <div class="mt-6 flex justify-between items-center">
      <?php if (!empty($page)): ?>
        <div><?= $page ?></div>
      <?php endif; ?>

      <!-- NEW Delete Button -->
      <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg">
        Delete Selected
      </button>
    </div>
  </form>
</div>

<script>
  // ✅ Select/Deselect All
  document.getElementById('selectAll').addEventListener('click', function() {
    const checkboxes = document.querySelectorAll('.rowCheckbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
  });
</script>

      </main>
    </div>
</body>
</html>
