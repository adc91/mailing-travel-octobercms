<?php namespace Aldea\Travel\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Validator;
use Flash;
use Lang;
use File;
use Mail;

// Mailing
use System\Classes\MailManager;
use System\Models\MailLayout;
use System\Models\MailTemplate;

// Validation
use ValidationException;
use ApplicationException;

use Aldea\Travel\Models\Package;
use Aldea\Travel\Models\PackageSent;
Use Aldea\Travel\Models\Customer;
Use Aldea\Travel\Models\Group;

class Packages extends Controller
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'manage_packages'
    ];

    protected $itemFormWidget;

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Aldea.Travel', 'menu-packages', 'menu-packages');

        // Add or edit package flag
        if (post('popup_mode')) {
            $this->asExtension('FormController')->create();
        }

        // Widget for Massive sent
        $this->itemFormWidget = $this->createFormMassiveSentWidget();
    }

    // ABM Packages
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

    protected function createFormMassiveSentWidget()
    {
        if (post('package_massive_mode')) {
            $config = $this->makeConfig('$/aldea/travel/models/packagesent/fields-package.yaml');
        } elseif (post('group_massive_mode')) {
            $config = $this->makeConfig('$/aldea/travel/models/packagesent/fields-group.yaml');
        } elseif (post('customer_massive_mode')) {
            $config = $this->makeConfig('$/aldea/travel/models/packagesent/fields-customer.yaml');
        } else if (!empty(post())) {
            $config = $this->makeConfig('$/aldea/travel/models/packagesent/fields-customer.yaml');
        } else {
            $config = $this->makeConfig('$/aldea/travel/models/packagesent/fields-package.yaml');
        }

        $config->alias = 'packageSentForm';
        $config->arrayName = 'PackageForm';
        $config->model = new \Aldea\Travel\Models\PackageSent;

        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();

        return $widget;
    }

    /**
     * [onCreateFormMassiveSent Create the form in the popup by instantiating the created widget.]
     * @return [string]
     */
    public function onCreateFormMassiveSent()
    {
        $this->asExtension('FormController')->create();

        $this->vars['itemFormWidget'] = $this->itemFormWidget;

        $this->vars['package_massive_mode'] = post('package_massive_mode');
        $this->vars['group_massive_mode'] = post('group_massive_mode');
        $this->vars['customer_massive_mode'] = post('customer_massive_mode');

        return $this->makePartial('create_massive_sent');
    }

    /**
     * [onCreateFormMassivePreview Create a preview of how the email would be displayed once it is sent.]
     * @return [string]
     */
    public function onCreateFormMassivePreview()
    {
        // Validate form
        $post = $this->onCreateFormMassiveSentValidate();

        // Get package row info
        $packageRow = $this->getPackageRow($post['package']);

        $data = [
            'subject' => $post['subject'],
            'image' => $packageRow->image,
            'activeAccount' => e(trans('aldea.travel::lang.mail.activeAccount')),
        ];

        $layout = new MailLayout;
        $layout->fillFromCode('default');

        $template = new MailTemplate;
        $template->layout = $layout;
        $template->content_html = File::get(base_path('plugins/aldea/travel/views/mail/package.htm'));

        return $this->makePartial('preview_massive_sent', ['renderedTemplate' => MailManager::instance()->renderTemplate($template, $data)]);
    }

    /**
     * [onCreateFormMassiveSentStart Verify what type of email the client wants to send.]
     * @return [void]
     */
    public function onCreateFormMassiveSentStart()
    {
        if (post('package_massive_mode')) {
            $this->packageMassiveSendStart();
        } else if (post('group_massive_mode')) {
            $this->groupMassiveSendStart();
        } else {
            $this->customerSendStart();
        }
    }

    /**
     * [packageMassiveSendStart Start the process of sending mass emails to all users of the database]
     * @return [void]
     */
    protected function packageMassiveSendStart()
    {
        // Validate form
        $post = $this->onCreateFormMassiveSentValidate();

        // Get package row info
        $packageRow = $this->getPackageRow($post['package']);

        // Template params
        $params = [
            'subject' => $post['subject'],
            'image' => $packageRow->image,
            'activeAccount' => e(trans('aldea.travel::lang.mail.activeAccount')),
        ];

        Customer::chunk(200, function($customers) use ($post, $packageRow, $params) {
            foreach ($customers as $customer) {
                $recipientsList = [];

                foreach ($customer->emails as $row) {
                    $recipientsList[] = [
                        'id' => $customer->id,
                        'name' => $customer->fullname,
                        'email' => $row->email,
                        'group_id' => $customer->group_id
                    ];
                }

                $this->sendMail($post, $packageRow, $params, $recipientsList);
            }
        });

        return Flash::success(e(trans('aldea.travel::lang.packageSent.sendAllSuccess')));
    }

    /**
     * [packageMassiveSendStart Start the process of sending mass emails to all users of the group selected]
     * @return [mixed]
     */
    protected function groupMassiveSendStart()
    {
        // Validate form for group
        $post = $this->onCreateFormMassiveSentValidate('group');

        // Get package row info
        $packageRow = $this->getPackageRow($post['package']);

        // Template params
        $params = [
            'subject' => $post['subject'],
            'image' => $packageRow->image
        ];

        Customer::where('group_id', '=', $post['group'])
        ->chunk(200, function($customers) use ($post, $packageRow, $params) {
            foreach ($customers as $customer) {
                $recipientsList = [];

                foreach ($customer->emails as $row) {
                    $recipientsList[] = [
                        'id' => $customer->id,
                        'name' => $customer->fullname,
                        'email' => $row->email,
                        'group_id' => $customer->group_id
                    ];
                }

                $this->sendMail($post, $packageRow, $params, $recipientsList);
            }
        });

        return Flash::success(e(trans('aldea.travel::lang.packageSent.sendAllSuccess')));
    }

    /**
     * [customerSendStart Send an email to all the emails of the selected user]
     * @return [void]
     */
    protected function customerSendStart()
    {
        // Validate form for group
        $post = $this->onCreateFormMassiveSentValidate('customer');

        // Get package row info
        $packageRow = $this->getPackageRow($post['package']);

        // Get customer row info
        $customerRow = $this->getCustomerRow($post['customer']);

        // Template params
        $params = [
            'subject' => $post['subject'],
            'image' => $packageRow->image
        ];

        foreach ($customerRow->emails as $row) {
            $recipientsList[] = [
                'id' => $customerRow->id,
                'name' => $customerRow->fullname,
                'email' => $row->email,
                'group_id' => $customerRow->group_id
            ];
        }

        $this->sendMail($post, $packageRow, $params, $recipientsList);

        return Flash::success(e(trans('aldea.travel::lang.packageSent.sendAllSuccess')));
    }

    /**
     * [onCreateFormMassiveSentValidate Validate form fields]
     * @param  string $extraValidate [Extra fields validate]
     * @return [array]
     */
    private function onCreateFormMassiveSentValidate($extraValidate = '')
    {
        $post = post('PackageForm');

        $rules = [
            'package' => 'required|numeric',
            'subject' => 'required'
        ];

        if ($extraValidate == 'group') {
            $rules['group'] = 'required|numeric';
        } elseif ($extraValidate == 'customer') {
            $rules['customer'] = 'required|numeric';
        }

        $validator = Validator::make($post, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $post;
    }

    /**
     * [getPackageRow Gets the information stored in the database of a specific package.]
     * @param  integer $packageId [unique identifier of the package]
     * @return [array]
     */
    private function getPackageRow($packageId = 0)
    {
        $packageRow = Package::find($packageId);

        if (!$packageRow) {
            throw new ApplicationException(e(trans('aldea.travel::lang.packageSent.404Package')));
        }

        return $packageRow;
    }

    /**
     * [getPackageRow Gets the information stored in the database of a specific customer.]
     * @param  integer $customerId [unique identifier of the customer]
     * @return [array]
     */
    private function getCustomerRow($customerId = 0)
    {
        $customerRow = Customer::find($customerId);

        if (!$customerRow) {
            throw new ApplicationException(e(trans('aldea.travel::lang.packageSent.404Customer')));
        }

        return $customerRow;
    }

    /**
     * [sendMail Proceed to send the email with the information provided in your input parameters.]
     * @param  array  $post           [Without description]
     * @param  array  $packageRow     [Without description]
     * @param  array  $params         [Without description]
     * @param  array  $recipientsList [Without description]
     * @return [void]
     */
    private function sendMail($post = [], $packageRow = [], $params = [], $recipientsList = [])
    {   
        try {
            Mail::send('aldea.travel::mail.package', $params, function($message) use ($post, $packageRow, $params, $recipientsList) {
                // Extract firts element of the array
                $firstTo = array_shift($recipientsList);

                $message->to($firstTo['email'], $firstTo['name']);

                if (count($recipientsList) > 0) {
                    foreach ($recipientsList as $recipient) {
                        $message->bcc($recipient['email'], $recipient['name']);
                    }
                }

                $message->subject($params['subject']);
            });

            $this->savePackagesSent($params['subject'], $packageRow->id, $recipientsList);

        } catch (\Exception $e) {
            // We must give more useful to this log in the future.
            traceLog($e->getMessage());
        }
    }

    private function savePackagesSent($subject = '', $packageId = 0, $recipientsList = [])
    {
        $packageSent = new PackageSent;

        foreach ($recipientsList as $value) {
            $packageSent->subject = $subject;
            $packageSent->customer_id = $value['id'];
            $packageSent->package_id = $packageId;
            $packageSent->group_id = $value['group_id'];
            $packageSent->save();
        }
    }
}