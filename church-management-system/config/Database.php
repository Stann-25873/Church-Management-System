<?php

namespace Config;

class Database
{
    private static $instance = null;
    private $data = [];
    private $dataFile;

    private function __construct()
    {
        $this->dataFile = __DIR__ . '/../data.json';
        $this->loadData();
    }

    private function loadData()
    {
        if (file_exists($this->dataFile)) {
            $json = file_get_contents($this->dataFile);
            $this->data = json_decode($json, true);
        } else {
            $this->data = ['users' => [], 'events' => [], 'sermons' => [], 'offerings' => []];
        }
    }

    private function saveData()
    {
        file_put_contents($this->dataFile, json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this;
    }

    public function select($table, $conditions = [])
    {
        if (!isset($this->data[$table])) {
            return [];
        }

        $results = $this->data[$table];

        foreach ($conditions as $column => $value) {
            $results = array_filter($results, function($item) use ($column, $value) {
                return isset($item[$column]) && $item[$column] == $value;
            });
        }

        return array_values($results);
    }

    public function selectOne($table, $conditions = [])
    {
        $results = $this->select($table, $conditions);
        return count($results) > 0 ? $results[0] : null;
    }

    public function insert($table, $data)
    {
        if (!isset($this->data[$table])) {
            $this->data[$table] = [];
        }

        $maxId = 0;
        foreach ($this->data[$table] as $item) {
            if (isset($item['id']) && $item['id'] > $maxId) {
                $maxId = $item['id'];
            }
        }

        $data['id'] = $maxId + 1;
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        $this->data[$table][] = $data;
        $this->saveData();

        return $data['id'];
    }

    public function update($table, $id, $data)
    {
        if (!isset($this->data[$table])) {
            return false;
        }

        foreach ($this->data[$table] as &$item) {
            if (isset($item['id']) && $item['id'] == $id) {
                $item = array_merge($item, $data);
                $this->saveData();
                return true;
            }
        }

        return false;
    }

    public function delete($table, $id)
    {
        if (!isset($this->data[$table])) {
            return false;
        }

        foreach ($this->data[$table] as $key => $item) {
            if (isset($item['id']) && $item['id'] == $id) {
                unset($this->data[$table][$key]);
                $this->data[$table] = array_values($this->data[$table]);
                $this->saveData();
                return true;
            }
        }

        return false;
    }

    public function query($sql, $params = [])
    {
        // Simple implementation for compatibility
        return null;
    }

    public function fetchAll($sql, $params = [])
    {
        return [];
    }

    public function fetch($sql, $params = [])
    {
        return null;
    }
}
