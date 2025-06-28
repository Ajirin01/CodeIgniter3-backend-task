<?php
class Blog_model extends CI_Model {

    public function get_all_posts() {
        return $this->db->get('posts')->result_array();
    }

    public function get_post($id) {
        return $this->db->get_where('posts', ['id' => $id])->row_array();
    }

    public function insert_post($data) {
        return $this->db->insert('posts', $data);
    }

    public function update_post($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('posts', $data);
    }

    public function delete_post($id) {
        return $this->db->delete('posts', ['id' => $id]);
    }

    public function filter_posts($startDate, $endDate, $category)
    {
        $this->db->select('*');
        $this->db->from('posts');

        if (!empty($startDate)) {
            $this->db->where('created_at >=', $startDate);
        }

        if (!empty($endDate)) {
            $this->db->where('created_at <=', $endDate);
        }

        if (!empty($category)) {
            $this->db->where("FIND_IN_SET(" . $this->db->escape($category) . ", categories) !=", 0, FALSE);
        }

        return $this->db->get()->result();
    }


}
