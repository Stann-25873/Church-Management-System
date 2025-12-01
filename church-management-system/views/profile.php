<?php include 'layouts/header.php'; ?>

<main class="main-content">
    <div class="content">
        <div class="section-header">
            <h2>👤 My Profile</h2>
        </div>

        <div class="dashboard-section">
            <div class="section-content">
                <div class="profile-card" style="text-align: center; padding: 40px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); border-radius: 8px;">
                    <div class="profile-photo" style="margin-bottom: 20px;">
                        <?php if ($member['photo']): ?>
                            <img src="<?php echo htmlspecialchars($member['photo']); ?>" alt="Profile Photo" style="width: 150px; height: 150px; border-radius: 50%; border: 5px solid white; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                        <?php else: ?>
                            <div class="no-photo" style="width: 150px; height: 150px; background: #999; border-radius: 50%; border: 5px solid white; display: inline-flex; align-items: center; justify-content: center; color: white; font-size: 14px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">No Photo</div>
                        <?php endif; ?>
                    </div>
                    <div class="profile-info">
                        <h2 style="font-size: 28px; margin: 20px 0; color: #333;"><?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></h2>
                        <table style="width: 100%; max-width: 500px; margin: 20px auto; text-align: left;">
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 15px; font-weight: 600; color: #3f51b5;">📧 Email</td>
                                <td style="padding: 15px;"><?php echo htmlspecialchars($member['email']); ?></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 15px; font-weight: 600; color: #3f51b5;">👔 Role</td>
                                <td style="padding: 15px;"><span class="badge badge-primary"><?php echo ucfirst($member['role']); ?></span></td>
                            </tr>
                            <tr>
                                <td style="padding: 15px; font-weight: 600; color: #3f51b5;">📅 Joined</td>
                                <td style="padding: 15px;"><?php echo date('F d, Y', strtotime($member['created_at'])); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="/app.js"></script>

</body>
</html>
