<?php namespace Aldea\Travel\Models;

use Model;

/**
 * Model
 */
class PackageSent extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /**
     * @var array Validation rules
     */
    public $rules = [
        'subject' => 'required',
        'customer_id' => 'required',
        'package_id' => 'required',
        'group_id' => 'required'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'aldea_travel_packages_sent';

    public $belongsTo = [
        'package' => 'Aldea\Travel\Models\Package',
        'group' => 'Aldea\Travel\Models\Group',
        'customer' => 'Aldea\Travel\Models\Customer',
    ];
}