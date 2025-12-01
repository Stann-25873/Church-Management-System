<header id="main-navbar">
    <div class="navbar-inner">
        
        <div class="logo-section">
            <a href="/dashboard">Church Management System</a>
        </div>

        <nav class="main-nav">
            <ul>
                <li><a href="/dashboard">Dashboard</a></li>
                <?php if (in_array($_SESSION['user_role'], ['admin', 'pastor'])): ?>
                    <li><a href="/members">Members</a></li>
                <?php endif; ?>
                <li><a href="/events">Events</a></li>
                <?php if (in_array($_SESSION['user_role'], ['admin', 'pastor'])): ?>
                    <li><a href="/offerings">Offerings</a></li>
                <?php endif; ?>
                <?php if ($_SESSION['user_role'] === 'pastor'): ?>
                    <li><a href="/sermons">Sermons</a></li>
                <?php endif; ?>
                <li><a href="/profile">Profile</a></li>
                </ul>
                
                 <div class="header-actions">

            <button class="dark-mode-toggle" id="dark-mode-toggle">
                <i class="fas fa-moon"></i>
            </button>
           
        </div>
        </nav>

        <div class="logout-section">
            <a href="/logout" class="btn btn-logout">Logout</a>
        </div>
    </div>
</header>