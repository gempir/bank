<?php declare(strict_types=1);

namespace App\Enumeration;

class ReasonEnumeration extends BasicEnumeration
{
    const DEBTOR_PAYBACK = "debtor_payback";
    const BANK_CHARGE = "bank_charge";
    const PAYMENT_REQUEST = "payment_request";
    const UNIDENTIFIED = "unidentified";
}