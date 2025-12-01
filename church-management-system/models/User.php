<?php

namespace Models;

use Core\Model;

class User extends Model
{
    public function authenticate($email, $password)
    {
        $user = $this->db->selectOne('users', ['email' => $email]);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    public function create($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        return $this->db->insert('users', $data);
    }

    public function getAllMembers()
    {
        $users = $this->db->select('users', ['role' => 'member']);
        usort($users, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        return $users;
    }

    public function findById($id)
    {
        return $this->db->selectOne('users', ['id' => $id]);
    }

    public function update($id, $data)
    {
        return $this->db->update('users', $id, $data);
    }

    public function delete($id)
    {
        return $this->db->delete('users', $id);
    }

    public function countByRole($role)
    {
        $users = $this->db->select('users', ['role' => $role]);
        return count($users);
    }

    public function getRecentMembers($limit)
    {
        $users = $this->db->select('users', ['role' => 'member']);
        usort($users, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        return array_slice($users, 0, $limit);
    }

    public function getAll()
    {
        return $this->db->select('users');
    }
}
