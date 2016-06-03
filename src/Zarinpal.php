<?php

namespace Zarinpal;

use Zarinpal\Drivers\DriverInterface;

class Zarinpal
{
    private $merchantID;
    private $driver;
    private $Authority;

    private $debug;

    public function __construct($merchantID, DriverInterface $driver, $debug = false)
    {
        $this->merchantID = $merchantID;
        $this->driver = $driver;

        $this->debug = $debug;
    }

    /**
     * send request for money to zarinpal
     * and redirect if there was no error.
     *
     * @param string $callbackURL
     * @param string $Amount
     * @param string $Description
     * @param string $Email
     * @param string $Mobile
     *
     * @return array|@redirect
     */
    public function request($callbackURL, $Amount, $Description, $Email = null, $Mobile = null)
    {
        $inputs = [
            'MerchantID'  => $this->merchantID,
            'CallbackURL' => $callbackURL,
            'Amount'      => $Amount,
            'Description' => $Description,
        ];
        if (!empty($Email)) {
            $inputs['Email'] = $Email;
        }
        if (!empty($Mobile)) {
            $inputs['Mobile'] = $Mobile;
        }
        $auth = $this->driver->request($inputs, $this->debug);
        if (empty($auth['Authority'])) {
            $auth['Authority'] = null;
        }
        $this->Authority = $auth['Authority'];

        return $auth;
    }

    /**
     * verify that the bill is paid or not
     * by checking authority, amount and status.
     *
     * @param $status
     * @param $amount
     * @param $authority
     *
     * @return array
     */
    public function verify($status, $amount)
    {
        $authority = \Session::get('auth');

        if ($status == 'OK') {
            $inputs = [
                'MerchantID' => $this->merchantID,
                'Authority'  => $authority,
                'Amount'     => $amount,
            ];

            return $this->driver->verify($inputs, $this->debug);
        } else {
            return ['Status' => 'canceled'];
        }
    }

    public function redirect($auth)
    {
        \Session::put('auth', $auth);
        $url = ($this->debug) ? 'sandbox' : 'www';

        return redirect('https://'.$url.'.zarinpal.com/pg/StartPay/'.$this->Authority);
    }

    /**
     * @return DriverInterface
     */
    public function getDriver()
    {
        return $this->driver;
    }
}
