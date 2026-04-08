<?php
/**
 * LifeTrack: Personal Decision Analyzer
 * ADD ENTRY — Form Handling (POST method)
 * 
 * Demonstrates:
 * - HTML form with various input types
 * - PHP POST form handling
 * - Input validation
 * - Database INSERT (CRUD: Create)
 * - Redirect after successful submission
 */

require_once 'db.php';
require_once 'functions.php';

// ============================================================
// HANDLE FORM SUBMISSION (POST method)
// ============================================================
$errors = [];
$success = false;
$result = null;
$form_data = [
    'name'             => 'Guest User',
    'age'              => 20,
    'sleep_hours'      => '',
    'study_hours'      => '',
    'exercise_minutes' => '',
    'junk_food_count'  => '',
    'water_glasses'    => '',
    'screen_time'      => '',
    'mood'             => 'neutral',
    'entry_date'       => date('Y-m-d'),
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form input using our validation function
    $validation = validateEntryForm($_POST);
    
    if ($validation['valid']) {
        // Create new entry (CRUD: Create)
        $result = createEntry($pdo, $validation['cleaned']);
        $success = true;
        // Keep form data for display
        $form_data = $validation['cleaned'];
    } else {
        $errors = $validation['errors'];
        // Preserve submitted values
        $form_data = array_merge($form_data, $_POST);
    }
}

$page_title = "Log Daily Habits";
require_once 'includes/header.php';
?>

<div class="page-header">
    <h1>➕ Log Your Daily Habits</h1>
    <p>Enter your habits for the day and discover your lifestyle category.</p>
</div>

<!-- ============================================ -->
<!-- SUCCESS MESSAGE                              -->
<!-- ============================================ -->
<?php if ($success && $result): ?>
<div class="alert alert-success animate-slide">
    ✅ Entry saved successfully! Your score: <strong><?php echo $result['score']; ?>/100</strong>
    — Category: <strong><?php echo strtoupper($result['category']); ?></strong>
</div>

<!-- Show result banner -->
<div class="category-banner <?php echo strtolower($result['category']); ?> animate-slide">
    <h2>
        <?php echo getCategoryIcon($result['category']); ?>
        <?php echo generateSummary($form_data['name'], $result['category'], $result['score'], $form_data['entry_date']); ?>
    </h2>
    
    <!-- Scorecard image link -->
    <div class="scorecard-wrap" style="margin-top: var(--space-md);">
        <a href="scorecard.php?id=<?php echo $result['id']; ?>" target="_blank" class="btn btn-secondary btn-sm">
            🎨 View Score Card Image
        </a>
    </div>
    
    <?php if (!empty($result['feedback'])): ?>
    <div class="fun-feedback" style="margin-top: var(--space-lg); text-align: left;">
        <div class="label">🎭 Fun Mode Says...</div>
        <?php echo htmlspecialchars($result['feedback']); ?>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- ============================================ -->
<!-- ERROR MESSAGES                               -->
<!-- ============================================ -->
<?php if (!empty($errors)): ?>
<div class="alert alert-error animate-slide">
    ❌ Please fix the following:
    <ul style="margin: 8px 0 0 16px; list-style: disc;">
        <?php 
        // while loop — iterate through errors
        $err_index = 0;
        while ($err_index < count($errors)) {
            echo '<li>' . htmlspecialchars($errors[$err_index]) . '</li>';
            $err_index++;
        }
        ?>
    </ul>
</div>
<?php endif; ?>

