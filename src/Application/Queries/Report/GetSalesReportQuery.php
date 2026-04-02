<?php
// src/Application/Queries/Report/GetSalesReportQuery.php
namespace Application\Queries\Report;

class GetSalesReportQuery
{
    public function __construct(
        public readonly string $from,
        public readonly string $to,
        public readonly string $groupBy = 'daily', // daily | monthly
    ) {}
}
