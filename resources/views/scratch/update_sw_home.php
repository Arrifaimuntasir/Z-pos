<?php
$p='e:/Z-pos/lang/sw.json';
$s=json_decode(file_get_contents($p),true);
$n=[
    'Business statistics'=>'Takwimu za biashara',
    'Overall statistics'=>'Takwimu za jumla',
    'Today\'s statistics'=>'Takwimu za leo',
    'This month\'s statistics'=>'Takwimu za mwezi huu',
    'Half-year statistics'=>'Takwimu za nusu mwaka',
    'Yearly statistics'=>'Takwimu za mwaka',
    'Total Sales'=>'Jumla ya Mauzo',
    'Gross Profit'=>'Faida Ghafi',
    'Net Profit'=>'Faida Halisi',
    'Net Loss'=>'Hasara Halisi',
    'Cash Flow'=>'Mzunguko wa Pesa',
    'This year'=>'Mwaka huu',
    'monthly'=>'kwa mwezi',
    'cumulative'=>'jumla',
    'Total Income'=>'Jumla ya Mapato',
    'Total Expenses'=>'Jumla ya Matumizi',
    'Net Cash Flow'=>'Mzunguko Halisi',
    'Income & Expenses'=>'Mapato na Matumizi',
    'Top Expenses'=>'Matumizi Makubwa',
    'Recent Sales'=>'Mauzo ya Hivi Karibuni',
    'View All'=>'Tazama Yote',
    'Paid'=>'Imelipwa',
    'Partial'=>'Nusu',
    'Unpaid'=>'Haijalipwa',
    'No recent sales found.'=>'Hakuna mauzo ya hivi karibuni.'
];
file_put_contents($p,json_encode(array_merge($s,$n),JSON_PRETTY_PRINT));
echo "sw.json updated for home.";
