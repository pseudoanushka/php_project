<?php
/**
 * LifeTrack: Personal Decision Analyzer
 * Core Functions File
 * 
 * Demonstrates:
 * - User-defined functions
 * - Anonymous functions (closures)
 * - String functions (strtoupper, strtolower, ucfirst, strlen)
 * - Associative arrays for scoring
 * - Conditional logic (if-else, switch)
 */

// ============================================================
// SCORING SYSTEM — Associative Arrays
// ============================================================

// Each habit has a weight and scoring rules stored in associative arrays
$scoring_rules = [
    'sleep' => [
        'excellent' => ['min' => 7, 'max' => 9, 'points' => 25],
        'good'      => ['min' => 5, 'max' => 7, 'points' => 15],
        'poor'      => ['min' => 0, 'max' => 5, 'points' => 5],
    ],
    'study' => [
        'excellent' => ['min' => 6, 'max' => 24, 'points' => 25],
        'good'      => ['min' => 3, 'max' => 6, 'points' => 15],
        'poor'      => ['min' => 0, 'max' => 3, 'points' => 5],
    ],
    'exercise' => [
        'excellent' => ['min' => 60, 'max' => 300, 'points' => 20],
        'good'      => ['min' => 30, 'max' => 60, 'points' => 12],
        'poor'      => ['min' => 0, 'max' => 30, 'points' => 3],
    ],
    'junk_food' => [
        'excellent' => ['min' => 0, 'max' => 0, 'points' => 15],
        'good'      => ['min' => 1, 'max' => 2, 'points' => 8],
        'poor'      => ['min' => 3, 'max' => 100, 'points' => 0],
    ],
    'water' => [
        'excellent' => ['min' => 8, 'max' => 100, 'points' => 15],
        'good'      => ['min' => 4, 'max' => 7, 'points' => 8],
        'poor'      => ['min' => 0, 'max' => 3, 'points' => 2],
    ],
];

// ============================================================
// SARCASTIC FEEDBACK — Indexed Array
// ============================================================
$sarcastic_feedback = [
    'sleep_low'     => [
        "You slept %s hours. Are you okay? ☕",
        "Sleep is for the weak? No, sleep is for the smart. You got %s hours.",
        "%s hours of sleep? Your pillow misses you.",
        "Legend says you'll sleep when you're dead. With %s hours, that might be soon. 💀"
    ],
    'sleep_high'    => [
        "%s hours of sleep? Are you a cat? 🐱",
        "Sleeping Beauty called — she wants her record back. %s hours!",
    ],
    'study_high'    => [
        "Studied %s hours? Your brain called, it wants a vacation. 🧠",
        "%s hours of studying! Are you training for a PhD speedrun?",
    ],
    'study_low'     => [
        "Only %s hours of study? Netflix won today, didn't it? 📺",
        "%s hours studying. Bold strategy, let's see if it pays off.",
    ],
    'junk_high'     => [
        "%s junk food items?! Your stomach is filing a complaint. 🍔",
        "You ate junk %s times. Your future self is judging you.",
        "%s servings of junk food — are you speedrunning diabetes?",
    ],
    'exercise_none' => [
        "0 minutes of exercise. Your couch must be really comfortable. 🛋️",
        "Exercise? Never heard of her. — You, probably.",
    ],
    'exercise_great'=> [
        "%s minutes of exercise! Are you training for the Olympics? 🏅",
    ],
    'water_low'     => [
        "Only %s glasses of water? You're basically a raisin. 🍇",
        "%s glasses of water. Camels drink more than you.",
    ],
    'screen_high'   => [
        "%s hours of screen time. Your eyes are filing for divorce. 👀",
        "Screen time: %s hours. Touch grass maybe?",
    ],
    'healthy'       => [
        "Look at you being all responsible! Keep it up! 🌟",
        "Your lifestyle today was *chef's kiss*. Magnificent! ✨",
        "If healthy living was a sport, you'd be MVP today! 🏆",
    ],
    'risky'         => [
        "Not terrible, not great. Like microwaved pizza. 🍕",
        "You're walking on thin ice today. Watch out! 🧊",
        "Today was... okay. Your mom would be mildly concerned.",
    ],
    'chaotic'       => [
        "Complete chaos. But at least you're consistent? 🌪️",
        "Your day was so chaotic, even your planner gave up. 📅",
        "If bad decisions were currency, you'd be rich today. 💰",
    ],
];


