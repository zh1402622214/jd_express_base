<?php
namespace Lop\Api\Plugin\Enum;

class HmacSourceType
{
    const Header = 1;
    const UrlArgs = 2;
    const Cookie = 3;
    const SystemVar = 4;
    const CustomerVar = 5;
    const FixedConstant = 9;

}