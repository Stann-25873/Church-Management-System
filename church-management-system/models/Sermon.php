<?php

namespace Models;

use Core\Model;

class Sermon extends Model
{
    public function create($data)
    {
        return $this->db->insert('sermons', $data);
    }

    public function getAll()
    {
        $sermons = $this->db->select('sermons');
        $users = $this->db->select('users');
        $userMap = [];
        foreach ($users as $user) {
            $userMap[$user['id']] = $user;
        }

        foreach ($sermons as &$sermon) {
            if (isset($userMap[$sermon['pastor_id']])) {
                $sermon['first_name'] = $userMap[$sermon['pastor_id']]['first_name'];
                $sermon['last_name'] = $userMap[$sermon['pastor_id']]['last_name'];
            }
        }

        usort($sermons, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return $sermons;
    }

    public function findById($id)
    {
        return $this->db->selectOne('sermons', ['id' => $id]);
    }

    public function delete($id)
    {
        return $this->db->delete('sermons', $id);
    }

    public function countByPastor($pastorId)
    {
        $sermons = $this->db->select('sermons', ['pastor_id' => $pastorId]);
        return count($sermons);
    }

    public function update($id, $data)
    {
        return $this->db->update('sermons', $id, $data);
    }
}
