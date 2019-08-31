<?php
/**
 * Awsome database framework
 * https://binfinn.gitee.io
 * Version 0.0.0
 *
 * Copyright 2019
 * Released under the MIT license
 */
namespace Mysqli;

class Mysqli
{
    public static function test() {
        echo 'test success!';
    }

    private $mysqli;
    private $result;
    /**
    * ���ݿ�����
    * @param $config ��������
    */
    public function connect($config)
    {
	    $host = $config['host'];    //������ַ
        $username = $config['username'];//�û���
        $password = $config['password'];//����
        $database = $config['database'];//���ݿ�
        $port = $config['port'];    //�˿ں�
        $this->mysqli = new mysqli($host, $username, $password, $database, $port);
    }
    /**
    * ���ݲ�ѯ
    * @param $table ���ݱ�
    * @param null $field �ֶ�
    * @param null $where ����
    * @return mixed ��ѯ�����Ŀ
    */
    public function select($table, $field = null, $where = null)
    {
	    $sql = "SELECT * FROM {$table}";
        if (!empty($field)) {
            $field = '`' . implode('`,`', $field) . '`';
            $sql = str_replace('*', $field, $sql);
        }
        if (!empty($where)) {
            $sql = $sql . ' WHERE ' . $where;
        }
        $this->result = $this->mysqli->query($sql);
        return $this->result->num_rows;
    }
    /**
    * @return mixed ��ȡȫ�����
    */
    public function fetchAll()
    {
	    return $this->result->fetch_all(MYSQLI_ASSOC);
    }
    /**
    * ��������
    * @param $table ���ݱ�
    * @param $data ��������
    * @return mixed ����ID
    */
    public function insert($table, $data)
    {
        foreach ($data as $key => $value) {
        $data[$key] = $this->mysqli->real_escape_string($value);
        }
        $keys = '`' . implode('`,`', array_keys($data)) . '`';
        $values = '\'' . implode("','", array_values($data)) . '\'';
        $sql = "INSERT INTO {$table}( {$keys} )VALUES( {$values} )";
        $this->mysqli->query($sql);
        return $this->mysqli->insert_id;
    }
    /**
    * ��������
    * @param $table ���ݱ�
    * @param $data ��������
    * @param $where ��������
    * @return mixed ��Ӱ���¼
    */
    public function update($table, $data, $where)
    {
        foreach ($data as $key => $value) {
            $data[$key] = $this->mysqli->real_escape_string($value);
        }
        $sets = array();
        foreach ($data as $key => $value) {
            $kstr = '`' . $key . '`';
            $vstr = '\'' . $value . '\'';
            array_push($sets, $kstr . '=' . $vstr);
        }
        $kav = implode(',', $sets);
        $sql = "UPDATE {$table} SET {$kav} WHERE {$where}";
        $this->mysqli->query($sql);
        return $this->mysqli->affected_rows;
    }
    /**
    * ɾ������
    * @param $table ���ݱ�
    * @param $where ��������
    * @return mixed ��Ӱ���¼
    */
    public function delete($table, $where)
    {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $this->mysqli->query($sql);
        return $this->mysqli->affected_rows;
    }
}