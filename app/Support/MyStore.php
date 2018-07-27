<?php

namespace App\Support;

use Illuminate\Support\ServiceProvider;
use Curl;
use anlutro\LaravelSettings\SettingStore;
use anlutro\LaravelSettings\ArrayUtil;
use anlutro\LaravelSettings\DatabaseSettingStore;
use Illuminate\Database\Connection;

class MyStore extends DatabaseSettingStore
{
    public function __construct(Connection $connection, $table = null, $keyColumn = null, $valueColumn = null)
    {
        $this->connection = $connection;
        $this->table = $table ?: 'settings';
        $this->keyColumn = $keyColumn ?: 'key';
        $this->valueColumn = $valueColumn ?: 'value';
    }
    protected function write(array $data)
    {
        $keysQuery = $this->newQuery();
        // "lists" was removed in Laravel 5.3, at which point
        // "pluck" should provide the same functionality.
        $method = !method_exists($keysQuery, 'lists') ? 'pluck' : 'lists';
        $keys = $keysQuery->$method($this->keyColumn);
        $insertData = array_dot($data);
        $updateData = array();
        $deleteKeys = array();
        foreach ($keys as $key) {
            if (isset($insertData[$key])) {
                $updateData[$key] = $insertData[$key];
            } else {
                $deleteKeys[] = $key;
            }
            unset($insertData[$key]);
        }
        print_r($insertData);
        foreach ($updateData as $key => $value) {
            if (is_bool($value) === true) {
                $value = json_encode($value);
            }
            $this->newQuery()
                ->where($this->keyColumn, '=', $key)
                ->update(array($this->valueColumn => $value));
        }
        if ($insertData) {
            $this->newQuery(true)
                ->insert($this->prepareInsertData($insertData));
        }
        if ($deleteKeys) {
            $this->newQuery()
                ->whereIn($this->keyColumn, $deleteKeys)
                ->delete();
        }
    }


    public function parseReadData($data)
    {
        $results = array();
        foreach ($data as $row) {
            if (is_array($row)) {
                $key = $row[$this->keyColumn];
                $value = $row[$this->valueColumn];
            } elseif (is_object($row)) {
                $key = $row->{$this->keyColumn};
                $value = $row->{$this->valueColumn};
            } else {
                $msg = 'Expected array or object, got '.gettype($row);
                throw new \UnexpectedValueException($msg);
            }
            if($value == 'true' || $value == 'false')
                $value = false;
            ArrayUtil::set($results, $key, $value);
        }
        return $results;
    }




}
