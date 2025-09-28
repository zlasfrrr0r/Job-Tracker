<html>

<head>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0
,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Signika:wght@300..700&display=swap" rel="stylesheet">

<style>
footer {
    background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
    padding: 1rem 2rem;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    font-family: 'Poppins', Sans-serif;
    margin: 0;
    padding: 0;
    color: #2c3e50;
}

.footer-container {
    display: flex;
    flex-flow: row wrap;
    justify-content: space-between;
    align-items: center;
    height: auto;
    gap: 1rem;
}

.footer-main {
    text-align: left;
}

.footer-main p {
    margin: 0.4rem;
}

.footer-main .get-hired {
    font-weight: 700;
    color: #34495e;
}

.footer-navbar ul {
    display: flex;
    gap: 2rem;
    list-style: none;
    font-size: 1rem;
    font-weight: 600;
    margin: 2rem;
    padding: 1rem;
}

.footer-navbar a {
    text-decoration: none;
    color: black;
    font-weight: bold;
    padding: 0.2rem 0.5rem;
    border: 3px solid rgb(193, 192, 191);
    background-color: rgb(209, 207, 207);
    transition: all 0.3s ease;
    border-radius: 15%;
}

a:hover {
    background-color: #5396b7;
}

@media (max-width: 750px) {
    .footer {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .footer-navbar {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>

</head>

<body>
    
<footer>

    <div class="footer-container">
        <div class="footer-main">
            <p>&copy <?php echo date("Y"); ?> jobPilot</p>
            <p class="get-hired">Get Hired</p>
        </div>
        <div class="footer-navbar">
            <nav>
                <ul>
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == TRUE): ?>
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="https://github.com/zlasfrrr0r" target="_blank">Contact Author</a></li>
                    <?php else: ?>
                        <li><a href="index.php">Login</a></li>
                        <li><a href="signup.php">Signup</a></li>
                        <li><a href="https://github.com/zlasfrrr0r" target="_blank">Contact Author</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>

</footer>

</body>

</html>