<?php
namespace polux\CorePHP\Database\Interfaces;
interface IDatabaseObject{

    public function getTablename();
    public function getFields();
    public function getFieldValue($fieldname);
    public function getKeys();
}

?>