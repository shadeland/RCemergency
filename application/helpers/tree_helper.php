<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function display_child_nodes($parent_id, $level)
{
    global $data, $index;
    $parent_id = $parent_id === 0 ? 0 : $parent_id;
    if (isset($index[$parent_id])) {
        foreach ($index[$parent_id] as $id) {
            echo str_repeat("-", $level) . $data[$id]["name"] . "\n";
            display_child_nodes($id, $level + 1);
        }
    }
}
?>