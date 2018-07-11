<?php namespace Aldea\Travel\Models;

use Model;

/**
 * Model
 */
class Group extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required|max:200'
    ];

    public $attributeNames = [
        'name' => 'aldea.travel::lang.group.name'
    ];

    protected $fillable = ['name'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'aldea_travel_groups';

    public $hasMany = [
        'customers' => 'Aldea\Travel\Models\Customer'
    ];
}