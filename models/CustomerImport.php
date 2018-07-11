<?php namespace Aldea\Travel\Models;

class CustomerImport extends \Backend\Models\ImportModel
{
    /**
     * @var array The rules to be applied to the data.
     */
    public $rules = [];

    public $belongsTo = [
        'city' => 'Aldea\Travel\Models\City',
        'group' => 'Aldea\Travel\Models\Group'
    ];

    public function importData($results, $sessionKey = null)
    {
        foreach ($results as $row => $data) {

            $data = $this->validateAndFormatedDate($data);
            $data = $this->assignDefaultValues($data);

            try {
                $customer = new Customer;
                $customer->fill($data);
                $customer->save();

                // Prepare mails
                $this->insertEmails($customer->id, $data);
                $this->insertAddress($customer->id, $data);

                $this->logCreated();
            }
            catch (\Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }
        }
    }

    protected function validateAndFormatedDate($row)
    {
        // Birthdate
        if (isset($row['birthdate'])) {
            $bdayDateArray = explode("-", $row['birthdate']);

            if (count($bdayDateArray) <= 1) {
                $bdayDateArray = explode("/", $row['birthdate']);
            }

            if (isset($bdayDateArray[1])) {
                $row['birthdate'] = date("Y-m-d", mktime(0, 0, 0, $bdayDateArray[1], $bdayDateArray[0], $bdayDateArray[2]));
            } else {
                $row['birthdate'] = NULL;
            }
        }

        // Passport
        if (isset($row['passport_expiration'])) {
            $ciDateArray = explode("-", $row['passport_expiration']);

            if (count($ciDateArray) <= 1) {
                $passportDateArray = explode("/", $row['passport_expiration']);
            }

            if (isset($passportDateArray[1])) {
                $row['passport_expiration'] = date("Y-m-d", mktime(0, 0, 0, $passportDateArray[1], $passportDateArray[0], $passportDateArray[2]));
            } else {
                $row['passport_expiration'] = NULL;
            }
        }

        // CI / DNI
        if (isset($row['ci_expiration'])) {
            $ciDateArray = explode("-", $row['ci_expiration']);

            if (count($ciDateArray) <= 1) {
                $ciDateArray = explode("/", $row['ci_expiration']);
            }

            if (isset($ciDateArray[1])) {
                $row['ci_expiration'] = date("Y-m-d", mktime(0, 0, 0, $ciDateArray[1], $ciDateArray[0], $ciDateArray[2]));
            } else {
                $row['ci_expiration'] = NULL;
            }
        }

        return $row;
    }

    protected function assignDefaultValues($row)
    {
        $row['city_id'] = $this->city->id;
        $row['group_id'] = $this->group->id;

        return $row;
    }

    private function insertEmails($customerId = NULL, $data = [])
    {
        $rowsList = [];

        // Save email list
        if (!empty($data['email'])) {

            $emailExplode = explode(',', $data['email']);

            foreach ($emailExplode as $value) {
                if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $rowsList[] = [
                        'email' => $value,
                        'type' => 1,
                        'customer_id' => $customerId
                    ];
                }
            }

            if (count($rowsList) > 0) {
                CustomerEmail::insert($rowsList);
            }
        }
    }

    private function insertAddress($customerId = NULL, $data = [])
    {
        $rowsList = [];

        /**
         * $data['address']) != 'null': This conditional is used because some export their CSV files and in the empty fields the word "NULL" is implicit
         */
        if (!empty($data['address']) && strtolower($data['address']) != 'null') {

            $addressExplode = explode(',', $data['address']);

            foreach ($emailExplode as $value) {
                if (!empty($value)) {
                    $rowsList[] = [
                        'address' => $value,
                        'type' => 1,
                        'customer_id' => $customerId
                    ];
                }
            }

            if (count($rowsList) > 0) {
                CustomerAddress::insert($rowsList);
            }
        }
    }
}