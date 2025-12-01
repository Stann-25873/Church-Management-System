<?php include 'layouts/header.php'; ?>

<main class="main-content">
    <div class="content">
        <div class="church-banner">
            <div class="banner-content">
                <h2>🙏 Welcome to Our Church Management System</h2>
                <p>"For where two or three gather in my name, there am I with them." - Matthew 18:20</p>
            </div>
        </div>

        <h1 class="dashboard-title">📊 Dashboard</h1>

        <div class="stats-grid">
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <div class="stat-card info">
                    <div class="value"><?php echo $stats['total_members'] ?? 0; ?></div>
                    <h3>👥 Total Members</h3>
                </div>
                <div class="stat-card success">
                    <div class="value"><?php echo $stats['total_pastors'] ?? 0; ?></div>
                    <h3>🙏 Total Pastors</h3>
                </div>
                <div class="stat-card warning">
                    <div class="value"><?php echo $stats['upcoming_events'] ?? 0; ?></div>
                    <h3>📅 Upcoming Events</h3>
                </div>
                <div class="stat-card danger">
                    <div class="value">$<?php echo number_format($stats['total_offerings'] ?? 0, 2); ?></div>
                    <h3>💰 Total Offerings</h3>
                </div>
            <?php elseif ($_SESSION['user_role'] === 'pastor'): ?>
                <div class="stat-card info">
                    <div class="value"><?php echo $stats['total_members'] ?? 0; ?></div>
                    <h3>👥 Total Members</h3>
                </div>
                <div class="stat-card warning">
                    <div class="value"><?php echo $stats['upcoming_events'] ?? 0; ?></div>
                    <h3>📅 Upcoming Events</h3>
                </div>
                <div class="stat-card danger">
                    <div class="value">$<?php echo number_format($stats['total_offerings'] ?? 0, 2); ?></div>
                    <h3>💰 Total Offerings</h3>
                </div>
                <div class="stat-card success">
                    <div class="value"><?php echo $stats['my_sermons'] ?? 0; ?></div>
                    <h3>📖 My Sermons</h3>
                </div>
            <?php else: ?>
                <div class="stat-card warning">
                    <div class="value"><?php echo $stats['upcoming_events'] ?? 0; ?></div>
                    <h3>📅 Upcoming Events</h3>
                </div>
                <div class="stat-card danger">
                    <div class="value">$<?php echo number_format($stats['my_offerings'] ?? 0, 2); ?></div>
                    <h3>💰 My Offerings</h3>
                </div>
            <?php endif; ?>
        </div>

        <div class="dashboard-sections">
            <?php if (isset($stats['recent_offerings']) && !empty($stats['recent_offerings'])): ?>
                <div class="dashboard-section">
                    <div class="section-header">
                        <h2>💳 Recent Offerings</h2>
                    </div>
                    <div class="section-content">
                        <div class="recent-offerings-grid">
                            <?php foreach ($stats['recent_offerings'] as $offering): ?>
                                <div class="recent-offering-card">
                                    <h4><?php echo htmlspecialchars($offering['first_name'] . ' ' . $offering['last_name']); ?></h4>
                                    <div class="offering-amount">$<?php echo number_format($offering['amount'], 2); ?></div>
                                    <div class="offering-date"><?php echo date('M d, Y', strtotime($offering['date'])); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($stats['recent_members']) && !empty($stats['recent_members'])): ?>
                <div class="dashboard-section">
                    <div class="section-header">
                        <h2>👥 Recent Members</h2>
                    </div>
                    <div class="section-content">
                        <div class="recent-members-grid">
                            <?php foreach ($stats['recent_members'] as $member): ?>
                                <div class="recent-member-card">
                                    <h4><?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></h4>
                                    <div class="member-email"><?php echo htmlspecialchars($member['email']); ?></div>
                                    <div class="member-date">Joined: <?php echo date('M d, Y', strtotime($member['created_at'])); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script src="/app.js"></script>

</body>
</html>
