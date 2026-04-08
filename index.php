<?php
/**
 * LifeTrack: Personal Decision Analyzer
 * ============================================
 * HOMEPAGE — "Hello World" + App Introduction
 * 
 * Demonstrates:
 * - PHP echo / print
 * - Variables (string, integer, array)
 * - PHP embedded in HTML
 */

// ---- Include database and functions ----
require_once 'db.php';
require_once 'functions.php';

// ============================================================
// PHP VARIABLES & DATA TYPES DEMONSTRATION
// ============================================================

// String variables
$app_name    = "LifeTrack";                    // string
$app_tagline = "Personal Decision Analyzer";   // string
$greeting    = "Hello, World!";                // string — classic start!

// Integer variables
$version       = 1;             // integer
$total_habits  = 6;             // integer
$max_score     = 100;           // integer

// Float variable
$app_version = 1.0;             // float

// Boolean
$is_fun_mode = true;            // boolean

// Array — categories
$categories = ["Healthy", "Risky", "Chaotic"]; // indexed array

// Associative array — category descriptions
$category_info = [
    "Healthy" => ["emoji" => "🌟", "color" => "#10b981", "desc" => "You're making great choices!"],
    "Risky"   => ["emoji" => "⚠️",  "color" => "#f59e0b", "desc" => "Some habits need attention."],
    "Chaotic" => ["emoji" => "🌪️", "color" => "#ef4444", "desc" => "Time for a lifestyle reboot!"],
];

// Get stats from database
$stmt = $pdo->query("SELECT COUNT(*) as total FROM entries");
$total_entries = $stmt->fetch()['total']; // integer from DB

$stmt = $pdo->query("SELECT AVG(score) as avg FROM entries");
$row = $stmt->fetch();
$avg_score = $row['avg'] ? round($row['avg']) : 0;

$today_entry = getTodayEntry($pdo);

// Set page title
$page_title = $greeting . " Welcome to " . $app_name;

// Include header
require_once 'includes/header.php';
?>

<!-- ============================================ -->
<!-- HERO SECTION — Hello World Introduction      -->
<!-- ============================================ -->
<section class="hero">
    <div class="hero-badge">
        <span>📡</span>
        <span>v<?php echo $app_version; ?> — <?php 
            // String function: strtoupper
            echo strtoupper($app_tagline); 
        ?></span>
    </div>
    
    <!-- PHP echo demonstration -->
    <h1><?php echo $greeting; ?> Welcome to<br><?php echo $app_name; ?></h1>
    
    <p>
        Track your daily habits — sleep, study, exercise, nutrition — 
        and discover whether your lifestyle is 
        <?php 
        // foreach loop to display categories inline
        $i = 0;
        foreach ($categories as $cat) {
            echo '<strong style="color:' . $category_info[$cat]['color'] . '">' 
                 . $category_info[$cat]['emoji'] . ' ' . $cat 
                 . '</strong>';
            $i++;
            if ($i < count($categories) - 1) echo ', ';
            elseif ($i == count($categories) - 1) echo ', or ';
        }
        ?>.
    </p>
    
    <div class="hero-actions">
        <a href="add_entry.php" class="btn btn-primary btn-lg">➕ Log Today's Habits</a>
        <a href="dashboard.php" class="btn btn-secondary btn-lg">📊 View Dashboard</a>
    </div>
</section>

<!-- ============================================ -->
<!-- QUICK STATS — Using variables & DB data      -->
<!-- ============================================ -->
<section class="stats-grid">
    <!-- Total Entries (integer) -->
    <div class="stat-card">
        <div class="stat-value" style="color: var(--accent-purple-light);">
            <?php echo $total_entries; ?>
        </div>
        <div class="stat-label">Total Entries</div>
    </div>
    
    <!-- Average Score (calculated) -->
    <div class="stat-card">
        <div class="stat-value" style="color: var(--accent-cyan);">
            <?php echo $avg_score; ?><span style="font-size:1rem">/<?php echo $max_score; ?></span>
        </div>
        <div class="stat-label">Avg Score</div>
    </div>
    
    <!-- Today's Status -->
    <div class="stat-card <?php echo $today_entry ? getCategoryClass($today_entry['category']) : ''; ?>">
        <div class="stat-value">
            <?php if ($today_entry): ?>
                <?php echo getCategoryIcon($today_entry['category']) . ' ' . $today_entry['score']; ?>
            <?php else: ?>
                <span style="color: var(--text-muted);">—</span>
            <?php endif; ?>
        </div>
        <div class="stat-label">Today's Score</div>
    </div>
    
    <!-- Habits Tracked (integer) -->
    <div class="stat-card">
        <div class="stat-value" style="color: var(--accent-pink);">
            <?php echo $total_habits; ?>
        </div>
        <div class="stat-label">Habits Tracked</div>
    </div>
</section>