// ============================================================
// USER-DEFINED FUNCTION: Calculate Daily Score
// ============================================================

/**
 * Calculates the daily lifestyle score based on habits.
 * Demonstrates: user-defined functions, associative arrays, if-else logic.
 *
 * @param float $sleep_hours     Hours of sleep
 * @param float $study_hours     Hours of study
 * @param int   $exercise_min    Minutes of exercise
 * @param int   $junk_food       Number of junk food servings
 * @param int   $water_glasses   Glasses of water consumed
 * @param float $screen_time     Hours of screen time
 * @return int  Score from 0-100
 */
function calculateDailyScore($sleep_hours, $study_hours, $exercise_min, $junk_food, $water_glasses, $screen_time) {
    global $scoring_rules;
    
    $score = 0; // integer type
    
    // --- Sleep scoring using if-else ---
    if ($sleep_hours >= $scoring_rules['sleep']['excellent']['min'] && $sleep_hours <= $scoring_rules['sleep']['excellent']['max']) {
        $score += $scoring_rules['sleep']['excellent']['points'];
    } elseif ($sleep_hours >= $scoring_rules['sleep']['good']['min']) {
        $score += $scoring_rules['sleep']['good']['points'];
    } else {
        $score += $scoring_rules['sleep']['poor']['points'];
    }
    
    // --- Study scoring ---
    if ($study_hours >= $scoring_rules['study']['excellent']['min']) {
        $score += $scoring_rules['study']['excellent']['points'];
    } elseif ($study_hours >= $scoring_rules['study']['good']['min']) {
        $score += $scoring_rules['study']['good']['points'];
    } else {
        $score += $scoring_rules['study']['poor']['points'];
    }
    
    // --- Exercise scoring ---
    if ($exercise_min >= $scoring_rules['exercise']['excellent']['min']) {
        $score += $scoring_rules['exercise']['excellent']['points'];
    } elseif ($exercise_min >= $scoring_rules['exercise']['good']['min']) {
        $score += $scoring_rules['exercise']['good']['points'];
    } else {
        $score += $scoring_rules['exercise']['poor']['points'];
    }
    
    // --- Junk food scoring (lower is better) ---
    if ($junk_food <= $scoring_rules['junk_food']['excellent']['max']) {
        $score += $scoring_rules['junk_food']['excellent']['points'];
    } elseif ($junk_food <= $scoring_rules['junk_food']['good']['max']) {
        $score += $scoring_rules['junk_food']['good']['points'];
    } else {
        $score += $scoring_rules['junk_food']['poor']['points'];
    }
    
    // --- Water scoring ---
    if ($water_glasses >= $scoring_rules['water']['excellent']['min']) {
        $score += $scoring_rules['water']['excellent']['points'];
    } elseif ($water_glasses >= $scoring_rules['water']['good']['min']) {
        $score += $scoring_rules['water']['good']['points'];
    } else {
        $score += $scoring_rules['water']['poor']['points'];
    }
    
    // Screen time penalty (not in scoring_rules, direct calc)
    if ($screen_time > 8) {
        $score -= 10;
    } elseif ($screen_time > 5) {
        $score -= 5;
    }
    
    // Clamp score between 0 and 100
    return max(0, min(100, $score));
}


// ============================================================
// USER-DEFINED FUNCTION: Categorize Behavior (switch statement)
// ============================================================

