<?php namespace Aldea\Travel\Models;

use Model;

/**
 * Model
 */
class Customer extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $fillable = ['fullname', 'ci', 'ci_expiration', 'birthdate', 'passport', 'passport_expiration', 'ruc', 'business_name', 'birthplace', 'city_id', 'group_id'];
    
    /**
     * @var array Validation rules
     */
    public $rules = [
        'fullname' => 'required',
        'city' => 'required',
        'group' => 'required',
    ];

    public $attributeNames = [
        'fullname' => 'aldea.travel::lang.customer.fullname',
        'city' => 'aldea.travel::lang.customer.city',
        'group' => 'aldea.travel::lang.customer.group',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'aldea_travel_customers';

    public $belongsTo = [
        'city' => 'Aldea\Travel\Models\City',
        'group' => 'Aldea\Travel\Models\Group'
    ];

    public $hasMany = [
        'emails' => 'Aldea\Travel\Models\CustomerEmail',
        'address' => 'Aldea\Travel\Models\CustomerAddress',
        'phones' => 'Aldea\Travel\Models\CustomerPhone',
        'packages' => 'Aldea\Travel\Models\PackageSent'
    ];
}