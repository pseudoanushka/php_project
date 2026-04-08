<?php
/**
 * LifeTrack: Personal Decision Analyzer
 * DASHBOARD — Score Trends & Analysis
 * 
 * Demonstrates:
 * - Database READ operations
 * - foreach, for, while loops
 * - Associative arrays
 * - String functions
 * - Anonymous functions
 */

require_once 'db.php';
require_once 'functions.php';

// ============================================================
// FETCH DASHBOARD DATA
// ============================================================

$today_entry = getTodayEntry($pdo);
$score_trend = getScoreTrend($pdo, 14);
$category_dist = getCategoryDistribution($pdo);
$all_entries = getAllEntries($pdo, 10);

// Calculate streak and stats using loops
$total_entries = count($all_entries);
$healthy_count = 0;
$risky_count = 0;
$chaotic_count = 0;

// while loop — count categories
$idx = 0;
while ($idx < count($category_dist)) {
    $cat = $category_dist[$idx];
    switch (strtolower($cat['category'])) {
        case 'healthy': $healthy_count = $cat['count']; break;
        case 'risky':   $risky_count = $cat['count']; break;
        case 'chaotic': $chaotic_count = $cat['count']; break;
    }
    $idx++;
}

$grand_total = $healthy_count + $risky_count + $chaotic_count;

$page_title = "Dashboard";
require_once 'includes/header.php';
?>

<div class="page-header">
    <h1>📊 Your Dashboard</h1>
    <p>Overview of your lifestyle trends and daily analysis.</p>
</div>

<!-- ============================================ -->
<!-- TODAY'S STATUS                                -->
<!-- ============================================ -->
<?php if ($today_entry): ?>
<div class="category-banner <?php echo strtolower($today_entry['category']); ?> animate-slide">
    <h2>
        <?php echo getCategoryIcon($today_entry['category']); ?>
        Today's Verdict: <strong><?php echo strtoupper($today_entry['category']); ?></strong>
    </h2>
    <p><?php echo generateSummary($today_entry['name'], $today_entry['category'], $today_entry['score'], $today_entry['entry_date']); ?></p>
    
    <!-- Score Circle -->
    <div class="score-circle-wrap" style="margin-top: var(--space-md);">
        <div class="score-circle" 
             style="--score-pct: <?php echo $today_entry['score']; ?>; --score-color: var(--<?php echo strtolower($today_entry['category']); ?>-primary);">
            <div class="score-circle-inner">
                <span class="score-number" style="color: var(--<?php echo strtolower($today_entry['category']); ?>-primary);">
                    <?php echo $today_entry['score']; ?>
                </span>
                <span class="score-label">out of 100</span>
            </div>
        </div>
    </div>
    
    <!-- Scorecard image link -->
    <a href="scorecard.php?id=<?php echo $today_entry['id']; ?>" target="_blank" class="btn btn-secondary btn-sm" style="margin-top: var(--space-md);">
        🎨 Generate Score Card Image
    </a>
</div>
<?php else: ?>
<div class="alert alert-info animate-slide">
    📝 You haven't logged today's habits yet. <a href="add_entry.php" style="font-weight: 600;">Log now →</a>
</div>
<?php endif; ?>

<!-- ============================================ -->
<!-- STATS OVERVIEW                               -->
<!-- ============================================ -->
<div class="stats-grid">
    <div class="stat-card category-healthy">
        <div class="stat-value"><?php echo $healthy_count; ?></div>
        <div class="stat-label">🌟 Healthy Days</div>
    </div>
    <div class="stat-card category-risky">
        <div class="stat-value"><?php echo $risky_count; ?></div>
        <div class="stat-label">⚠️ Risky Days</div>
    </div>
    <div class="stat-card category-chaotic">
        <div class="stat-value"><?php echo $chaotic_count; ?></div>
        <div class="stat-label">🌪️ Chaotic Days</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" style="color: var(--accent-purple-light);">
            <?php echo $grand_total; ?>
        </div>
        <div class="stat-label">📊 Total Entries</div>
    </div>
