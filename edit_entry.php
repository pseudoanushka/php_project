<?php
/**
 * LifeTrack: Personal Decision Analyzer
 * EDIT ENTRY — Update existing entry (CRUD: Update)
 * 
 * Demonstrates:
 * - GET parameter handling (?id=...)
 * - Database READ + UPDATE
 * - Form pre-population
 * - Input validation
 */

require_once 'db.php';
require_once 'functions.php';

// ============================================================
// GET ENTRY ID FROM URL
// ============================================================
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: history.php');
    exit;
}

// Fetch existing entry
$entry = getEntryById($pdo, $id);

if (!$entry) {
    header('Location: history.php');
    exit;
}

// ============================================================
// HANDLE FORM SUBMISSION (UPDATE)
// ============================================================
$errors = [];
$success = false;
$result = null;

$form_data = [
    'name'             => $entry['name'],
    'age'              => $entry['age'],
    'sleep_hours'      => $entry['sleep_hours'],
    'study_hours'      => $entry['study_hours'],
    'exercise_minutes' => $entry['exercise_minutes'],
    'junk_food_count'  => $entry['junk_food_count'],
    'water_glasses'    => $entry['water_glasses'],
    'screen_time'      => $entry['screen_time'],
    'mood'             => $entry['mood'],
    'entry_date'       => $entry['entry_date'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validation = validateEntryForm($_POST);
    
    if ($validation['valid']) {
        // Update entry (CRUD: Update)
        $result = updateEntry($pdo, $id, $validation['cleaned']);
        $success = true;
        $form_data = $validation['cleaned'];
    } else {
        $errors = $validation['errors'];
        $form_data = array_merge($form_data, $_POST);
    }
}

$page_title = "Edit Entry #" . $id;
require_once 'includes/header.php';
?>

<div class="page-header">
    <h1>✏️ Edit Entry #<?php echo $id; ?></h1>
    <p>Update habits for <?php echo htmlspecialchars($entry['entry_date']); ?></p>
</div>

<!-- Success Message -->
<?php if ($success && $result): ?>
<div class="alert alert-success animate-slide">
    ✅ Entry updated! New score: <strong><?php echo $result['score']; ?>/100</strong>
    — Category: <strong><?php echo strtoupper($result['category']); ?></strong>
</div>
<?php endif; ?>

<!-- Error Messages -->
<?php if (!empty($errors)): ?>
<div class="alert alert-error animate-slide">
    ❌ Please fix the following:
    <ul style="margin: 8px 0 0 16px; list-style: disc;">
        <?php foreach ($errors as $err): ?>
            <li><?php echo htmlspecialchars($err); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<!-- Edit Form (same structure as add, pre-populated) -->
<form method="POST" action="edit_entry.php?id=<?php echo $id; ?>">
    <div class="card">
        <div class="card-header">
            <div class="card-title">👤 Personal Info</div>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label" for="name">Your Name</label>
                <input type="text" id="name" name="name" class="form-input"
                       value="<?php echo htmlspecialchars($form_data['name']); ?>" required minlength="2" maxlength="50">
            </div>
            <div class="form-group">
                <label class="form-label" for="age">Age</label>
                <input type="number" id="age" name="age" class="form-input"
                       value="<?php echo htmlspecialchars($form_data['age']); ?>" required min="1" max="120">
            </div>
            <div class="form-group">
                <label class="form-label" for="entry_date">Date</label>
                <input type="date" id="entry_date" name="entry_date" class="form-input"
                       value="<?php echo htmlspecialchars($form_data['entry_date']); ?>" required>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: var(--space-lg);">
        <div class="card-header">
            <div class="card-title">🧬 Daily Habits</div>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label" for="sleep_hours">😴 Sleep Hours</label>
                <input type="number" id="sleep_hours" name="sleep_hours" class="form-input"
                       value="<?php echo htmlspecialchars($form_data['sleep_hours']); ?>" required min="0" max="24" step="0.5">
            </div>
            <div class="form-group">
                <label class="form-label" for="study_hours">📚 Study Hours</label>
                <input type="number" id="study_hours" name="study_hours" class="form-input"
                       value="<?php echo htmlspecialchars($form_data['study_hours']); ?>" required min="0" max="24" step="0.5">
            </div>
            <div class="form-group">
                <label class="form-label" for="exercise_minutes">🏃 Exercise (min)</label>
                <input type="number" id="exercise_minutes" name="exercise_minutes" class="form-input"
                       value="<?php echo htmlspecialchars($form_data['exercise_minutes']); ?>" required min="0" max="600">
            </div>
            <div class="form-group">
                <label class="form-label" for="junk_food_count">🍔 Junk Food</label>
                <input type="number" id="junk_food_count" name="junk_food_count" class="form-input"
                       value="<?php echo htmlspecialchars($form_data['junk_food_count']); ?>" required min="0" max="20">
            </div>
            <div class="form-group">
                <label class="form-label" for="water_glasses">💧 Water (glasses)</label>
                <input type="number" id="water_glasses" name="water_glasses" class="form-input"
                       value="<?php echo htmlspecialchars($form_data['water_glasses']); ?>" required min="0" max="30">
            </div>
            <div class="form-group">
                <label class="form-label" for="screen_time">📱 Screen Time (hrs)</label>
                <input type="number" id="screen_time" name="screen_time" class="form-input"
                       value="<?php echo htmlspecialchars($form_data['screen_time']); ?>" required min="0" max="24" step="0.5">
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: var(--space-lg);">
        <div class="card-header">
            <div class="card-title">😊 Mood</div>
        </div>
        <div class="form-group">
            <select id="mood" name="mood" class="form-select" required>
                <?php
                $moods = ['great' => '😄 Great', 'good' => '🙂 Good', 'neutral' => '😐 Neutral', 'tired' => '😴 Tired', 'stressed' => '😰 Stressed'];
                foreach ($moods as $val => $label):
                ?>
                <option value="<?php echo $val; ?>" <?php echo ($form_data['mood'] === $val) ? 'selected' : ''; ?>>
                    <?php echo $label; ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div style="margin-top: var(--space-xl); display: flex; gap: var(--space-md); justify-content: flex-end;">
        <a href="history.php" class="btn btn-secondary">← Back</a>
        <button type="submit" class="btn btn-primary btn-lg">💾 Update Entry</button>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>
