<?php
namespace App\Library\Base;

use EasySwoole\ORM\AbstractModel;

class AbstractBaseModel extends AbstractModel
{

    /**
     * 实体映射
     *
     * @param array $mapping
     * @return void
     */
    public function entityMappings(array $mapping)
    {
        foreach ($mapping as $k => $v) {
            if (!empty($v) && isset($this->$k)) {
                $this->$k = $v;
            }
        }
    }


    /**
     * @param $notNull
     * @param $strict
     * @return array|string[]
     */
    public function toArray($notNull = null, $strict = null): array
    {
        $pArray = parent::toArray(false,false); // TODO: Change the autogenerated stub

        $formatData = [];
        foreach ($pArray as $fieldName => $fieldVal) {
            $formatData[$fieldName] = $fieldVal;
        }

        $methods = get_class_methods($this);
        foreach ($methods as $m) {
            if (mb_substr($m, 0, 2) === '__') {
                $tmpFunNames = explode('__', $m);
                if (isset($formatData[$tmpFunNames[1]])) {
                    $formatData['__'.$tmpFunNames['2']] = $this->$m();
                }
            }
        }

        //将下滑线，转换成驼峰式
        return $this->_convertUnderline($formatData);
    }

    /**
     * 将下划线转换成驼峰式
     *
     * @param $data
     * @param bool $ucfirst
     * @return array|string|string[]|null
     */
    private function _convertUnderline($data , bool $ucfirst = false)
    {
        if (!is_string($data)) {
            $return = array();
            foreach ($data as $key => $str) {
                $key          = preg_replace_callback('/(([-_]+)([A-Za-z]{1}))/i', function ($matches) {
                    if ($matches[2] === '_') {
                        return strtoupper($matches[3]);
                    }
                    return $matches[0];
                }, $key);
                $key          = $ucfirst ? ucfirst($key) : $key;
                $return[$key] = $str;
            }
            return $return;
        } else {
            $data = preg_replace_callback('/(([-_]+)([A-Za-z]{1}))/i', function ($matches) {
                if ($matches[2] === '_') {
                    return strtoupper($matches[3]);
                }
                return $matches[0];
            }, $data);
            return $ucfirst ? ucfirst($data) : $data;
        }
    }


    public function kv(string $k, $v)
    {

    }
}