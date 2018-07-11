<?php namespace Aldea\Travel\Models;

use Model;

/**
 * Model
 */
class Package extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required|max:200',
        'image' => 'required'
    ];

    public $attributeNames = [
        'name' => 'aldea.travel::lang.package.name',
        'image' => 'aldea.travel::lang.package.image'
    ];

    protected $fillable = ['name'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'aldea_travel_packages';

    public $attachOne = [
        'image' => 'System\Models\File'
    ];
}