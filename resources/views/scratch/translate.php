<?php
$files = [
    'e:/Z-pos/resources/views/layouts/landing.blade.php',
    'e:/Z-pos/resources/views/welcome.blade.php'
];

$replacements = [
    '>Home<' => '>{{ __(\'Home\') }}<',
    '>Features<' => '>{{ __(\'Features\') }}<',
    '>Pricing<' => '>{{ __(\'Pricing\') }}<',
    '>Testimonials<' => '>{{ __(\'Testimonials\') }}<',
    '>About Us<' => '>{{ __(\'About Us\') }}<',
    '>Contact<' => '>{{ __(\'Contact\') }}<',
    '>Sign in<' => '>{{ __(\'Sign in\') }}<',
    '>Try free for 7 days<' => '>{{ __(\'Try free for 7 days\') }}<',
    '>Product<' => '>{{ __(\'Product\') }}<',
    '>Company<' => '>{{ __(\'Company\') }}<',
    '>Find Us<' => '>{{ __(\'Find Us\') }}<',
    'Run your shop <br> from your pocket.' => '{!! __(\'Run your shop <br> from your pocket.\') !!}',
    'Z-pos is a complete POS for Tanzanian retailers — products, suppliers, customers, and finances, on iOS, Android and the web.' => '{{ __(\'Z-pos is a complete POS for Tanzanian retailers — products, suppliers, customers, and finances, on iOS, Android and the web.\') }}',
    '>Start free trial ' => '>{{ __(\'Start free trial\') }} ',
    '> Watch 10-sec demo<' => '> {{ __(\'Watch 10-sec demo\') }}<',
    '>shops onboarded<' => '>{{ __(\'shops onboarded\') }}<',
    '>processed monthly<' => '>{{ __(\'processed monthly\') }}<',
    '>App Store rating<' => '>{{ __(\'App Store rating\') }}<',
    '>Trusted by Industry Leaders<' => '>{{ __(\'Trusted by Industry Leaders\') }}<',
    '>Core Features<' => '>{{ __(\'Core Features\') }}<',
    '>Everything you need to scale<' => '>{{ __(\'Everything you need to scale\') }}<',
    '>From single shops to nationwide chains, we\'ve got you covered.<' => '>{{ __(\'From single shops to nationwide chains, we\\\'ve got you covered.\') }}<',
    '>Active Stores<' => '>{{ __(\'Active Stores\') }}<',
    '>Daily Transactions<' => '>{{ __(\'Daily Transactions\') }}<',
    '>Uptime<' => '>{{ __(\'Uptime\') }}<',
    '>Customer Support<' => '>{{ __(\'Customer Support\') }}<',
    '>Loved by Business Owners<' => '>{{ __(\'Loved by Business Owners\') }}<',
    '>Don\'t just take our word for it.<' => '>{{ __(\'Don\\\'t just take our word for it.\') }}<',
    '>Simple, Transparent Pricing<' => '>{{ __(\'Simple, Transparent Pricing\') }}<',
    '>No hidden fees. Scale as you grow.<' => '>{{ __(\'No hidden fees. Scale as you grow.\') }}<',
    '>Frequently Asked Questions<' => '>{{ __(\'Frequently Asked Questions\') }}<',
    '>Still have questions?<' => '>{{ __(\'Still have questions?\') }}<',
    '>Do I need internet to use the POS?<' => '>{{ __(\'Do I need internet to use the POS?\') }}<',
    '>What hardware is supported?<' => '>{{ __(\'What hardware is supported?\') }}<',
    '>How secure is my data?<' => '>{{ __(\'How secure is my data?\') }}<',
    '>Starter<' => '>{{ __(\'Starter\') }}<',
    '>Professional<' => '>{{ __(\'Professional\') }}<',
    '>Enterprise<' => '>{{ __(\'Enterprise\') }}<',
    '>Custom<' => '>{{ __(\'Custom\') }}<',
    '>MOST POPULAR<' => '>{{ __(\'MOST POPULAR\') }}<'
];

foreach ($files as $path) {
    $c = file_get_contents($path);
    foreach ($replacements as $search => $replace) {
        $c = str_replace($search, $replace, $c);
    }
    file_put_contents($path, $c);
    echo "Updated $path\n";
}
