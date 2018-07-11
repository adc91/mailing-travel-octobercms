<?php namespace Aldea\Travel\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Customers extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend.Behaviors.RelationController',
        'Backend.Behaviors.ImportExportController',
    ];
    
    public $listConfig = ['customers' => 'config_list.yaml'];
    public $formConfig = 'config_form.yaml';
    public $importExportConfig = 'config_import_export.yaml';
    public $relationConfig = 'config_relation.yaml';

    public $requiredPermissions = [
        'manage_packages',
        'manage_customers' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Aldea.Travel', 'menu-packages', 'menu-customers');
    }
}