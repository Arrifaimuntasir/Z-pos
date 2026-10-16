<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CmsSetting;

class CmsSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ============ ABOUT PAGE ============
            ['page' => 'about', 'key' => 'hero_badge',      'label' => 'Hero Badge Text',   'type' => 'text',     'value' => 'Our Story',                               'sort_order' => 1],
            ['page' => 'about', 'key' => 'hero_title',      'label' => 'Main Title',         'type' => 'text',     'value' => 'Empowering Tanzanian Businesses',          'sort_order' => 2],
            ['page' => 'about', 'key' => 'hero_paragraph_1','label' => 'First Paragraph',    'type' => 'textarea', 'value' => 'Z-pos was built with a simple mission: to provide a world-class Point of Sale system tailored specifically for the East African market. We understand the unique challenges faced by local shop owners, from internet connectivity issues to complex inventory tracking.', 'sort_order' => 3],
            ['page' => 'about', 'key' => 'hero_paragraph_2','label' => 'Second Paragraph',   'type' => 'textarea', 'value' => 'Our team is dedicated to building software that is not only powerful and secure, but also incredibly easy to use. Whether you are running a single hardware store or a chain of supermarkets, Z-pos scales with you.', 'sort_order' => 4],
            ['page' => 'about', 'key' => 'stat_1_number',   'label' => 'Stat 1 Number',      'type' => 'text',     'value' => '5K+',                                     'sort_order' => 5],
            ['page' => 'about', 'key' => 'stat_1_label',    'label' => 'Stat 1 Label',       'type' => 'text',     'value' => 'Active Users',                            'sort_order' => 6],
            ['page' => 'about', 'key' => 'stat_1_desc',     'label' => 'Stat 1 Description', 'type' => 'text',     'value' => 'Trusting our system daily',               'sort_order' => 7],
            ['page' => 'about', 'key' => 'stat_2_number',   'label' => 'Stat 2 Number',      'type' => 'text',     'value' => '24/7',                                    'sort_order' => 8],
            ['page' => 'about', 'key' => 'stat_2_label',    'label' => 'Stat 2 Label',       'type' => 'text',     'value' => 'Customer Support',                        'sort_order' => 9],
            ['page' => 'about', 'key' => 'stat_2_desc',     'label' => 'Stat 2 Description', 'type' => 'text',     'value' => 'We are always here to help',              'sort_order' => 10],
            ['page' => 'about', 'key' => 'stat_3_number',   'label' => 'Stat 3 Number',      'type' => 'text',     'value' => '99%',                                     'sort_order' => 11],
            ['page' => 'about', 'key' => 'stat_3_label',    'label' => 'Stat 3 Label',       'type' => 'text',     'value' => 'Uptime',                                  'sort_order' => 12],
            ['page' => 'about', 'key' => 'stat_3_desc',     'label' => 'Stat 3 Description', 'type' => 'text',     'value' => 'Reliability you can count on',            'sort_order' => 13],

            // ============ PRICING PAGE ============
            ['page' => 'pricing', 'key' => 'page_title',              'label' => 'Page Title',                  'type' => 'text',  'value' => 'Simple, Transparent Pricing',          'sort_order' => 1],
            ['page' => 'pricing', 'key' => 'page_subtitle',           'label' => 'Page Subtitle',               'type' => 'text',  'value' => 'No hidden fees. Scale as you grow.',   'sort_order' => 2],
            ['page' => 'pricing', 'key' => 'starter_name',            'label' => 'Starter Plan Name',           'type' => 'text',  'value' => 'Starter',                              'sort_order' => 3],
            ['page' => 'pricing', 'key' => 'starter_desc',            'label' => 'Starter Plan Description',    'type' => 'text',  'value' => 'Perfect for single retail shops.',     'sort_order' => 4],
            ['page' => 'pricing', 'key' => 'starter_price',           'label' => 'Starter Price',               'type' => 'text',  'value' => 'TZS 15K',                              'sort_order' => 5],
            ['page' => 'pricing', 'key' => 'starter_old_price',       'label' => 'Starter Old Price (strikethrough)', 'type' => 'text', 'value' => 'TZS 20K',                        'sort_order' => 6],
            ['page' => 'pricing', 'key' => 'professional_name',       'label' => 'Professional Plan Name',      'type' => 'text',  'value' => 'Professional',                         'sort_order' => 7],
            ['page' => 'pricing', 'key' => 'professional_desc',       'label' => 'Professional Plan Description','type' => 'text',  'value' => 'For growing multi-branch businesses.', 'sort_order' => 8],
            ['page' => 'pricing', 'key' => 'professional_price',      'label' => 'Professional Price',          'type' => 'text',  'value' => 'TZS 45K',                              'sort_order' => 9],
            ['page' => 'pricing', 'key' => 'professional_old_price',  'label' => 'Professional Old Price',      'type' => 'text',  'value' => 'TZS 50K',                              'sort_order' => 10],
            ['page' => 'pricing', 'key' => 'enterprise_name',         'label' => 'Enterprise Plan Name',        'type' => 'text',  'value' => 'Enterprise',                           'sort_order' => 11],
            ['page' => 'pricing', 'key' => 'enterprise_desc',         'label' => 'Enterprise Plan Description', 'type' => 'text',  'value' => 'Custom solutions for large chains.',   'sort_order' => 12],
            ['page' => 'pricing', 'key' => 'enterprise_price',        'label' => 'Enterprise Price',            'type' => 'text',  'value' => 'TZS 110K',                             'sort_order' => 13],
            ['page' => 'pricing', 'key' => 'enterprise_old_price',    'label' => 'Enterprise Old Price',        'type' => 'text',  'value' => 'TZS 130K',                             'sort_order' => 14],

            // ============ FEATURES PAGE ============
            ['page' => 'features', 'key' => 'page_badge',      'label' => 'Page Badge',            'type' => 'text',     'value' => 'Core Features',                                      'sort_order' => 1],
            ['page' => 'features', 'key' => 'page_title',      'label' => 'Page Title',            'type' => 'text',     'value' => 'Everything you need to scale',                       'sort_order' => 2],
            ['page' => 'features', 'key' => 'page_subtitle',   'label' => 'Page Subtitle',         'type' => 'text',     'value' => "From single shops to nationwide chains, we've got you covered.", 'sort_order' => 3],
            ['page' => 'features', 'key' => 'feat_1_title',    'label' => 'Feature 1 Title',       'type' => 'text',     'value' => 'Lightning Fast POS',                                 'sort_order' => 4],
            ['page' => 'features', 'key' => 'feat_1_desc',     'label' => 'Feature 1 Description', 'type' => 'textarea', 'value' => 'Process sales in seconds using barcode scanners, shortcuts, and an intuitive touch-friendly interface designed for speed.', 'sort_order' => 5],
            ['page' => 'features', 'key' => 'feat_2_title',    'label' => 'Feature 2 Title',       'type' => 'text',     'value' => 'Smart Inventory',                                    'sort_order' => 6],
            ['page' => 'features', 'key' => 'feat_2_desc',     'label' => 'Feature 2 Description', 'type' => 'textarea', 'value' => 'Track stock across multiple branches in real-time. Get low-stock alerts, manage expiry dates, and handle seamless transfers.', 'sort_order' => 7],
            ['page' => 'features', 'key' => 'feat_3_title',    'label' => 'Feature 3 Title',       'type' => 'text',     'value' => 'Advanced Analytics',                                 'sort_order' => 8],
            ['page' => 'features', 'key' => 'feat_3_desc',     'label' => 'Feature 3 Description', 'type' => 'textarea', 'value' => 'Make data-driven decisions with detailed reports on daily sales, profit margins, employee performance, and top-selling items.', 'sort_order' => 9],
            ['page' => 'features', 'key' => 'feat_4_title',    'label' => 'Feature 4 Title',       'type' => 'text',     'value' => 'Enterprise Security',                                'sort_order' => 10],
            ['page' => 'features', 'key' => 'feat_4_desc',     'label' => 'Feature 4 Description', 'type' => 'textarea', 'value' => 'Role-based access control ensures staff only see what they need to. Activity logs track every void, discount, and deletion.', 'sort_order' => 11],
            ['page' => 'features', 'key' => 'feat_5_title',    'label' => 'Feature 5 Title',       'type' => 'text',     'value' => 'Multi-Payment Ready',                                'sort_order' => 12],
            ['page' => 'features', 'key' => 'feat_5_desc',     'label' => 'Feature 5 Description', 'type' => 'textarea', 'value' => 'Accept Cash, Cards, and Mobile Money (M-Pesa, Tigo Pesa, Airtel Money) seamlessly in a single unified checkout flow.', 'sort_order' => 13],
            ['page' => 'features', 'key' => 'feat_6_title',    'label' => 'Feature 6 Title',       'type' => 'text',     'value' => 'Hardware Integrated',                                'sort_order' => 14],
            ['page' => 'features', 'key' => 'feat_6_desc',     'label' => 'Feature 6 Description', 'type' => 'textarea', 'value' => 'Plug and play with thermal receipt printers, cash drawers, customer displays, and external barcode scanners without hassle.', 'sort_order' => 15],
            ['page' => 'features', 'key' => 'feat_7_title',    'label' => 'Feature 7 Title',       'type' => 'text',     'value' => 'Professional Invoicing',                             'sort_order' => 16],
            ['page' => 'features', 'key' => 'feat_7_desc',     'label' => 'Feature 7 Description', 'type' => 'textarea', 'value' => 'Generate, print, and share beautiful A4 invoices for your customers instantly. Keep track of paid and unpaid invoices easily.', 'sort_order' => 17],
            ['page' => 'features', 'key' => 'feat_8_title',    'label' => 'Feature 8 Title',       'type' => 'text',     'value' => 'Custom Warranties',                                  'sort_order' => 18],
            ['page' => 'features', 'key' => 'feat_8_desc',     'label' => 'Feature 8 Description', 'type' => 'textarea', 'value' => "Issue professional digital warranty certificates to your customers with 10 customizable themes, complete with your shop's logo.", 'sort_order' => 19],
            ['page' => 'features', 'key' => 'feat_9_title',    'label' => 'Feature 9 Title',       'type' => 'text',     'value' => 'Digital Receipts',                                   'sort_order' => 20],
            ['page' => 'features', 'key' => 'feat_9_desc',     'label' => 'Feature 9 Description', 'type' => 'textarea', 'value' => 'Provide modern PDF receipts that can be downloaded or shared directly to customers via WhatsApp or Email, saving on paper costs.', 'sort_order' => 21],

            // ============ CONTACT PAGE ============
            ['page' => 'contact', 'key' => 'page_title',    'label' => 'Page Title',    'type' => 'text', 'value' => 'Get in Touch',                                    'sort_order' => 1],
            ['page' => 'contact', 'key' => 'page_subtitle', 'label' => 'Page Subtitle', 'type' => 'text', 'value' => "We'd love to hear from you. Drop us a line below.", 'sort_order' => 2],
            ['page' => 'contact', 'key' => 'address',       'label' => 'Office Address', 'type' => 'text', 'value' => 'Uhuru Plaza Kkoo, Dar es Salaam, Tanzania',       'sort_order' => 3],
            ['page' => 'contact', 'key' => 'email',         'label' => 'Email Address',  'type' => 'text', 'value' => 'info@z-pos.co.tz',                               'sort_order' => 4],
            ['page' => 'contact', 'key' => 'phone',         'label' => 'Phone Numbers',  'type' => 'text', 'value' => '+255 683 628 142 | +255 716 465 511',             'sort_order' => 5],

            // ============ PRIVACY PAGE ============
            ['page' => 'privacy', 'key' => 'page_title', 'label' => 'Page Title',   'type' => 'text',     'value' => 'Privacy Policy',               'sort_order' => 1],
            ['page' => 'privacy', 'key' => 'content',    'label' => 'Page Content', 'type' => 'textarea', 'value' => 'Your privacy is important to us. Z-pos collects only the data necessary to provide our services. We do not sell your data to third parties. All data is stored securely and encrypted at rest. For any privacy concerns, contact us at info@z-pos.co.tz.', 'sort_order' => 2],

            // ============ TERMS PAGE ============
            ['page' => 'terms', 'key' => 'page_title', 'label' => 'Page Title',   'type' => 'text',     'value' => 'Terms & Conditions',              'sort_order' => 1],
            ['page' => 'terms', 'key' => 'content',    'label' => 'Page Content', 'type' => 'textarea', 'value' => 'By using Z-pos, you agree to our terms of service. You must not use Z-pos for illegal activities. We reserve the right to suspend accounts that violate our terms. Subscriptions are billed monthly and are non-refundable. For full terms, contact our support team.', 'sort_order' => 2],

            // ============ COOKIES PAGE ============
            ['page' => 'cookies', 'key' => 'page_title', 'label' => 'Page Title',   'type' => 'text',     'value' => 'Cookie Policy',              'sort_order' => 1],
            ['page' => 'cookies', 'key' => 'content',    'label' => 'Page Content', 'type' => 'textarea', 'value' => 'Z-pos uses cookies to improve your experience. Essential cookies are required for the system to function. Analytics cookies help us understand how users interact with our platform. You can manage cookie preferences in your browser settings.', 'sort_order' => 2],
        ];

        foreach ($settings as $setting) {
            CmsSetting::updateOrCreate(
                ['page' => $setting['page'], 'key' => $setting['key']],
                $setting
            );
        }
    }
}
