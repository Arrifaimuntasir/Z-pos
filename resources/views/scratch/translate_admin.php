<?php
$path = 'e:/Z-pos/resources/views/layouts/admin.blade.php';
$c = file_get_contents($path);

$replacements = [
    '> Dashboard<' => '> {{ __(\'Dashboard\') }}<',
    '> Sales<' => '> {{ __(\'Sales\') }}<',
    '> Items<' => '> {{ __(\'Items\') }}<',
    '>Products<' => '>{{ __(\'Products\') }}<',
    '>Categories<' => '>{{ __(\'Categories\') }}<',
    '>Brands<' => '>{{ __(\'Brands\') }}<',
    '>Units<' => '>{{ __(\'Units\') }}<',
    '> Expenses<' => '> {{ __(\'Expenses\') }}<',
    '> Reports<' => '> {{ __(\'Reports\') }}<',
    '>Overview<' => '>{{ __(\'Overview\') }}<',
    '>Profit and Loss<' => '>{{ __(\'Profit and Loss\') }}<',
    '> Staff & Users<' => '> {{ __(\'Staff & Users\') }}<',
    '> Manage Shops<' => '> {{ __(\'Manage Shops\') }}<',
    '> Manage Payments<' => '> {{ __(\'Manage Payments\') }}<',
    '>Maintenance<' => '>{{ __(\'Maintenance\') }}<',
    '> Shop Settings<' => '> {{ __(\'Shop Settings\') }}<'
];

foreach ($replacements as $search => $replace) {
    $c = str_replace($search, $replace, $c);
}

file_put_contents($path, $c);
echo "Translated admin.blade.php\n";
