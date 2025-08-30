<?php
include 'includes/db.php';

function create_slug($string) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    return $slug;
}

$stmt = $pdo->query("SELECT id, name FROM tools WHERE slug IS NULL OR slug = ''");

while ($tool = $stmt->fetch()) {
    $slug = create_slug($tool['name']);

    // Ensure slug is unique
    $check = $pdo->prepare("SELECT COUNT(*) FROM tools WHERE slug = ?");
    $check->execute([$slug]);
    if ($check->fetchColumn() > 0) {
        $slug .= '-' . $tool['id'];
    }

    $update = $pdo->prepare("UPDATE tools SET slug = ? WHERE id = ?");
    $update->execute([$slug, $tool['id']]);
}

echo "✅ Slugs generated successfully.";
?>
