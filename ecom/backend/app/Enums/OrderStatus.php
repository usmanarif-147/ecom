<?php

namespace App\Enums;

enum OrderStatus: int
{
    case Pending = 0;
    case Shipped = 1;
    case Delivered = 2;
    case Cancelled = 3;
}