<!-- ============================================ -->
<!-- HABIT ENTRY FORM                             -->
<!-- ============================================ -->
<form method="POST" action="add_entry.php" id="habit-form">
    <div class="card">
        <div class="card-header">
            <div class="card-title">👤 Personal Info</div>
        </div>
        
        <div class="form-grid">
            <!-- Name (string input) -->
            <div class="form-group">
                <label class="form-label" for="name">Your Name</label>
                <div class="input-icon-wrapper">
                    <span class="icon">👤</span>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           class="form-input" 
                           placeholder="Enter your name"
                           value="<?php echo htmlspecialchars($form_data['name']); ?>"
                           required
                           minlength="2"
                           maxlength="50">
                </div>
            </div>
            
            <!-- Age (integer input) -->
            <div class="form-group">
                <label class="form-label" for="age">Age</label>
                <div class="input-icon-wrapper">
                    <span class="icon">🎂</span>
                    <input type="number" 
                           id="age" 
                           name="age" 
                           class="form-input" 
                           placeholder="Your age"
                           value="<?php echo htmlspecialchars($form_data['age']); ?>"
                           required
                           min="1" 
                           max="120">
                </div>
            </div>
            
            <!-- Date -->
            <div class="form-group">
                <label class="form-label" for="entry_date">Date</label>
                <div class="input-icon-wrapper">
                    <span class="icon">📅</span>
                    <input type="date" 
                           id="entry_date" 
                           name="entry_date" 
                           class="form-input"
                           value="<?php echo htmlspecialchars($form_data['entry_date']); ?>"
                           required>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: var(--space-lg);">
        <div class="card-header">
            <div class="card-title">🧬 Daily Habits</div>
            <span class="card-subtitle">Rate your day honestly!</span>
        </div>
        
        <div class="form-grid">
            <!-- Sleep Hours (float) -->
            <div class="form-group">
                <label class="form-label" for="sleep_hours">😴 Sleep Hours</label>
                <input type="number" 
                       id="sleep_hours" 
                       name="sleep_hours" 
                       class="form-input"
                       placeholder="e.g. 7.5"
                       value="<?php echo htmlspecialchars($form_data['sleep_hours']); ?>"
                       required
                       min="0" max="24" step="0.5">
                <span class="form-hint">7-9 hours recommended</span>
            </div>
            
            <!-- Study Hours (float) -->
            <div class="form-group">
                <label class="form-label" for="study_hours">📚 Study / Work Hours</label>
                <input type="number" 
                       id="study_hours" 
                       name="study_hours" 
                       class="form-input"
                       placeholder="e.g. 4"
                       value="<?php echo htmlspecialchars($form_data['study_hours']); ?>"
                       required
                       min="0" max="24" step="0.5">
                <span class="form-hint">Productive study/work time</span>
            </div>
            
            <!-- Exercise Minutes (integer) -->
            <div class="form-group">
                <label class="form-label" for="exercise_minutes">🏃 Exercise (minutes)</label>
                <input type="number" 
                       id="exercise_minutes" 
                       name="exercise_minutes" 
                       class="form-input"
                       placeholder="e.g. 30"
                       value="<?php echo htmlspecialchars($form_data['exercise_minutes']); ?>"
                       required
                       min="0" max="600">
                <span class="form-hint">Any physical activity</span>
            </div>
            
            <!-- Junk Food Count (integer) -->
            <div class="form-group">
                <label class="form-label" for="junk_food_count">🍔 Junk Food Servings</label>
                <input type="number" 
                       id="junk_food_count" 
                       name="junk_food_count" 
                       class="form-input"
                       placeholder="e.g. 1"
                       value="<?php echo htmlspecialchars($form_data['junk_food_count']); ?>"
                       required
                       min="0" max="20">
                <span class="form-hint">Fast food, chips, candy, etc.</span>
            </div>
            
            <!-- Water Glasses (integer) -->
            <div class="form-group">
                <label class="form-label" for="water_glasses">💧 Water (glasses)</label>
                <input type="number" 
                       id="water_glasses" 
                       name="water_glasses" 
                       class="form-input"
                       placeholder="e.g. 8"
                       value="<?php echo htmlspecialchars($form_data['water_glasses']); ?>"
                       required
                       min="0" max="30">
                <span class="form-hint">8+ glasses recommended</span>
            </div>
            
            <!-- Screen Time (float) -->
            <div class="form-group">
                <label class="form-label" for="screen_time">📱 Screen Time (hours)</label>
                <input type="number" 
                       id="screen_time" 
                       name="screen_time" 
                       class="form-input"
                       placeholder="e.g. 3"
                       value="<?php echo htmlspecialchars($form_data['screen_time']); ?>"
                       required
                       min="0" max="24" step="0.5">
                <span class="form-hint">Non-productive screen time</span>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: var(--space-lg);">
        <div class="card-header">
            <div class="card-title">😊 Mood Check</div>
        </div>
        
        <div class="form-group">
            <label class="form-label" for="mood">How are you feeling today?</label>
            <select id="mood" name="mood" class="form-select" required>
                <?php
                // Array of moods — using foreach loop
                $moods = [
                    'great'    => '😄 Great — On top of the world!',
                    'good'     => '🙂 Good — Pretty solid day',
                    'neutral'  => '😐 Neutral — Just another day',
                    'tired'    => '😴 Tired — Running on fumes',
                    'stressed' => '😰 Stressed — Need a break',
                ];
                foreach ($moods as $value => $label):
                ?>
                <option value="<?php echo $value; ?>" 
                        <?php echo ($form_data['mood'] === $value) ? 'selected' : ''; ?>>
                    <?php echo $label; ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Submit -->
    <div style="margin-top: var(--space-xl); display: flex; gap: var(--space-md); justify-content: flex-end;">
        <a href="index.php" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-success btn-lg">
            🧬 Analyze My Day
        </button>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>