/**
 * Categorizes the user's day using a switch statement.
 * 
 * @param int $score  The daily score (0-100)
 * @return string     Category: Healthy, Risky, or Chaotic
 */
function categorizeDay($score) {
    // Determine tier first
    if ($score >= 70) {
        $tier = 'high';
    } elseif ($score >= 40) {
        $tier = 'medium';
    } else {
        $tier = 'low';
    }
    
    // Switch statement to assign category
    switch ($tier) {
        case 'high':
            $category = 'Healthy';
            break;
        case 'medium':
            $category = 'Risky';
            break;
        case 'low':
            $category = 'Chaotic';
            break;
        default:
            $category = 'Unknown';
            break;
    }
    
    return $category;
}


// ============================================================
// USER-DEFINED FUNCTION: Generate Summary (String Functions)
// ============================================================

/**
 * Generates a formatted summary string using PHP string functions.
 * Demonstrates: strtoupper, strtolower, ucfirst, strlen, substr, str_repeat
 *
 * @param string $name      User name
 * @param string $category  Category (Healthy/Risky/Chaotic)
 * @param int    $score     Daily score
 * @param string $date      Entry date
 * @return string           Formatted summary
 */
function generateSummary($name, $category, $score, $date) {
    // ucfirst — capitalize first letter of name
    $formatted_name = ucfirst(strtolower($name));
    
    // strtoupper — category in uppercase for emphasis
    $formatted_category = strtoupper($category);
    
    // Build the summary string
    $summary = "Hey " . $formatted_name . "! Your lifestyle on " . $date . " was " . $formatted_category . " (Score: " . $score . "/100).";
    
    // strlen — show summary length
    $summary_length = strlen($summary);
    
    // Add decorative border using str_repeat
    $border = str_repeat("═", min($summary_length, 60));
    
    return $summary;
}


// ============================================================
// ANONYMOUS FUNCTION: Quick Formatting Transformations
// ============================================================

/**
 * Anonymous function to format a score into a colored badge string.
 */
$formatScoreBadge = function($score, $category) {
    $emoji = '';
    switch (strtolower($category)) {
        case 'healthy': $emoji = '🌟'; break;
        case 'risky':   $emoji = '⚠️'; break;
        case 'chaotic': $emoji = '🌪️'; break;
        default:        $emoji = '❓'; break;
    }
    return $emoji . ' ' . strtoupper($category) . ' — ' . $score . '/100';
};

/**
 * Anonymous function to format time display.
 */
$formatTimeAgo = function($datetime) {
    $now = new DateTime();
    $then = new DateTime($datetime);
    $diff = $now->diff($then);
    
    if ($diff->days == 0) return "Today";
    if ($diff->days == 1) return "Yesterday";
    if ($diff->days < 7) return $diff->days . " days ago";
    if ($diff->days < 30) return ceil($diff->days / 7) . " weeks ago";
    return $then->format('M j, Y');
};


// ============================================================
// USER-DEFINED FUNCTION: Generate Fun Feedback
// ============================================================

/**
 * Generates sarcastic/fun feedback based on habits.
 * 
 * @param float  $sleep      Hours of sleep
 * @param float  $study      Hours of study
 * @param int    $exercise   Minutes of exercise
 * @param int    $junk       Junk food count
 * @param int    $water      Water glasses
 * @param float  $screen     Screen time hours
 * @param string $category   Day category
 * @return string            Sarcastic feedback string
 */
