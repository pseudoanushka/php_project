<?php
/**
 * LifeTrack: Personal Decision Analyzer
 * Layout Header Partial
 * 
 * Included at the top of every page.
 * $page_title should be set before including this file.
 */

$page_title = $page_title ?? 'LifeTrack';
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="LifeTrack — Personal Decision Analyzer. Track your daily habits and discover if your lifestyle is Healthy, Risky, or Chaotic.">
    <title><?php echo htmlspecialchars($page_title); ?> — LifeTrack</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🧬</text></svg>">
</head>
<body>
    <div class="page-wrapper">
        <!-- Navigation Bar -->
        <nav class="navbar">
            <div class="container">
                <a href="index.php" class="navbar-brand">
                    <span class="logo-icon">🧬</span>
                    <span>LifeTrack</span>
                </a>
                <ul class="nav-links">
                    <li><a href="index.php" class="<?php echo $current_page === 'index' ? 'active' : ''; ?>">🏠 Home</a></li>
                    <li><a href="dashboard.php" class="<?php echo $current_page === 'dashboard' ? 'active' : ''; ?>">📊 Dashboard</a></li>
                    <li><a href="history.php" class="<?php echo $current_page === 'history' ? 'active' : ''; ?>">📜 History</a></li>
                    <li><a href="add_entry.php" class="btn-nav-primary <?php echo $current_page === 'add_entry' ? 'active' : ''; ?>">➕ New Entry</a></li>
                </ul>
            </div>
        </nav>

        <!-- Main Content Start -->
        <main class="main-content">
            <div class="container animate-fade">
