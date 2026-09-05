@extends('layouts.landing')
@section('title', 'Terms of Service - Z-pos')
@section('content')
<div style="padding-top: 100px;">
    <!-- Terms of Service -->
    <section class="py-5 bg-light">
        <div class="container py-5 bg-white shadow-sm rounded-4 p-4 p-md-5" style="max-width: 900px;" data-aos="fade-up">
            <h1 class="fw-bold text-primary mb-4">{{ __('Terms of Service') }}</h1>
            <p class="text-muted mb-4">Last Updated: {{ date('F Y') }}</p>

            <div style="line-height: 1.8; color: #333; font-size: 1.1rem;" class="mt-4">
                <h4 class="text-dark fw-bold mt-5 mb-3">{{ __('1. Acceptance of Terms') }}</h4>
                <p>By accessing and using Z-pos ("the Service"), you agree to be bound by these Terms of Service. If you do not agree to these terms, please do not use the Service.</p>

                <h4 class="text-dark fw-bold mt-5 mb-3">{{ __('2. Description of Service') }}</h4>
                <p>Z-pos provides cloud-based point of sale (POS) and inventory management software for retail businesses in Tanzania and East Africa. The Service includes software updates, hosting, data storage, and technical support as defined by your subscription plan.</p>

                <h4 class="text-dark fw-bold mt-5 mb-3">{{ __('3. User Accounts') }}</h4>
                <p>{{ __('To use Z-pos, you must register for an account. You agree to provide accurate, current, and complete information during the registration process and to update such information to keep it accurate, current, and complete. You are responsible for safeguarding your password and for all activities that occur under your account.') }}</p>

                <h4 class="text-dark fw-bold mt-5 mb-3">{{ __('4. Subscription and Payments') }}</h4>
                <ul>
                    <li>The Service is billed on a subscription basis (monthly or annually).</li>
                    <li>{{ __('Payments are non-refundable, except as required by law.') }}</li>
                    <li>{{ __('If your payment method fails or your account is past due, we may suspend your access to the Service.') }}</li>
                </ul>

                <h4 class="text-dark fw-bold mt-5 mb-3">{{ __('5. Data Ownership and Privacy') }}</h4>
                <p>{{ __('You retain all rights to your business data entered into Z-pos. We claim no intellectual property rights over the material you provide to the Service. Your use of the Service is also governed by our Privacy Policy.') }}</p>

                <h4 class="text-dark fw-bold mt-5 mb-3">{{ __('6. Service Availability') }}</h4>
                <p>We strive to ensure the Service is available 24/7. However, we do not guarantee uninterrupted access. The Service may be temporarily unavailable for scheduled maintenance or due to unforeseen circumstances beyond our control.</p>

                <h4 class="text-dark fw-bold mt-5 mb-3">{{ __('7. Limitation of Liability') }}</h4>
                <p>{{ __('In no event shall Z-pos, its directors, employees, or partners be liable for any indirect, incidental, special, consequential or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from your access to or use of or inability to access or use the Service.') }}</p>

                <h4 class="text-dark fw-bold mt-5 mb-3">{{ __('8. Changes to Terms') }}</h4>
                <p>{{ __('We reserve the right to modify or replace these Terms at any time. We will provide notice of any significant changes. Your continued use of the Service after any such changes constitutes your acceptance of the new Terms.') }}</p>
            </div>
        </div>
    </section>
</div>
@endsection