</div>

<!-- ============================================ -->
<!-- CATEGORY DISTRIBUTION BAR                    -->
<!-- ============================================ -->
<?php if ($grand_total > 0): ?>
<div class="card" style="margin-bottom: var(--space-xl);">
    <div class="card-header">
        <div class="card-title">📈 Category Distribution</div>
    </div>
    <div style="display: flex; border-radius: var(--radius-full); overflow: hidden; height: 12px; background: rgba(255,255,255,0.05);">
        <?php if ($healthy_count > 0): ?>
        <div style="width: <?php echo round($healthy_count / $grand_total * 100); ?>%; background: var(--healthy-primary); transition: width 1s ease;" 
             class="tooltip" data-tooltip="Healthy: <?php echo $healthy_count; ?>"></div>
        <?php endif; ?>
        <?php if ($risky_count > 0): ?>
        <div style="width: <?php echo round($risky_count / $grand_total * 100); ?>%; background: var(--risky-primary); transition: width 1s ease;"
             class="tooltip" data-tooltip="Risky: <?php echo $risky_count; ?>"></div>
        <?php endif; ?>
        <?php if ($chaotic_count > 0): ?>
        <div style="width: <?php echo round($chaotic_count / $grand_total * 100); ?>%; background: var(--chaotic-primary); transition: width 1s ease;"
             class="tooltip" data-tooltip="Chaotic: <?php echo $chaotic_count; ?>"></div>
        <?php endif; ?>
    </div>
    <div style="display: flex; justify-content: space-between; margin-top: var(--space-sm); font-size: 0.75rem; color: var(--text-muted);">
        <span style="color: var(--healthy-primary);">● Healthy <?php echo $grand_total > 0 ? round($healthy_count / $grand_total * 100) : 0; ?>%</span>
        <span style="color: var(--risky-primary);">● Risky <?php echo $grand_total > 0 ? round($risky_count / $grand_total * 100) : 0; ?>%</span>
        <span style="color: var(--chaotic-primary);">● Chaotic <?php echo $grand_total > 0 ? round($chaotic_count / $grand_total * 100) : 0; ?>%</span>
    </div>
</div>
<?php endif; ?>

<!-- ============================================ -->
<!-- SCORE TREND CHART (CSS bars)                 -->
<!-- ============================================ -->
<?php if (!empty($score_trend)): ?>
<div class="card" style="margin-bottom: var(--space-xl);">
    <div class="card-header">
        <div class="card-title">📉 Score Trend (Last <?php echo count($score_trend); ?> Entries)</div>
    </div>
    <div class="chart-bars">
        <?php 
        // foreach loop — render chart bars
        foreach ($score_trend as $point): 
            $bar_height = max(4, $point['score']); // minimum 4% height
            $cat_class = strtolower($point['category']);
        ?>
        <div class="chart-bar-wrap">
            <div class="chart-bar-value"><?php echo $point['score']; ?></div>
            <div class="chart-bar <?php echo $cat_class; ?>" 
                 style="height: <?php echo $bar_height; ?>%;"
                 title="<?php echo $point['entry_date'] . ' — ' . $point['score'] . '/100 ' . strtoupper($point['category']); ?>">
            </div>
            <div class="chart-bar-label"><?php echo date('M j', strtotime($point['entry_date'])); ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- ============================================ -->
