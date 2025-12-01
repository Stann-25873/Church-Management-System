<?php include 'layouts/header.php'; ?>

<main class="main-content">
    <div class="content">
        <?php if (!isset($action) || $action === 'list'): ?>
            <div class="section-header">
                <h2>💳 Offerings Management</h2>
                <a href="/offerings/add" class="btn btn-primary">+ Add Offering</a>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <div class="offerings-grid">
                <?php foreach ($offerings as $offering): ?>
                    <div class="offering-card">
                        <div class="offering-amount">$<?php echo number_format($offering['amount'], 2); ?></div>
                        <div class="offering-member"><?php echo htmlspecialchars($offering['first_name'] . ' ' . $offering['last_name']); ?></div>
                        <div class="offering-date"><?php echo date('M d, Y H:i', strtotime($offering['date'])); ?></div>
                        <div class="offering-actions">
                            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                <form action="/offerings/<?php echo $offering['id']; ?>/delete" method="POST" style="display: inline;">
                                    <button type="submit" class="btn-sm btn-delete" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif ($action === 'add'): ?>
            <div class="section-header">
                <h2>➕ Add Offering</h2>
                <a href="/offerings" class="btn btn-view">← Back to Offerings</a>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <div class="dashboard-section">
                <div class="section-content">
                <form action="/offerings/store" method="POST">
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <div class="form-group">
                            <label for="member_id">Member</label>
                            <select id="member_id" name="member_id" required>
                                <option value="">Select Member</option>
                                <?php
                                // Assuming we have a way to get members list, but for simplicity, we'll skip this in view
                                // In real implementation, pass members from controller
                                ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" id="amount" name="amount" step="0.01" min="0.01" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Offering</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</main>

<script src="/app.js"></script>

</body>
</html>
