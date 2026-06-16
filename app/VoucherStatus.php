<?php

namespace App;

enum VoucherStatus: string
{
    case Issued = 'issued';
    case Redeemed = 'redeemed';
    case Invalid = 'invalid';
}