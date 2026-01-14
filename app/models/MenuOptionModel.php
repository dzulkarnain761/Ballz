<?php

class MenuOptionModel
{
    use Database;

    public function getGroups($item_id = null)
    {
        $query = "SELECT g.*, i.name as item_name 
                 FROM menu_option_groups g 
                 JOIN menu_items i ON g.menu_item_id = i.id";
        if ($item_id) {
            $query .= " WHERE g.menu_item_id = ? ORDER BY g.sort_order ASC";
            $this->query($query);
            $this->bind("i", $item_id);
            return $this->resultSet();
        }
        $query .= " ORDER BY i.name ASC, g.sort_order ASC";
        $this->query($query);
        return $this->resultSet();
    }

    public function getGroup($id)
    {
        $this->query("SELECT * FROM menu_option_groups WHERE id = ?");
        $this->bind("i", $id);
        $res = $this->resultSet();
        return $res ? $res[0] : null;
    }

    public function createGroup($data)
    {
        $query = "INSERT INTO menu_option_groups (menu_item_id, name, is_required, min_select, max_select, sort_order) 
                 VALUES (?, ?, ?, ?, ?, ?)";
        $this->query($query);
        $this->bind("i", $data['menu_item_id']);
        $this->bind("s", $data['name']);
        $this->bind("i", $data['is_required'] ? 1 : 0);
        $this->bind("i", $data['min_select']);
        $this->bind("i", $data['max_select']);
        $this->bind("i", $data['sort_order']);
        return $this->execute();
    }

    public function updateGroup($id, $data)
    {
        $query = "UPDATE menu_option_groups SET menu_item_id = ?, name = ?, is_required = ?, min_select = ?, max_select = ?, sort_order = ? 
                 WHERE id = ?";
        $this->query($query);
        $this->bind("i", $data['menu_item_id']);
        $this->bind("s", $data['name']);
        $this->bind("i", $data['is_required'] ? 1 : 0);
        $this->bind("i", $data['min_select']);
        $this->bind("i", $data['max_select']);
        $this->bind("i", $data['sort_order']);
        $this->bind("i", $id);
        return $this->execute();
    }

    public function deleteGroup($id)
    {
        return $this->query("DELETE FROM menu_option_groups WHERE id = ?", [$id]);
    }

    // === OPTIONS ===
    public function getOptions($group_id)
    {
        $this->query("SELECT * FROM menu_options WHERE option_group_id = ? ORDER BY sort_order ASC");
        $this->bind("i", $group_id);
        return $this->resultSet();
    }

    public function getOption($id)
    {
        $this->query("SELECT * FROM menu_options WHERE id = ?");
        $this->bind("i", $id);
        $res = $this->resultSet();
        return $res ? $res[0] : null;
    }

    public function createOption($data)
    {
        $query = "INSERT INTO menu_options (option_group_id, name, price_modifier, is_default, sort_order) 
                 VALUES (?, ?, ?, ?, ?)";
        $this->query($query );
        $this->bind("i", $data['option_group_id']);
        $this->bind("s", $data['name']);
        $this->bind("d", $data['price_modifier']);
        $this->bind("i", $data['is_default'] ? 1 : 0);
        $this->bind("i", $data['sort_order']);
        return $this->execute();
    }

    public function updateOption($id, $data)
    {
        $query = "UPDATE menu_options SET name = ?, price_modifier = ?, is_default = ?, sort_order = ? 
                 WHERE id = ?";
        $this->query($query);
        $this->bind("s", $data['name']);
        $this->bind("d", $data['price_modifier']);
        $this->bind("i", $data['is_default'] ? 1 : 0);
        $this->bind("i", $data['sort_order']);
        $this->bind("i", $id);
        return $this->execute();
    }

    public function deleteOption($id)
    {
        return $this->query("DELETE FROM menu_options WHERE id = ?", [$id]);
    }
}
