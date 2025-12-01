<?php include 'layouts/header.php'; ?>
<?php include 'layouts/sidebar.php'; ?>

<main class="main-content">
    <div class="container">
        <?php if (!isset($action) || $action === 'list'): ?>
            <div class="page-header">
                <h1>Sermons</h1>
                <?php if ($_SESSION['user_role'] === 'pastor'): ?>
                    <a href="/sermons/add" class="btn btn-primary">Add Sermon</a>
                <?php endif; ?>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <div class="sermons-grid">
                <?php foreach ($sermons as $sermon): ?>
                    <div class="sermon-card">
                        <div class="sermon-header">
                            <h3><?php echo htmlspecialchars($sermon['title']); ?></h3>
                            <div class="sermon-pastor">
                                <i class="fas fa-user-tie"></i>
                                <?php echo htmlspecialchars($sermon['first_name'] . ' ' . $sermon['last_name']); ?>
                            </div>
                            <div class="sermon-date"><?php echo date('M d, Y H:i', strtotime($sermon['date'])); ?></div>
                        </div>
                        <div class="sermon-content">
                            <p><?php echo htmlspecialchars($sermon['message']); ?></p>
                        </div>
                        <div class="sermon-actions">
                            <?php if ($_SESSION['user_role'] === 'pastor' && $sermon['pastor_id'] == $_SESSION['user_id']): ?>
                                <form action="/sermons/<?php echo $sermon['id']; ?>/delete" method="POST" style="display: inline;">
                                    <button type="submit" class="btn-sm btn-delete" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif ($action === 'add'): ?>
            <div class="page-header">
                <h1>Add Sermon</h1>
                <a href="/sermons" class="btn btn-secondary">Back to Sermons</a>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <div class="form-container">
                <form action="/sermons/store" method="POST">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="10" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Sermon</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</main>

<script src="/app.js"></script>

</body>
</html>
