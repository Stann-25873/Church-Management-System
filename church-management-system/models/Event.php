<?php

namespace Models;

use Core\Model;

class Event extends Model
{
    public function create($data)
    {
        return $this->db->insert('events', $data);
    }

    public function getAll()
    {
        $events = $this->db->select('events');
        $users = $this->db->select('users');
        $userMap = [];
        foreach ($users as $user) {
            $userMap[$user['id']] = $user;
        }

        foreach ($events as &$event) {
            if (isset($userMap[$event['created_by']])) {
                $event['first_name'] = $userMap[$event['created_by']]['first_name'];
                $event['last_name'] = $userMap[$event['created_by']]['last_name'];
            }
        }

        usort($events, function($a, $b) {
            return strtotime($b['event_date']) - strtotime($a['event_date']);
        });

        return $events;
    }

    public function findById($id)
    {
        return $this->db->selectOne('events', ['id' => $id]);
    }

    public function update($id, $data)
    {
        return $this->db->update('events', $id, $data);
    }

    public function delete($id)
    {
        return $this->db->delete('events', $id);
    }

    public function countUpcoming()
    {
        $events = $this->db->select('events');
        $today = date('Y-m-d');
        $count = 0;
        foreach ($events as $event) {
            if ($event['event_date'] >= $today) {
                $count++;
            }
        }
        return $count;
    }
}
