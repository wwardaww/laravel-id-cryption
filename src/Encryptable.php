<?php
/**
 * Created by PhpStorm.
 * User: Arda
 * Date: 05/05/2017
 * Time: 14:51
 */


namespace Wwardaww\Traits;
use Crypt;

trait Encryptable
{

    /**
     * If the attribute is in the findOrFail array
     * then decrypt it.
     *
     * @param  $value
     *
     * @return $value
     */
    public static function decryptOrFail($value)
    {
        $value = Crypt::decrypt($value);

        return parent::findOrFail($value);
    }

    /**
     * If the attribute is in the find
     * then decrypt it.
     *
     * @param  $value
     *
     * @return $value
     */
    public static function decryptFind($value)
    {
        if(php_sapi_name() == "cli")
            return parent::find($value);
        $value = Crypt::decrypt($value);

        return parent::find($value);
    }


    /**
     * If the attribute is in the encryptable array
     * then decrypt it.
     *
     * @param  $key
     *
     * @return $value
     */
    public function getAttribute($key)
    {
        $this->hiddenFunctions = array_merge((($this->hiddenFunctions)?:[]),$this->defaultHiddens());
        if(php_sapi_name() == "cli")
            return parent::getAttribute($key);

        $callback = debug_backtrace()[2]['function'];
        $value = parent::getAttribute($key);
        if (in_array($key, $this->encryptable)&&!in_array($callback,$this->hiddenFunctions)) {
            $value = Crypt::encrypt($value);
        }

        return parent::getAttribute($key, $value);

    }

    /**
     * If the attribute is in the encryptable array
     * then encrypt it.
     *
     * @param $key
     * @param $value
     */
    public function setAttribute($key, $value)
    {
        $this->hiddenFunctions = array_merge((($this->hiddenFunctions)?:[]),$this->defaultHiddens());
        if(php_sapi_name() == "cli")
            return parent::setAttribute($key, $value);

        $callback = debug_backtrace()[2]['function'];
        if (in_array($key, $this->encryptable)&&!in_array($callback,$this->hiddenFunctions)) {
            $value = Crypt::decrypt($value);
        }


        return parent::setAttribute($key, $value);
    }

    /**
     * When need to make sure that we iterate through
     * all the keys.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $this->hiddenFunctions = array_merge((($this->hiddenFunctions)?:[]),$this->defaultHiddens());
        if(php_sapi_name() == "cli")
            return  parent::attributesToArray();
        $callback = debug_backtrace()[2]['function'];
        $attributes = parent::attributesToArray();
        foreach ($this->encryptable as $key) {
            if (isset($attributes[$key])&&!in_array($callback,$this->hiddenFunctions)) {
                $attributes[$key] = Crypt::encrypt($attributes[$key]);
            }
        }

        return $attributes;
    }
    private function defaultHiddens (){
        return [
            '__construct',
            'create',
            'performInsert',
            'update',
            'updateOrCreate'
        ];
    }
}
