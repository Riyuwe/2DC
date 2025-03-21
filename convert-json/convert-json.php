<?php
function transform_json_structure($data) {
    $tree = [];
    $lookup = [];

    // Create an associative array for quick lookup
    foreach ($data as $item) {
        $id = $item['id'];
        $lookup[$id] = ['id' => $id, 'level' => $item['attributes']['level'], 'children' => []];
    }

    // Build the tree structure
    foreach ($lookup as &$item) {
        $level = $item['level'];
        foreach ($lookup as &$potentialParent) {
            if ($potentialParent['level'] == $level - 1) {
                $potentialParent['children'][] = &$item;
                break;
            }
        }
    }

    // Extract only the top-level parents
    foreach ($lookup as $item) {
        if ($item['level'] == 1) {
            $tree[] = $item;
        }
    }

    return $tree;
}

// Example Input
$input_json = '[{"id":1,"attributes":{"level":1}},{"id":2,"attributes":{"level":2}},{"id":3,"attributes":{"level":3}},{"id":4,"attributes":{"level":2}},{"id":5,"attributes":{"level":1}},{"id":6,"attributes":{"level":2}},{"id":7,"attributes":{"level":3}}]';

$input_data = json_decode($input_json, true);
$output_data = transform_json_structure($input_data);

// Output result
echo json_encode($output_data, JSON_PRETTY_PRINT);

?>