<?php include 'layouts/header.php'; ?>

<main class="main-content">
    <div class="content">
        <?php if (!isset($action) || $action === 'list'): ?>
            <div class="section-header">
                <h2>📅 Events Management</h2>
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <a href="/events/add" class="btn btn-primary">+ Add Event</a>
                <?php endif; ?>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <div class="events-grid">
                <?php foreach ($events as $event): ?>
                    <div class="event-card">
                        <div class="event-header">
                            <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                            <div class="event-date">
                                <i class="fas fa-calendar"></i>
                                <?php echo date('M d, Y', strtotime($event['event_date'])); ?>
                            </div>
                        </div>
                        <div class="event-description">
                            <p><?php echo htmlspecialchars(substr($event['description'], 0, 100)) . (strlen($event['description']) > 100 ? '...' : ''); ?></p>
                        </div>
                        <div class="event-meta">
                            <span class="event-creator">By: <?php echo htmlspecialchars($event['first_name'] . ' ' . $event['last_name']); ?></span>
                        </div>
                        <div class="event-actions">
                            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                <a href="/events/<?php echo $event['id']; ?>/edit" class="btn-sm btn-edit">Edit</a>
                                <form action="/events/<?php echo $event['id']; ?>/delete" method="POST" style="display: inline;">
                                    <button type="submit" class="btn-sm btn-delete" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif ($action === 'add' || $action === 'edit'): ?>
            <div class="section-header">
                <h2><?php echo $action === 'add' ? '➕ Add Event' : '✏️ Edit Event'; ?></h2>
                <a href="/events" class="btn btn-view">← Back to Events</a>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <div class="dashboard-section">
                <div class="section-content">
                <form action="<?php echo $action === 'add' ? '/events/store' : '/events/' . $event['id'] . '/update'; ?>" method="POST">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" value="<?php echo $action === 'edit' ? htmlspecialchars($event['title']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4"><?php echo $action === 'edit' ? htmlspecialchars($event['description']) : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="event_date">Event Date</label>
                        <input type="date" id="event_date" name="event_date" value="<?php echo $action === 'edit' ? htmlspecialchars($event['event_date']) : ''; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><?php echo $action === 'add' ? 'Add Event' : 'Update Event'; ?></button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</main>

<script src="/app.js"></script>

</body>
</html>