<!-- TODAY'S HABIT BREAKDOWN                      -->
<!-- ============================================ -->
<?php if ($today_entry): ?>
<div class="dashboard-grid">
    <div class="card">
        <div class="card-header">
            <div class="card-title">🔬 Today's Habit Breakdown</div>
        </div>
        
        <!-- Habit meters using PHP calculations -->
        <div class="habit-meters">
            <?php
            // Associative array for habit display data
            $habits_display = [
                ['label' => '😴 Sleep',    'value' => $today_entry['sleep_hours'] . 'h',  'pct' => min(100, ($today_entry['sleep_hours'] / 9) * 100),  'max' => '9h'],
                ['label' => '📚 Study',    'value' => $today_entry['study_hours'] . 'h',  'pct' => min(100, ($today_entry['study_hours'] / 8) * 100),  'max' => '8h'],
                ['label' => '🏃 Exercise', 'value' => $today_entry['exercise_minutes'] . 'm', 'pct' => min(100, ($today_entry['exercise_minutes'] / 90) * 100), 'max' => '90m'],
                ['label' => '💧 Water',    'value' => $today_entry['water_glasses'] . ' gl','pct' => min(100, ($today_entry['water_glasses'] / 10) * 100), 'max' => '10'],
                ['label' => '🍔 Junk',     'value' => $today_entry['junk_food_count'] . 'x', 'pct' => min(100, ($today_entry['junk_food_count'] / 5) * 100), 'max' => '0 ideal'],
                ['label' => '📱 Screen',   'value' => $today_entry['screen_time'] . 'h',  'pct' => min(100, ($today_entry['screen_time'] / 10) * 100), 'max' => '<2h ideal'],
            ];
            
            // foreach loop to render habit meters
            foreach ($habits_display as $habit):
                $quality = $habit['pct'] >= 70 ? 'excellent' : ($habit['pct'] >= 40 ? 'good' : 'poor');
                // Invert for junk food and screen time (lower is better)
                if (strpos($habit['label'], 'Junk') !== false || strpos($habit['label'], 'Screen') !== false) {
                    $quality = $habit['pct'] <= 30 ? 'excellent' : ($habit['pct'] <= 60 ? 'good' : 'poor');
                }
            ?>
            <div class="habit-meter">
                <span class="habit-meter-label"><?php echo $habit['label']; ?></span>
                <div class="habit-meter-bar">
                    <div class="habit-meter-fill <?php echo $quality; ?>" 
                         style="width: <?php echo $habit['pct']; ?>%;"></div>
                </div>
                <span class="habit-meter-value"><?php echo $habit['value']; ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Fun Feedback Card -->
    <?php if (!empty($today_entry['fun_feedback'])): ?>
    <div class="card">
        <div class="card-header">
            <div class="card-title">🎭 Fun Mode</div>
        </div>
        <div class="fun-feedback">
            <div class="label">🎭 Sarcastic AI Feedback</div>
            <?php echo htmlspecialchars($today_entry['fun_feedback']); ?>
        </div>
        <p style="margin-top: var(--space-md); font-size: 0.75rem; color: var(--text-muted);">
            Mood: <?php echo getMoodEmoji($today_entry['mood']); ?> 
            <?php echo ucfirst($today_entry['mood']); ?>
        </p>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- ============================================ -->
<!-- RECENT ENTRIES TABLE                         -->
<!-- ============================================ -->
<?php if (!empty($all_entries)): ?>
<div class="card" style="margin-top: var(--space-xl);">
    <div class="card-header">
        <div class="card-title">📋 Recent Entries</div>
        <a href="history.php" class="btn btn-secondary btn-sm">View All →</a>
    </div>
    
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>User</th>
                    <th>Score</th>
                    <th>Category</th>
                    <th>Mood</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // for loop — display table rows
                for ($i = 0; $i < min(5, count($all_entries)); $i++): 
                    $entry = $all_entries[$i];
                ?>
                <tr>
                    <td><?php echo $entry['entry_date']; ?></td>
                    <td><?php echo ucfirst(htmlspecialchars($entry['name'])); ?></td>
                    <td><strong><?php echo $entry['score']; ?></strong>/100</td>
                    <td>
                        <span class="badge badge-<?php echo strtolower($entry['category']); ?>">
                            <?php echo getCategoryIcon($entry['category']) . ' ' . $entry['category']; ?>
                        </span>
                    </td>
                    <td><?php echo getMoodEmoji($entry['mood']); ?></td>
                    <td class="actions">
                        <a href="edit_entry.php?id=<?php echo $entry['id']; ?>" class="btn btn-secondary btn-sm">✏️</a>
                        <a href="delete_entry.php?id=<?php echo $entry['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this entry?');">🗑️</a>
                    </td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