function generateFunFeedback($sleep, $study, $exercise, $junk, $water, $screen, $category) {
    global $sarcastic_feedback;
    
    $feedbacks = []; // array to collect applicable feedback
    
    // Check each habit for funny feedback using if-else
    if ($sleep < 5) {
        $templates = $sarcastic_feedback['sleep_low'];
        $feedbacks[] = sprintf($templates[array_rand($templates)], $sleep);
    } elseif ($sleep > 10) {
        $templates = $sarcastic_feedback['sleep_high'];
        $feedbacks[] = sprintf($templates[array_rand($templates)], $sleep);
    }
    
    if ($study > 8) {
        $templates = $sarcastic_feedback['study_high'];
        $feedbacks[] = sprintf($templates[array_rand($templates)], $study);
    } elseif ($study < 1) {
        $templates = $sarcastic_feedback['study_low'];
        $feedbacks[] = sprintf($templates[array_rand($templates)], $study);
    }
    
    if ($junk >= 3) {
        $templates = $sarcastic_feedback['junk_high'];
        $feedbacks[] = sprintf($templates[array_rand($templates)], $junk);
    }
    
    if ($exercise == 0) {
        $templates = $sarcastic_feedback['exercise_none'];
        $feedbacks[] = $templates[array_rand($templates)];
    } elseif ($exercise > 90) {
        $templates = $sarcastic_feedback['exercise_great'];
        $feedbacks[] = sprintf($templates[array_rand($templates)], $exercise);
    }
    
    if ($water < 4) {
        $templates = $sarcastic_feedback['water_low'];
        $feedbacks[] = sprintf($templates[array_rand($templates)], $water);
    }
    
    if ($screen > 6) {
        $templates = $sarcastic_feedback['screen_high'];
        $feedbacks[] = sprintf($templates[array_rand($templates)], $screen);
    }
    
    // Add category-based feedback
    $cat_key = strtolower($category);
    if (isset($sarcastic_feedback[$cat_key])) {
        $templates = $sarcastic_feedback[$cat_key];
        $feedbacks[] = $templates[array_rand($templates)];
    }
    
    // Return 1-3 random feedbacks joined
    shuffle($feedbacks);
    $selected = array_slice($feedbacks, 0, min(3, count($feedbacks)));
    
    return implode(" | ", $selected);
}


// ============================================================
// USER-DEFINED FUNCTION: Validate Form Input
// ============================================================

/**
 * Validates the habit form inputs.
 * 
 * @param array $data  POST data
 * @return array       ['valid' => bool, 'errors' => array, 'cleaned' => array]
 */
function validateEntryForm($data) {
    $errors = [];
    $cleaned = [];
    
    // Validate name (string)
    $name = trim($data['name'] ?? '');
    if (strlen($name) < 2 || strlen($name) > 50) {
        $errors[] = "Name must be between 2 and 50 characters.";
    }
    $cleaned['name'] = htmlspecialchars($name);
    
    // Validate age (integer)
    $age = filter_var($data['age'] ?? 0, FILTER_VALIDATE_INT);
    if ($age === false || $age < 1 || $age > 120) {
        $errors[] = "Age must be a valid number between 1 and 120.";
    }
    $cleaned['age'] = (int)$age;
    
    // Validate sleep hours (float)
    $sleep = filter_var($data['sleep_hours'] ?? 0, FILTER_VALIDATE_FLOAT);
    if ($sleep === false || $sleep < 0 || $sleep > 24) {
        $errors[] = "Sleep hours must be between 0 and 24.";
    }
    $cleaned['sleep_hours'] = round((float)$sleep, 1);
    
    // Validate study hours (float)
    $study = filter_var($data['study_hours'] ?? 0, FILTER_VALIDATE_FLOAT);
    if ($study === false || $study < 0 || $study > 24) {
        $errors[] = "Study hours must be between 0 and 24.";
    }
    $cleaned['study_hours'] = round((float)$study, 1);
    
    // Validate exercise (integer)
    $exercise = filter_var($data['exercise_minutes'] ?? 0, FILTER_VALIDATE_INT);
    if ($exercise === false || $exercise < 0 || $exercise > 600) {
        $errors[] = "Exercise minutes must be between 0 and 600.";
    }
    $cleaned['exercise_minutes'] = (int)$exercise;
    
    // Validate junk food count (integer)
    $junk = filter_var($data['junk_food_count'] ?? 0, FILTER_VALIDATE_INT);
    if ($junk === false || $junk < 0 || $junk > 20) {
        $errors[] = "Junk food count must be between 0 and 20.";
    }
    $cleaned['junk_food_count'] = (int)$junk;
    
    // Validate water (integer)
    $water = filter_var($data['water_glasses'] ?? 0, FILTER_VALIDATE_INT);
    if ($water === false || $water < 0 || $water > 30) {
        $errors[] = "Water glasses must be between 0 and 30.";
    }
    $cleaned['water_glasses'] = (int)$water;
    
    // Validate screen time (float)
    $screen = filter_var($data['screen_time'] ?? 0, FILTER_VALIDATE_FLOAT);
    if ($screen === false || $screen < 0 || $screen > 24) {
        $errors[] = "Screen time must be between 0 and 24 hours.";
    }
    $cleaned['screen_time'] = round((float)$screen, 1);
    
    // Validate mood (string)
    $valid_moods = ['great', 'good', 'neutral', 'tired', 'stressed'];
    $mood = strtolower(trim($data['mood'] ?? 'neutral'));
    if (!in_array($mood, $valid_moods)) {
        $errors[] = "Please select a valid mood.";
    }
    $cleaned['mood'] = $mood;
    
    // Validate date
    $date = $data['entry_date'] ?? date('Y-m-d');
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $errors[] = "Invalid date format.";
    }
    $cleaned['entry_date'] = $date;
    
    return [
        'valid'   => empty($errors),
        'errors'  => $errors,
        'cleaned' => $cleaned
    ];
}


