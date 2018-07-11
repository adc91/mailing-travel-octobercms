<?php namespace Aldea\Travel\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Redirect;
use Flash;
use Backend;

use Aldea\Travel\Models\Group as Group;

class Groups extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController'
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'manage_packages',
        'manage_users'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Aldea.Travel', 'menu-packages', 'menu-groups');

        if (post('popup_mode')) {
            $this->asExtension('FormController')->create();
        }
    }

    public function onCreateForm()
    {
        $this->asExtension('FormController')->create();

        return $this->makePartial('create');
    }

    public function onCreate()
    {
        $this->asExtension('FormController')->create_onSave();

        return $this->listRefresh();
    }

    public function onUpdateForm()
    {
        $this->asExtension('FormController')->update(post('id'));

        $this->vars['id'] = post('id');

        return $this->makePartial('update');
    }

    public function onUpdate()
    {
        $this->asExtension('FormController')->update_onSave(post('id'));

        return $this->listRefresh();
    }

    public function onDelete()
    {
        $this->asExtension('FormController')->update_onDelete(post('id'));
        
        return $this->listRefresh();
    }
}