<!-- ============================================ -->
<!-- TODAY'S RESULT (if exists)                    -->
<!-- ============================================ -->
<?php if ($today_entry): ?>
<section class="category-banner <?php echo strtolower($today_entry['category']); ?> animate-slide">
    <h2>
        <?php echo getCategoryIcon($today_entry['category']); ?>
        <?php 
        // String function demo: generateSummary uses ucfirst, strtoupper, strlen
        echo generateSummary(
            $today_entry['name'], 
            $today_entry['category'], 
            $today_entry['score'],
            $today_entry['entry_date']
        ); 
        ?>
    </h2>
    <p>
        <?php 
        // Anonymous function demo — $formatScoreBadge
        echo $formatScoreBadge($today_entry['score'], $today_entry['category']); 
        ?>
    </p>
    
    <?php if (!empty($today_entry['fun_feedback'])): ?>
    <div class="fun-feedback" style="margin-top: var(--space-lg); text-align: left;">
        <div class="label">🎭 Fun Mode Feedback</div>
        <?php echo htmlspecialchars($today_entry['fun_feedback']); ?>
    </div>
    <?php endif; ?>
</section>
<?php endif; ?>

<!-- ============================================ -->
<!-- CATEGORIES EXPLAINED — foreach loop + arrays -->
<!-- ============================================ -->
<h2 class="section-title">📋 How Scoring Works</h2>
<div class="dashboard-grid">
    <?php 
    // foreach loop — iterate over associative array
    foreach ($category_info as $name => $info): 
    ?>
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span style="font-size: 1.5rem;"><?php echo $info['emoji']; ?></span>
                <?php 
                // strtoupper — display category name in uppercase
                echo strtoupper($name); 
                ?>
            </div>
            <span class="badge badge-<?php echo strtolower($name); ?>">
                <?php echo $name; ?>
            </span>
        </div>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">
            <?php echo $info['desc']; ?>
        </p>
        
        <div style="margin-top: var(--space-md);">
            <?php
            // Switch statement to show score ranges
            switch ($name) {
                case 'Healthy':
                    echo '<p style="font-size:0.8rem; color:var(--text-muted);">Score Range: <strong style="color:' . $info['color'] . '">70-100</strong></p>';
                    echo '<p style="font-size:0.78rem; color:var(--text-muted); margin-top:4px;">✓ Sleep 7-9 hrs &nbsp; ✓ Study 6+ hrs &nbsp; ✓ Exercise 60+ min</p>';
                    break;
                case 'Risky':
                    echo '<p style="font-size:0.8rem; color:var(--text-muted);">Score Range: <strong style="color:' . $info['color'] . '">40-69</strong></p>';
                    echo '<p style="font-size:0.78rem; color:var(--text-muted); margin-top:4px;">⚡ Sleep 5-7 hrs &nbsp; ⚡ Study 3-6 hrs &nbsp; ⚡ Some junk food</p>';
                    break;
                case 'Chaotic':
                    echo '<p style="font-size:0.8rem; color:var(--text-muted);">Score Range: <strong style="color:' . $info['color'] . '">0-39</strong></p>';
                    echo '<p style="font-size:0.78rem; color:var(--text-muted); margin-top:4px;">✗ Sleep &lt;5 hrs &nbsp; ✗ No exercise &nbsp; ✗ Lots of junk food</p>';
                    break;
            }
            ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- ============================================ -->
<!-- RECENT ENTRIES — for loop demo               -->
<!-- ============================================ -->
<?php
$recent_entries = getAllEntries($pdo, 5);
if (!empty($recent_entries)):
?>
<h2 class="section-title">🕐 Recent Activity</h2>
<div class="timeline">
    <?php 
    // for loop — display entries with index
    for ($i = 0; $i < count($recent_entries); $i++): 
        $entry = $recent_entries[$i];
    ?>
    <div class="timeline-item <?php echo strtolower($entry['category']); ?> animate-slide" style="animation-delay: <?php echo $i * 0.1; ?>s;">
        <div class="timeline-date">
            <?php 
            // Anonymous function demo — $formatTimeAgo
            echo $formatTimeAgo($entry['entry_date']); 
            ?> 
            • <?php echo $entry['entry_date']; ?>
            • <?php echo getMoodEmoji($entry['mood']); ?>
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
            <div>
                <span class="timeline-score" style="color: var(--<?php echo strtolower($entry['category']); ?>-primary);">
                    <?php echo $entry['score']; ?>/100
                </span>
                <span class="badge badge-<?php echo strtolower($entry['category']); ?>" style="margin-left: 8px;">
                    <?php echo getCategoryIcon($entry['category']) . ' ' . strtoupper($entry['category']); ?>
                </span>
            </div>
            <span style="font-size: 0.8rem; color: var(--text-muted);">
                <?php 
                // ucfirst — capitalize user name  
                echo ucfirst(strtolower($entry['name'])); 
                ?>
            </span>
        </div>
        <div class="timeline-details">
            <span>😴 <?php echo $entry['sleep_hours']; ?>h sleep</span>
            <span>📚 <?php echo $entry['study_hours']; ?>h study</span>
            <span>🏃 <?php echo $entry['exercise_minutes']; ?>min exercise</span>
            <span>🍔 <?php echo $entry['junk_food_count']; ?>x junk</span>
            <span>💧 <?php echo $entry['water_glasses']; ?> glasses</span>
        </div>
    </div>
    <?php endfor; ?>
</div>
<?php else: ?>
<div class="empty-state">
    <div class="icon">📝</div>
    <h3>No entries yet!</h3>
    <p>Start tracking your daily habits to see your lifestyle analysis.</p>
    <a href="add_entry.php" class="btn btn-primary" style="margin-top: var(--space-md);">Log Your First Day</a>
</div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
