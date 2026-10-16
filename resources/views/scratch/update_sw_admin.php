<?php
$p='e:/Z-pos/lang/sw.json';
$s=json_decode(file_get_contents($p),true);
$n=[
    'Dashboard'=>'Dashibodi',
    'Sales'=>'Mauzo',
    'Items'=>'Bidhaa',
    'Products'=>'Bidhaa',
    'Categories'=>'Makundi',
    'Brands'=>'Chapa',
    'Units'=>'Vipimo',
    'Expenses'=>'Matumizi',
    'Reports'=>'Ripoti',
    'Overview'=>'Muhtasari',
    'Profit and Loss'=>'Faida na Hasara',
    'Staff & Users'=>'Wafanyakazi',
    'Manage Shops'=>'Dhibiti Maduka',
    'Manage Payments'=>'Dhibiti Malipo',
    'Maintenance'=>'Matengenezo',
    'Shop Settings'=>'Mipangilio ya Duka',
    'Logout'=>'Toka'
];
file_put_contents($p,json_encode(array_merge($s,$n),JSON_PRETTY_PRINT));
echo "sw.json updated.";
