<?php

namespace Models;

use Core\Model;

class Offering extends Model
{
    public function create($data)
    {
        return $this->db->insert('offerings', $data);
    }

    public function getAll()
    {
        $offerings = $this->db->select('offerings');
        $users = $this->db->select('users');
        $userMap = [];
        foreach ($users as $user) {
            $userMap[$user['id']] = $user;
        }

        foreach ($offerings as &$offering) {
            if (isset($userMap[$offering['member_id']])) {
                $offering['first_name'] = $userMap[$offering['member_id']]['first_name'];
                $offering['last_name'] = $userMap[$offering['member_id']]['last_name'];
                $offering['email'] = $userMap[$offering['member_id']]['email'];
            }
        }

        usort($offerings, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return $offerings;
    }

    public function findById($id)
    {
        return $this->db->selectOne('offerings', ['id' => $id]);
    }

    public function delete($id)
    {
        return $this->db->delete('offerings', $id);
    }

    public function getTotalOfferings()
    {
        $offerings = $this->db->select('offerings');
        $total = 0;
        foreach ($offerings as $offering) {
            $total += $offering['amount'];
        }
        return $total;
    }

    public function sumAll()
    {
        return $this->getTotalOfferings();
    }

    public function getOfferingsByMember($memberId)
    {
        return $this->db->select('offerings', ['member_id' => $memberId]);
    }

    public function getRecent($limit)
    {
        $offerings = $this->getAll();
        return array_slice($offerings, 0, $limit);
    }

    public function sumByMember($memberId)
    {
        $offerings = $this->getOfferingsByMember($memberId);
        $total = 0;
        foreach ($offerings as $offering) {
            $total += $offering['amount'];
        }
        return $total;
    }

    public function update($id, $data)
    {
        return $this->db->update('offerings', $id, $data);
    }
}
