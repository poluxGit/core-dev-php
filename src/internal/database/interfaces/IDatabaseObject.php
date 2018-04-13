<?php
namespace polux\CorePHP\Internal\Database\Interfaces;
interface IDatabaseObject{

    public function getTablename();
    public function getFields();
    public function getFieldValue($fieldname);
    public function getKeys();
}

?>