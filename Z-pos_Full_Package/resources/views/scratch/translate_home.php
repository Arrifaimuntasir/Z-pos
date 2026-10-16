<?php
$path = 'e:/Z-pos/resources/views/home.blade.php';
$c = file_get_contents($path);

$replacements = [
    '>Dashboard<' => '>{{ __(\'Dashboard\') }}<',
    '>Business statistics ' => '>{{ __(\'Business statistics\') }} ',
    '>Overall statistics<' => '>{{ __(\'Overall statistics\') }}<',
    '>Today\'s statistics<' => '>{{ __(\'Today\\\'s statistics\') }}<',
    '>This month\'s statistics<' => '>{{ __(\'This month\\\'s statistics\') }}<',
    '>Half-year statistics<' => '>{{ __(\'Half-year statistics\') }}<',
    '>Yearly statistics<' => '>{{ __(\'Yearly statistics\') }}<',
    '>Total Sales<' => '>{{ __(\'Total Sales\') }}<',
    '>Gross Profit<' => '>{{ __(\'Gross Profit\') }}<',
    '>Expenses<' => '>{{ __(\'Expenses\') }}<',
    '\'Net Profit\'' => '__(\'Net Profit\')',
    '\'Net Loss\'' => '__(\'Net Loss\')',
    '>Cash Flow<' => '>{{ __(\'Cash Flow\') }}<',
    '>This year &middot; monthly<' => '>{{ __(\'This year\') }} &middot; {{ __(\'monthly\') }}<',
    '>Total Income<' => '>{{ __(\'Total Income\') }}<',
    '>Total Expenses<' => '>{{ __(\'Total Expenses\') }}<',
    '>Net Cash Flow<' => '>{{ __(\'Net Cash Flow\') }}<',
    '>Income & Expenses<' => '>{{ __(\'Income & Expenses\') }}<',
    '>Top Expenses<' => '>{{ __(\'Top Expenses\') }}<',
    '>This year &middot; cumulative<' => '>{{ __(\'This year\') }} &middot; {{ __(\'cumulative\') }}<',
    '>Recent Sales<' => '>{{ __(\'Recent Sales\') }}<',
    '>View All<' => '>{{ __(\'View All\') }}<',
    '>Paid<' => '>{{ __(\'Paid\') }}<',
    '>Partial<' => '>{{ __(\'Partial\') }}<',
    '>Unpaid<' => '>{{ __(\'Unpaid\') }}<',
    '>No recent sales found.<' => '>{{ __(\'No recent sales found.\') }}<'
];

foreach ($replacements as $search => $replace) {
    $c = str_replace($search, $replace, $c);
}

file_put_contents($path, $c);
echo "Translated home.blade.php\n";