// ============================================================
// CRUD HELPER FUNCTIONS
// ============================================================

/**
 * CREATE: Insert a new entry into the database.
 */
function createEntry($pdo, $data) {
    // Get or create user
    $stmt = $pdo->prepare("SELECT id FROM users WHERE name = ?");
    $stmt->execute([$data['name']]);
    $user = $stmt->fetch();
    
    if (!$user) {
        $stmt = $pdo->prepare("INSERT INTO users (name, age) VALUES (?, ?)");
        $stmt->execute([$data['name'], $data['age']]);
        $user_id = $pdo->lastInsertId();
    } else {
        $user_id = $user['id'];
        // Update age
        $stmt = $pdo->prepare("UPDATE users SET age = ? WHERE id = ?");
        $stmt->execute([$data['age'], $user_id]);
    }
    
    // Calculate score and category
    $score = calculateDailyScore(
        $data['sleep_hours'], $data['study_hours'], $data['exercise_minutes'],
        $data['junk_food_count'], $data['water_glasses'], $data['screen_time']
    );
    $category = categorizeDay($score);
    $feedback = generateFunFeedback(
        $data['sleep_hours'], $data['study_hours'], $data['exercise_minutes'],
        $data['junk_food_count'], $data['water_glasses'], $data['screen_time'], $category
    );
    
    $stmt = $pdo->prepare("
        INSERT INTO entries (user_id, sleep_hours, study_hours, exercise_minutes, 
                            junk_food_count, water_glasses, screen_time, mood, 
                            score, category, fun_feedback, entry_date)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $user_id, $data['sleep_hours'], $data['study_hours'], $data['exercise_minutes'],
        $data['junk_food_count'], $data['water_glasses'], $data['screen_time'], $data['mood'],
        $score, $category, $feedback, $data['entry_date']
    ]);
    
    return [
        'id'       => $pdo->lastInsertId(),
        'score'    => $score,
        'category' => $category,
        'feedback' => $feedback
    ];
}

/**
 * READ: Get all entries with user info.
 */
