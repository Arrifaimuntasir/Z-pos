@extends('layouts.landing')
@section('title', 'Privacy Policy - Z-pos')
@section('content')
<div style="padding-top: 100px;">
    <!-- Privacy Policy -->
    <section class="py-5 bg-light">
        <div class="container py-5 bg-white shadow-sm rounded-4 p-4 p-md-5" style="max-width: 900px;" data-aos="fade-up">
            <h1 class="fw-bold text-primary mb-4">{{ __('Privacy Policy') }}</h1>
            <p class="text-muted mb-4">Last Updated: {{ date('F Y') }}</p>

            <div style="line-height: 1.8; color: #333; font-size: 1.1rem;" class="mt-4">
                <h4 class="text-dark fw-bold mt-5 mb-3">{{ __('1. Introduction') }}</h4>
                <p>{{ __('Welcome to Z-pos. We respect your privacy and are committed to protecting your personal data. This privacy policy will inform you as to how we look after your personal data when you visit our website and tell you about your privacy rights and how the law protects you.') }}</p>

                <h4 class="text-dark fw-bold mt-5 mb-3">{{ __('2. The Data We Collect About You') }}</h4>
                <p>{{ __('We may collect, use, store and transfer different kinds of personal data about you which we have grouped together as follows:') }}</p>
                <ul>
                    <li><strong>{{ __('Identity Data') }}</strong> {{ __('includes first name, maiden name, last name, username or similar identifier.') }}</li>
                    <li><strong>{{ __('Contact Data') }}</strong> {{ __('includes billing address, delivery address, email address and telephone numbers.') }}</li>
                    <li><strong>{{ __('Financial Data') }}</strong> includes bank account and payment card details (processed securely via our payment partners).</li>
                    <li><strong>{{ __('Transaction Data') }}</strong> {{ __('includes details about payments to and from you and other details of products and services you have purchased from us.') }}</li>
                    <li><strong>{{ __('Technical Data') }}</strong> includes internet protocol (IP) address, your login data, browser type and version, time zone setting and location, browser plug-in types and versions, operating system and platform, and other technology on the devices you use to access this website.</li>
                </ul>

                <h4 class="text-dark fw-bold mt-5 mb-3">{{ __('3. How We Use Your Personal Data') }}</h4>
                <p>{{ __('We will only use your personal data when the law allows us to. Most commonly, we will use your personal data in the following circumstances:') }}</p>
                <ul>
                    <li>{{ __('Where we need to perform the contract we are about to enter into or have entered into with you.') }}</li>
                    <li>Where it is necessary for our legitimate interests (or those of a third party) and your interests and fundamental rights do not override those interests.</li>
                    <li>{{ __('Where we need to comply with a legal obligation.') }}</li>
                </ul>

                <h4 class="text-dark fw-bold mt-5 mb-3">{{ __('4. Data Security') }}</h4>
                <p>{{ __('We have put in place appropriate security measures to prevent your personal data from being accidentally lost, used or accessed in an unauthorised way, altered or disclosed. In addition, we limit access to your personal data to those employees, agents, contractors and other third parties who have a business need to know.') }}</p>

                <h4 class="text-dark fw-bold mt-5 mb-3">{{ __('5. Data Retention') }}</h4>
                <p>{{ __('We will only retain your personal data for as long as reasonably necessary to fulfil the purposes we collected it for, including for the purposes of satisfying any legal, regulatory, tax, accounting or reporting requirements.') }}</p>

                <h4 class="text-dark fw-bold mt-5 mb-3">{{ __('6. Contact Details') }}</h4>
                <p>{{ __('If you have any questions about this privacy policy or our privacy practices, please contact us at:') }}</p>
                <p>Email: info@z-pos.co.tz<br>
                Phone: +255 683 628 142 / +255 716 465 511<br>
                {{ __('Address: Uhuru Plaza Kkoo, Dar es Salaam, Tanzania') }}</p>
            </div>
        </div>
    </section>
</div>
@endsection
