<?php namespace Aldea\Travel\Models;

use Model;

use Aldea\Travel\Classes\Helper;

/**
 * Model
 */
class CustomerPhone extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /**
     * @var array Validation rules
     */
    public $rules = [
        'phone' => 'required',
        'type' => 'required'
    ];

    public $attributeNames = [
        'phone' => 'aldea.travel::lang.customerPhone.phone',
        'type' => 'aldea.travel::lang.customerPhone.type',
    ];

    /**
     * @var string The database table used by the model.
     */
    
    public $table = 'aldea_travel_customers_phones';

    public function getTypeOptions($value = NULL, $formData = NULL)
    {
        $helper = new Helper;

        return $helper->customerTypeOptions();
    }

    public function getTypeAttribute()
    {
        if (!empty($this->attributes)) {
            return array_get($this->getTypeOptions(), array_get($this->attributes, 'type'));
        }
    }
}