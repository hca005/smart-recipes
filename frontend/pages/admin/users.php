<?php
require_once '../../includes/bootstrap.php';
require_admin(); // Lệnh bảo vệ Admin của con

// Lấy danh sách người dùng từ Database
$users = [];
if ($conn) {
    try {
        $query = "SELECT id, username, COALESCE(display_name, username) AS display_name, email, role, created_at FROM users ORDER BY created_at DESC";
        $result = $conn->query($query);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
    } catch (Throwable $e) {
        $users = [];
    }
}
if (empty($users)) {
    $users = [
        ['id' => 1, 'username' => 'admin', 'display_name' => 'Admin Chef', 'email' => 'admin@food.com', 'role' => 'admin', 'created_at' => date('Y-m-d H:i:s')],
        ['id' => 2, 'username' => 'demo_user', 'display_name' => 'Demo User', 'email' => 'user@food.com', 'role' => 'user', 'created_at' => date('Y-m-d H:i:s')]
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Users – Food. Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Rammetto+One&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/smart-recipes/frontend/assets/css/base/variables.css">
<link rel="stylesheet" href="/smart-recipes/frontend/assets/css/base/reset.css">
<link rel="stylesheet" href="/smart-recipes/frontend/assets/css/pages/admin.css">
<link rel="stylesheet" href="/smart-recipes/frontend/assets/css/components/footer.css">
</head>
<body class="adm-body">
<div class="adm-layout">
    
    <?php include '../../includes/admin_sidebar.php'; ?>
    
    <div class="adm-main">
        <header class="adm-topbar">
            <div class="adm-topbar-left"><span>Dashboards</span><span class="adm-topbar-sep">/</span><span class="adm-tb-active">Users</span></div>
            <div class="adm-topbar-spacer"></div>
            <div class="adm-tb-search">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                <input type="text" id="searchInput" placeholder="Search users...">
            </div>
        </header>

        <div class="adm-content">
            <div class="adm-page-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 class="adm-page-title">Users Management</h1>
                    <span style="font-size:0.8125rem;color:#9CA3AF;">Manage community members and roles</span>
                </div>
                <span style="background: #e2e8f0; color: #1e293b; padding: 5px 15px; border-radius: 20px; font-size: 0.9rem; font-weight: 600;">
                    Total: <?= $result->num_rows ?> users
                </span>
            </div>

            <div style="background: white; border-radius: 15px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden; margin-top: 20px;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <tr>
                            <th style="padding: 15px 20px; color: #64748b; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">ID</th>
                            <th style="padding: 15px 20px; color: #64748b; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">User</th>
                            <th style="padding: 15px 20px; color: #64748b; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Email</th>
                            <th style="padding: 15px 20px; color: #64748b; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Role</th>
                            <th style="padding: 15px 20px; color: #64748b; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Joined</th>
                            <th style="padding: 15px 20px; color: #64748b; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($user = $result->fetch_assoc()): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 15px 20px; color: #64748b; font-size: 0.9rem;">#<?= $user['id'] ?></td>
                            <td style="padding: 15px 20px;">
                                <div style="font-weight: 700; color: #111827;"><?= htmlspecialchars($user['display_name']) ?></div>
                                <div style="font-size: 0.8rem; color: #9CA3AF;">@<?= htmlspecialchars($user['username']) ?></div>
                            </td>
                            <td style="padding: 15px 20px; color: #6b7280; font-size: 0.9rem;"><?= htmlspecialchars($user['email']) ?></td>
                            <td style="padding: 15px 20px;">
                                <?php if($user['role'] === 'admin'): ?>
                                    <span style="background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700;">ADMIN</span>
                                <?php else: ?>
                                    <span style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 600;">USER</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 15px 20px; color: #6b7280; font-size: 0.9rem;">
                                <?= date('Y-m-d', strtotime($user['created_at'])) ?>
                            </td>
                            <td style="padding: 15px 20px;">
                                <div style="display:flex;gap:0.4rem;">
                                    <button class="adm-btn adm-btn-outline" onclick="window.openEditModal(<?= $user['id'] ?>, '<?= addslashes($user['display_name']) ?>', '<?= $user['role'] ?>')">Edit</button>
                                    <button class="adm-btn adm-btn-danger" onclick="window.deleteUser(<?= $user['id'] ?>)">Delete</button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- footer.php đã được include ở trên, không include lại -->
    </div>
</div>

<div id="editUserModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div style="background: white; padding: 30px; border-radius: 16px; width: 400px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <h2 style="margin-bottom: 20px; color: #111827; font-family: 'Rammetto One', sans-serif; font-size: 1.2rem;">Edit User</h2>
        <form id="editUserForm">
            <input type="hidden" id="edit_user_id">
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151; font-size: 0.9rem;">Display Name</label>
                <input type="text" id="edit_display_name" style="width: 100%; padding: 10px 15px; border: 1px solid #D1D5DB; border-radius: 8px; font-family: inherit;">
            </div>
            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151; font-size: 0.9rem;">Role</label>
                <select id="edit_role" style="width: 100%; padding: 10px 15px; border: 1px solid #D1D5DB; border-radius: 8px; font-family: inherit;">
                    <option value="user">USER</option>
                    <option value="admin">ADMIN</option>
                </select>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="adm-btn adm-btn-outline" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="adm-btn" style="background: #FCD34D; color: #111827;">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script src="/smart-recipes/frontend/assets/js/admin/users.js"></script>
</body>
</html>