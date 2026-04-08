<?php
/**
 * LifeTrack: Personal Decision Analyzer
 * HISTORY — View All Past Entries
 * 
 * Demonstrates:
 * - Database READ (CRUD: Read)
 * - for, while, foreach loops
 * - Timeline display
 * - String functions for formatting
 */

require_once 'db.php';
require_once 'functions.php';

// ============================================================
// FETCH ALL ENTRIES
// ============================================================
$all_entries = getAllEntries($pdo, 100);
$total_count = count($all_entries);

$page_title = "Entry History";
require_once 'includes/header.php';
?>

<div class="page-header">
    <h1>📜 Your Lifestyle History</h1>
    <p>A complete timeline of all your daily habit entries. Total: <strong><?php echo $total_count; ?></strong> entries logged.</p>
</div>

<?php if ($total_count === 0): ?>
<!-- Empty State -->
<div class="empty-state">
    <div class="icon">📝</div>
    <h3>No entries yet!</h3>
    <p>Start tracking your daily habits to build your history.</p>
    <a href="add_entry.php" class="btn btn-primary" style="margin-top: var(--space-md);">Log Your First Day</a>
</div>

<?php else: ?>

<!-- ============================================ -->
<!-- FULL TABLE VIEW                              -->
<!-- ============================================ -->
<div class="card" style="margin-bottom: var(--space-xl);">
    <div class="card-header">
        <div class="card-title">📋 All Entries</div>
        <span class="card-subtitle"><?php echo $total_count; ?> total</span>
    </div>
    
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>User</th>
                    <th>😴 Sleep</th>
                    <th>📚 Study</th>
                    <th>🏃 Exercise</th>
                    <th>🍔 Junk</th>
                    <th>💧 Water</th>
                    <th>📱 Screen</th>
                    <th>Score</th>
                    <th>Category</th>
                    <th>Mood</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // for loop — display all entries with numbering
                for ($i = 0; $i < $total_count; $i++): 
                    $entry = $all_entries[$i];
                    $row_num = $i + 1;
                ?>
                <tr>
                    <td style="color: var(--text-muted);"><?php echo $row_num; ?></td>
                    <td><?php echo $entry['entry_date']; ?></td>
                    <td>
                        <?php 
                        // ucfirst + strtolower — format name
                        echo ucfirst(strtolower(htmlspecialchars($entry['name']))); 
                        ?>
                    </td>
                    <td><?php echo $entry['sleep_hours']; ?>h</td>
                    <td><?php echo $entry['study_hours']; ?>h</td>
                    <td><?php echo $entry['exercise_minutes']; ?>m</td>
                    <td><?php echo $entry['junk_food_count']; ?>x</td>
                    <td><?php echo $entry['water_glasses']; ?></td>
                    <td><?php echo $entry['screen_time']; ?>h</td>
                    <td><strong style="color: var(--<?php echo strtolower($entry['category']); ?>-primary);"><?php echo $entry['score']; ?></strong></td>
                    <td>
                        <span class="badge badge-<?php echo strtolower($entry['category']); ?>">
                            <?php echo getCategoryIcon($entry['category']) . ' ' . $entry['category']; ?>
                        </span>
                    </td>
                    <td><?php echo getMoodEmoji($entry['mood']); ?></td>
                    <td class="actions">
                        <a href="scorecard.php?id=<?php echo $entry['id']; ?>" class="btn btn-secondary btn-sm" title="Score Card" target="_blank">🎨</a>
                        <a href="edit_entry.php?id=<?php echo $entry['id']; ?>" class="btn btn-secondary btn-sm" title="Edit">✏️</a>
                        <a href="delete_entry.php?id=<?php echo $entry['id']; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this entry?');">🗑️</a>
                    </td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================ -->
<!-- TIMELINE VIEW — Using foreach loop           -->
<!-- ============================================ -->
<h2 class="section-title">🕒 Timeline View</h2>
<div class="timeline">
    <?php 
    // foreach loop — iterate through entries for timeline
    foreach ($all_entries as $index => $entry): 
    ?>
    <div class="timeline-item <?php echo strtolower($entry['category']); ?> animate-slide" 
         style="animation-delay: <?php echo min($index * 0.05, 1); ?>s;">
        <div class="timeline-date">
            <?php echo $formatTimeAgo($entry['entry_date']); ?> • 
            <?php echo $entry['entry_date']; ?> • 
            <?php echo getMoodEmoji($entry['mood']) . ' ' . ucfirst($entry['mood']); ?>
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
                by <?php echo ucfirst(htmlspecialchars($entry['name'])); ?>
            </span>
        </div>
        <div class="timeline-details">
            <span>😴 <?php echo $entry['sleep_hours']; ?>h</span>
            <span>📚 <?php echo $entry['study_hours']; ?>h</span>
            <span>🏃 <?php echo $entry['exercise_minutes']; ?>m</span>
            <span>🍔 <?php echo $entry['junk_food_count']; ?>x</span>
            <span>💧 <?php echo $entry['water_glasses']; ?></span>
            <span>📱 <?php echo $entry['screen_time']; ?>h</span>
        </div>
        
        <?php if (!empty($entry['fun_feedback'])): ?>
        <div class="fun-feedback" style="margin-top: var(--space-sm); padding: var(--space-sm) var(--space-md); font-size: 0.8rem;">
            <span style="opacity: 0.7;">🎭</span> <?php echo htmlspecialchars($entry['fun_feedback']); ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>

<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
