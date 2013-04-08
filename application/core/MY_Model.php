<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Evan Hou
 * Date: 12-6-1
 * Time: 上午8:51
 * wunxin E-commerce management system
 */
class MY_Model extends CI_Model
{
    /**
     * 对象实例计数器
     * @var int
     */
    static $obj_num = 0;

    public function __construct()
    {
        ++self::$obj_num;
        parent::__construct();
    }

    /**
     * @name 获取数据库所有表名
     *
     * @return array
     */
    public function getDatabaseTables()
    {
        $sql = "show tables";
        return $this->db->query($sql)->result_array();
    }



    /**
     * 获取表字段
     *
     * @param $tableName string
     * @return mixed array
     */
    public function getTableField($tableName)
    {
        $sql = "desc " . trim($tableName);
        return $this->db->query($sql)->result_array();
    }

    /**
     * @name 检查表字段
     *
     * @param $tableName string
     * @param $field string
     * @return bool
     */
    function checkTableField($tableName, $field)
    {
        $this->load->driver('cache');
        $field = trim($field);
        $tableName = trim($tableName);

        $tf = $this->cache->file->get($tableName);

        if (empty ($tf)) {
            $tableAllField = $this->getTableField($tableName);

            foreach ($tableAllField as $v) {
                $tf[] = $v['Field'];
            }

            $this->cache->file->save($tableName, $tf, 86400);
        }

        return in_array($field, $tf) ? true : false;
    }

    /**
     * @name 批量检查表字段
     *
     * @param $tableName string
     * @param array $field array
     * @param bool $isKey boolean
     * @return bool
     */
    public function batchCheckTableField($tableName, array $field, $isKey = false)
    {
        $this->load->driver('cache');
        $tableName = trim($tableName);

        $tf = $this->cache->file->get($tableName);
        if (empty ($tableAllField)) {
            $tableAllField = $this->getTableField($tableName);

            foreach ($tableAllField as $v) {
                $tf[] = $v['Field'];
            }

            $this->cache->file->save($tableName, $tf, 86400);
        }

        foreach ($field as $k => $v) {
            if ($isKey) $v = $k;

            if (!in_array($v, $tf)) {
                return false;
            }
        }
        return true;
    }

    static protected function formatField($fields)
    {
        if(is_array($fields))
        {
            $key = isset($fields[0]) ? null : key($fields);
            $fields = current($fields);
        }
        else
        {
            $key = null;
        }
        return array($key, $fields);
    }

    /**
     * 获取文件内容
     *
     * @param $fileName
     * @param string $outCharset
     * @return bool|string
     */
    public function getFileContent($fileName, $outCharset = 'utf-8')
    {
        if (empty ($fileName) || !file_exists($fileName)) return false;

        $content = file_get_contents($fileName);

        $encode = mb_detect_encoding($content, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
        //var_dump($encode);exit;
        if($encode == 'EUC-CN')$encode='GBK';
        if ($encode !== 'UTF-8') {
            $content = iconv($encode, $outCharset.'//IGNORE', $content);
        }
        //

        return $content;
    }


    function __destruct()
    {
        /*/
        --self::$obj_num;
        if (ENVIRONMENT === 'development' && self::$obj_num === 0 && ! $this->input->is_ajax_request() && ! $this->input->is_cli_request() ) {
            if (count($this->db->queries)) {
                echo '<div  style="margin: 12px 15px 12px 15px;float: right;border: 1px solid #D0D0D0;display: block;">';
                foreach ($this->db->queries as $k => $v) {
                    echo "\n",'TIME:', sprintf("%.10f",$this->db->query_times[$k]),"\tSQL:", str_replace("\n", ' ', $v), "<br>";
                }
                echo '</div>';
            }
        }
        //*/
    }
}
