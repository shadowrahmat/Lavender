<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\SitePage;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        // FAQs
        $faqs = [
            ['question' => 'Where is Lavender Food & Bakery located?', 'answer' => 'We are located at AJ Height, Uttar Badda, Cha-72, 1/D Progoti Shoroni, Dhaka 1212. You can visit us daily from 9:00 AM to 11:00 PM.'],
            ['question' => 'Do you offer delivery?', 'answer' => 'Yes, we offer delivery to most areas in Dhaka. Delivery charge is ৳60, and orders above ৳500 get free delivery.'],
            ['question' => 'Can I pre-order a custom cake?', 'answer' => 'Absolutely! We accept custom cake orders for birthdays, anniversaries, and events. Please use our corporate order form or contact us directly at least 48 hours in advance.'],
            ['question' => 'Are your bakery items fresh every day?', 'answer' => 'Yes! All our bakery items are prepared fresh daily. We start baking early in the morning to ensure freshness for our customers.'],
            ['question' => 'Do you support corporate or bulk orders?', 'answer' => 'Yes, we have a dedicated corporate order service. We supply bakery boxes, snacks, and pastries for offices, events, and corporate gatherings. Please use our corporate order form.'],
            ['question' => 'What payment methods do you accept?', 'answer' => 'We currently accept Cash on Delivery (COD). bKash and Nagad payment options are coming soon.'],
            ['question' => 'How do I track my order?', 'answer' => 'After placing your order, you will receive an order number. Use our Order Tracking page to check the current status of your order.'],
            ['question' => 'Do you have Ramadan specials?', 'answer' => 'Yes! During Ramadan, we offer a special iftar menu with traditional sweets and snacks. Check our Ramadan Menu category in the shop.'],
        ];

        foreach ($faqs as $i => $faq) {
            Faq::firstOrCreate(
                ['question' => $faq['question']],
                array_merge($faq, ['sort_order' => $i, 'is_active' => true])
            );
        }

        // Terms & Conditions
        SitePage::updateOrCreate(['slug' => 'terms'], [
            'title'        => 'Terms & Conditions',
            'last_updated' => 'January 2024',
            'sections'     => [
                ['heading' => '1. Acceptance of Terms', 'body' => 'By using our website and services, you agree to these Terms & Conditions. If you do not agree, please do not use our services.'],
                ['heading' => '2. Orders & Payment', 'body' => 'All orders are subject to availability. We reserve the right to cancel or refuse any order. Payment must be completed before order fulfillment for online payment methods.'],
                ['heading' => '3. Delivery', 'body' => 'Delivery times are estimates and may vary based on location and demand. We strive to deliver within the estimated time but cannot guarantee exact delivery times.'],
                ['heading' => '4. Refund Policy', 'body' => 'Due to the perishable nature of bakery items, we do not accept returns. If there is a quality issue, please contact us within 2 hours of delivery.'],
                ['heading' => '5. Contact', 'body' => 'For any queries regarding these terms, please contact us at hello@lavenderfoodnbakery.com or +8801730-728954.'],
            ],
        ]);

        // Privacy Policy
        SitePage::updateOrCreate(['slug' => 'privacy'], [
            'title'        => 'Privacy Policy',
            'last_updated' => 'January 2024',
            'sections'     => [
                ['heading' => '1. Information We Collect', 'body' => 'When you use Lavender Food & Bakery\'s website, we collect information including your name, email address, phone number, and delivery address when you place an order or create an account.'],
                ['heading' => '2. How We Use Your Information', 'body' => 'We use your information to process orders, provide customer support, send order updates, and improve our services. We do not sell your personal information to third parties.'],
                ['heading' => '3. Data Security', 'body' => 'We implement appropriate security measures to protect your personal information. All sensitive data is encrypted and stored securely.'],
                ['heading' => '4. Contact Us', 'body' => 'If you have any questions about our Privacy Policy, please contact us at hello@lavenderfoodnbakery.com.'],
            ],
        ]);
    }
}
