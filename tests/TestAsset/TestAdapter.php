<?php

namespace Pop\Payment\Test\TestAsset;

use Pop\Payment\Adapter\AbstractAdapter;

class TestAdapter extends AbstractAdapter
{

    protected $transaction = [
        'x_card_num' => null,
        'x_exp_date' => null
    ];

    protected $fields = [
        'cardNum' => 'x_card_num',
        'expDate' => 'x_exp_date'
    ];

    protected $requiredFields = [
        'x_card_num',
        'x_exp_date'
    ];

    public function send($verifyPeer = true)
    {
        $this->transaction['x_card_num'] = $this->filterCardNum($this->transaction['x_card_num']);
        if ($this->transaction['x_card_num'] == '4111111111111111') {
            $this->transaction['x_exp_date'] = $this->filterExpDate($this->transaction['x_exp_date'], 6);
            $this->response        = 'Approved';
            $this->responseCodes   = [1 => 'OK', 2 => 'Declined', 3 => 'Error'];
            $this->responseCode    = 1;
            $this->responseSubcode = 1001;
            $this->reasonCode      = 'OK';
            $this->message         = 'Transaction approved';
            $this->approved        = true;
            $this->declined        = false;
            $this->error           = false;
        } else if ($this->transaction['x_card_num'] == '4111111111111112') {
            $this->transaction['x_exp_date'] = $this->filterExpDate($this->transaction['x_exp_date'], 4);
            $this->response        = 'Declined';
            $this->responseCodes   = [1 => 'OK', 2 => 'Declined', 3 => 'Error'];
            $this->responseCode    = 2;
            $this->responseSubcode = 2001;
            $this->reasonCode      = 'Declined';
            $this->message         = 'Transaction declined';
            $this->approved        = false;
            $this->declined        = true;
            $this->error           = false;
        } else {
            $this->transaction['x_exp_date'] = $this->filterExpDate($this->transaction['x_exp_date'], 4);
            $this->response        = 'Error';
            $this->responseCodes   = [1 => 'OK', 2 => 'Declined', 3 => 'Error'];
            $this->responseCode    = 3;
            $this->responseSubcode = 3001;
            $this->reasonCode      = 'Error';
            $this->message         = 'Transaction error';
            $this->approved        = false;
            $this->declined        = false;
            $this->error           = true;
        }
    }

}