<?php
/**
 * LifeTrack: Personal Decision Analyzer
 * DELETE ENTRY — Remove an entry (CRUD: Delete)
 * 
 * Demonstrates:
 * - GET parameter handling
 * - Database DELETE operation
 * - Redirect after action
 */

require_once 'db.php';
require_once 'functions.php';

// ============================================================
// GET ENTRY ID AND DELETE
// ============================================================
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    // Verify entry exists first
    $entry = getEntryById($pdo, $id);
    
    if ($entry) {
        // Delete the entry (CRUD: Delete)
        deleteEntry($pdo, $id);
    }
}

// Redirect back to history page
header('Location: history.php');
exit;
