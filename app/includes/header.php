<html>
<head>
<?php define('BASE_URL', '/jobtracker/'); ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0
,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Signika:wght@300..700&display=swap" rel="stylesheet">

<style>
header {
    background: linear-gradient(135deg, #c3cfe2, #f5f7fa); 
    padding: 1rem 2rem;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    font-family: 'Poppins', Sans-serif;
    margin: 0;
    padding: 0;
    color: #2c3e50;
}

.header-container {
    display: flex;
    flex-flow: row wrap;
    justify-content: space-between;
    align-items: center;
    height: auto;
    gap: 1rem;
    width: 100%;
}

.logo a img {
    height: 100px;
    max-width: 100%;
}

.header-main {
    flex: 1;
    text-align: center;
}
.header-main .welcome {
    font-size: 2rem;
    font-weight: 600;
    color: #34495e;
}

.header-main .motivation {
    font-size: 1rem;
    font-style: italic;
    color: #494c4d;
}

.navbar-container {
    display: flex;
    gap: 2rem;
    list-style: none;
    font-size: 1rem;
    font-weight: 600;
    margin: 2rem;
    padding: 1rem;
}

a:hover {
    background-color: #5396b7 !important;
}

.navbar-container a {
    text-decoration: none;
    color: black;
    font-weight: bold;
    padding: 0.2rem 0.5rem;
    border: 3px solid rgb(193, 192, 191);
    background-color: rgb(209, 207, 207);
    transition: all 0.3s ease;
    border-radius: 15%;
}

@media (max-width: 750px) {
    .header-container {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .navbar-container {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>


</head>

<body>
    
<header>
    <div class="header-container">
        <div class="logo">
            <a href="<?php (isset($_SESSION['loggedin'])) ? 'dashboard.php' : 'index.php'; ?>">
                <img src="<?php echo BASE_URL; ?>assets/imgs/web-logo.png" alt="Logo" id="my-logo">
            </a>
        </div>

        <div class="header-main">
            <?php 
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE) {
                echo "<p class='welcome'>Welcome, " . htmlspecialchars($_SESSION['username']) . "!</p>";
            } else {
                echo "<p class='welcome'>Welcome to your jobPilot- your Job Tracker!</p>";
            }

            $motivation = array(
                "Stay Organised. Stay ahead.",
                "Persistnece is key.",
                "Every application counts.",
                "It's never too late.",
                "One step closer to that job."
            );

            echo "<p class='motivation'>" . $motivation[array_rand($motivation)] . "</p>"; 
            ?>
        </div>

        <div class="navbar">
            <nav>
                <ul class="navbar-container">
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE): ?>
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="index.php">Login</a></li>
                        <li><a href="signup.php">Signup</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>

</header>

</body>

</html>