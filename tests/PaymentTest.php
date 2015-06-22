<?php

namespace Pop\Payment\Test;

use Pop\Payment\Payment;
use Pop\Payment\Test\TestAsset\TestAdapter;

class PaymentTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $payment = new Payment(new TestAdapter());
        $this->assertInstanceOf('Pop\Payment\Payment', $payment);
        $this->assertInstanceOf('Pop\Payment\Test\TestAsset\TestAdapter', $payment->adapter());
    }

    public function testApproved()
    {
        $payment = new Payment(new TestAdapter());
        $payment->cardNum = '4111111111111111';
        $payment->expDate = '10-15';
        $payment->send();
        $this->assertTrue($payment->isValid());
        $this->assertTrue($payment->isTest());
        $this->assertTrue($payment->isApproved());
        $this->assertFalse($payment->isDeclined());
        $this->assertFalse($payment->isError());
        $this->assertEquals('Approved', $payment->getResponse(1));
        $this->assertEquals('OK', $payment->getCode(1));
        $this->assertEquals(1, $payment->getResponseCode());
        $this->assertEquals(3, count($payment->getResponseCodes()));
        $this->assertEquals('Transaction approved', $payment->getMessage());
    }

    public function testNotValid()
    {
        $payment = new Payment(new TestAdapter());
        $this->assertFalse($payment->isValid());
    }

    public function testDeclined()
    {
        $payment = new Payment(new TestAdapter());
        $payment->cardNum = '4111-1111-1111-1112';
        $payment->expDate = '10-2015';
        $payment->send(true);
        $this->assertTrue(isset($payment->cardNum));
        $this->assertFalse($payment->isApproved());
        $this->assertTrue($payment->isDeclined());
        $this->assertFalse($payment->isError());
    }

    public function testError()
    {
        $payment = new Payment(new TestAdapter());
        $payment->cardNum = '4111 1111 1111 1114';
        $payment->expDate = '10/15';
        unset($payment->cardNum);
        $this->assertFalse(isset($payment->cardNum));
        $payment->cardNum = '4111 1111 1111 1113';
        $payment->send();
        $this->assertFalse($payment->isApproved());
        $this->assertFalse($payment->isDeclined());
        $this->assertTrue($payment->isError());
    }

    public function testErrorWithLongDate()
    {
        $payment = new Payment(new TestAdapter());
        $payment->cardNum = '4111 1111 1111 1114';
        $payment->expDate = '10/2015';
        unset($payment->cardNum);
        $this->assertFalse(isset($payment->cardNum));
        $payment->cardNum = '4111 1111 1111 1113';
        $payment->send();
        $this->assertFalse($payment->isApproved());
        $this->assertFalse($payment->isDeclined());
        $this->assertTrue($payment->isError());
    }

    public function testShippingSameAsBilling()
    {
        $payment = new Payment(new TestAdapter());
        $payment->firstName = 'Test';
        $payment->lastName  = 'Person';
        $payment->company   = 'Some Company';
        $payment->address   = '123 Main St';
        $payment->city      = 'New Orleans';
        $payment->state     = 'LA';
        $payment->zip       = '70124';
        $payment->country   = 'US';

        $payment->shippingSameAsBilling();

        $this->assertEquals('Test', $payment->shipToFirstName);
        $this->assertEquals('Person', $payment->shipToLastName);
        $this->assertEquals('Some Company', $payment->shipToCompany);
        $this->assertEquals('123 Main St', $payment->shipToAddress);
        $this->assertEquals('New Orleans', $payment->shipToCity);
        $this->assertEquals('LA', $payment->shipToState);
        $this->assertEquals('70124', $payment->shipToZip);
        $this->assertEquals('US', $payment->shipToCountry);
    }

}