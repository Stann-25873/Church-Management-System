<?php include 'layouts/header.php'; ?>

<main class="main-content">
    <div class="content">
        <?php if (!isset($action) || $action === 'list'): ?>
            <div class="section-header">
                <h2>👥 Members Management</h2>
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <a href="/members/add" class="btn btn-primary">+ Add Member</a>
                <?php endif; ?>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <div class="members-grid">
                <?php foreach ($members as $member): ?>
                    <div class="member-card">
                        <div class="member-avatar">
                            <?php if ($member['photo']): ?>
                                <img src="<?php echo htmlspecialchars($member['photo']); ?>" alt="Photo">
                            <?php else: ?>
                                <div class="avatar-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="member-info">
                            <h3><?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></h3>
                            <p class="member-email"><?php echo htmlspecialchars($member['email']); ?></p>
                            <div class="member-meta">
                                <span class="badge badge-primary"><?php echo ucfirst($member['role']); ?></span>
                                <span class="member-joined">Joined: <?php echo date('M d, Y', strtotime($member['created_at'])); ?></span>
                            </div>
                        </div>
                        <div class="member-actions">
                            <a href="/members/<?php echo $member['id']; ?>" class="btn-sm btn-view">View</a>
                            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                <a href="/members/<?php echo $member['id']; ?>/edit" class="btn-sm btn-edit">Edit</a>
                                <form action="/members/<?php echo $member['id']; ?>/delete" method="POST" style="display: inline;">
                                    <button type="submit" class="btn-sm btn-delete" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif ($action === 'add' || $action === 'edit'): ?>
            <div class="section-header">
                <h2><?php echo $action === 'add' ? '➕ Add Member' : '✏️ Edit Member'; ?></h2>
                <a href="/members" class="btn btn-view">← Back to Members</a>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <div class="dashboard-section">
                <div class="section-content">
                <form action="<?php echo $action === 'add' ? '/members/store' : '/members/' . $member['id'] . '/update'; ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo $action === 'edit' ? htmlspecialchars($member['first_name']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo $action === 'edit' ? htmlspecialchars($member['last_name']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo $action === 'edit' ? htmlspecialchars($member['email']) : ''; ?>" required>
                    </div>
                    <?php if ($action === 'add'): ?>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                    <?php endif; ?>
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select id="role" name="role">
                                <option value="member" <?php echo ($action === 'edit' && $member['role'] === 'member') ? 'selected' : ''; ?>>Member</option>
                                <option value="pastor" <?php echo ($action === 'edit' && $member['role'] === 'pastor') ? 'selected' : ''; ?>>Pastor</option>
                                <option value="admin" <?php echo ($action === 'edit' && $member['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="photo">Photo</label>
                        <input type="file" id="photo" name="photo" accept="image/*" onchange="previewImage(event)">
                        <div id="image-preview"></div>
                        <?php if ($action === 'edit' && $member['photo']): ?>
                            <p>Current photo: <img src="<?php echo htmlspecialchars($member['photo']); ?>" alt="Current photo" style="max-width: 100px;"></p>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary"><?php echo $action === 'add' ? 'Add Member' : 'Update Member'; ?></button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</main>

<script src="/app.js"></script>

</body>
</html>
