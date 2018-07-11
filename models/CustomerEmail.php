<?php namespace Aldea\Travel\Models;

use Model;

use Aldea\Travel\Classes\Helper;

/**
 * Model
 */
class CustomerEmail extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $fillable = ['email', 'type', 'customer_id'];
    
    /**
     * @var array Validation rules
     */
    public $rules = [
        'email' => 'required|email',
        'type' => 'required'
    ];

    public $attributeNames = [
        'email' => 'aldea.travel::lang.customerEmail.email',
        'type' => 'aldea.travel::lang.customerPhone.type',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'aldea_travel_customers_emails';

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