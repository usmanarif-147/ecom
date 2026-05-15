<?php

namespace App\Enums;

enum ProductImportStatus: int
{
    case Queued = 0;
    case Running = 1;
    case Done = 2;
    case Failed = 3;
}
