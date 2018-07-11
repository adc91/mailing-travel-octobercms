<?php namespace Aldea\Travel\Models;

use Model;

use Aldea\Travel\Classes\Helper;

/**
 * Model
 */
class CustomerAddress extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $fillable = ['address', 'type', 'customer_id'];
    
    /**
     * @var array Validation rules
     */
    public $rules = [
        'address' => 'required',
        'type' => 'required'
    ];

    public $attributeNames = [
        'address' => 'aldea.travel::lang.customerAddress.address',
        'type' => 'aldea.travel::lang.customerAddress.type',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'aldea_travel_customers_address';

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