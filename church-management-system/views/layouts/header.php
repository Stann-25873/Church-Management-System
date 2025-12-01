<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Church Management System</title>
    <link rel="stylesheet" href="/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="layout">
    <header id="main-navbar">
        <div class="navbar-inner">
            <div class="logo-section">
                <a href="/dashboard" title="Church Management System"><i class="fas fa-church"></i> CMS</a>
            </div>

            <div class="header-actions">
                <a href="/dashboard" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a>
                <?php if (in_array($_SESSION['user_role'], ['admin', 'pastor'])): ?>
                    <a href="/members" class="nav-link"><i class="fas fa-users"></i> Members</a>
                <?php endif; ?>
                <a href="/events" class="nav-link"><i class="fas fa-calendar-alt"></i> Events</a>
                <?php if (in_array($_SESSION['user_role'], ['admin', 'pastor'])): ?>
                    <a href="/offerings" class="nav-link"><i class="fas fa-hand-holding-heart"></i> Offerings</a>
                <?php endif; ?>
                <?php if ($_SESSION['user_role'] === 'pastor'): ?>
                    <a href="/sermons" class="nav-link"><i class="fas fa-book"></i> Sermons</a>
                <?php endif; ?>
                <a href="/profile" class="nav-link"><i class="fas fa-user"></i> Profile</a>

                <button class="dark-mode-toggle" id="dark-mode-toggle">
                    <i class="fas fa-moon"></i>
                </button>

                <form action="/logout" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </header>