function getAllEntries($pdo, $limit = 50) {
    $stmt = $pdo->prepare("
        SELECT e.*, u.name, u.age 
        FROM entries e 
        JOIN users u ON e.user_id = u.id 
        ORDER BY e.entry_date DESC, e.created_at DESC 
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

/**
 * READ: Get a single entry by ID.
 */
function getEntryById($pdo, $id) {
    $stmt = $pdo->prepare("
        SELECT e.*, u.name, u.age 
        FROM entries e 
        JOIN users u ON e.user_id = u.id 
        WHERE e.id = ?
    ");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * UPDATE: Update an existing entry.
 */
function updateEntry($pdo, $id, $data) {
    // Recalculate score and category
    $score = calculateDailyScore(
        $data['sleep_hours'], $data['study_hours'], $data['exercise_minutes'],
        $data['junk_food_count'], $data['water_glasses'], $data['screen_time']
    );
    $category = categorizeDay($score);
    $feedback = generateFunFeedback(
        $data['sleep_hours'], $data['study_hours'], $data['exercise_minutes'],
        $data['junk_food_count'], $data['water_glasses'], $data['screen_time'], $category
    );
    
    // Update user info
    $entry = getEntryById($pdo, $id);
    if ($entry) {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, age = ? WHERE id = ?");
        $stmt->execute([$data['name'], $data['age'], $entry['user_id']]);
    }
    
    $stmt = $pdo->prepare("
        UPDATE entries SET 
            sleep_hours = ?, study_hours = ?, exercise_minutes = ?,
            junk_food_count = ?, water_glasses = ?, screen_time = ?,
            mood = ?, score = ?, category = ?, fun_feedback = ?, entry_date = ?
        WHERE id = ?
    ");
    
    $stmt->execute([
        $data['sleep_hours'], $data['study_hours'], $data['exercise_minutes'],
        $data['junk_food_count'], $data['water_glasses'], $data['screen_time'],
        $data['mood'], $score, $category, $feedback, $data['entry_date'], $id
    ]);
    
    return ['score' => $score, 'category' => $category, 'feedback' => $feedback];
}

/**
 * DELETE: Remove an entry.
 */
function deleteEntry($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM entries WHERE id = ?");
    return $stmt->execute([$id]);
}

/**
 * Get score trend data for chart.
 */
function getScoreTrend($pdo, $limit = 14) {
    $stmt = $pdo->prepare("
        SELECT entry_date, score, category 
        FROM entries 
        ORDER BY entry_date DESC 
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    $results = $stmt->fetchAll();
    return array_reverse($results);
}

/**
 * Get category distribution.
 */
function getCategoryDistribution($pdo) {
    $stmt = $pdo->query("
        SELECT category, COUNT(*) as count 
        FROM entries 
        GROUP BY category
    ");
    return $stmt->fetchAll();
}

/**
 * Get today's entry if exists.
 */
function getTodayEntry($pdo) {
    $today = date('Y-m-d');
    $stmt = $pdo->prepare("
        SELECT e.*, u.name, u.age 
        FROM entries e 
        JOIN users u ON e.user_id = u.id 
        WHERE e.entry_date = ?
        ORDER BY e.created_at DESC 
        LIMIT 1
    ");
    $stmt->execute([$today]);
    return $stmt->fetch();
}

/**
 * Get category CSS class name.
 */
function getCategoryClass($category) {
    switch (strtolower($category)) {
        case 'healthy': return 'category-healthy';
        case 'risky':   return 'category-risky';
        case 'chaotic': return 'category-chaotic';
        default:        return 'category-unknown';
    }
}

/**
 * Get category icon.
 */
function getCategoryIcon($category) {
    switch (strtolower($category)) {
        case 'healthy': return '🌟';
        case 'risky':   return '⚠️';
        case 'chaotic': return '🌪️';
        default:        return '❓';
    }
}

/**
 * Get mood emoji.
 */
function getMoodEmoji($mood) {
    $moods = [
        'great'    => '😄',
        'good'     => '🙂',
        'neutral'  => '😐',
        'tired'    => '😴',
        'stressed' => '😰'
    ];
    return $moods[strtolower($mood)] ?? '😐';
}
