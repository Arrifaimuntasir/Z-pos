@extends('layouts.landing')
@section('title', 'Cookie Policy - Z-pos')
@section('content')
<div style="padding-top: 100px;">
    <!-- Cookie Policy -->
    <section class="py-5 bg-light">
        <div class="container py-5 bg-white shadow-sm rounded-4 p-4 p-md-5" style="max-width: 900px;" data-aos="fade-up">
            <h1 class="fw-bold text-primary mb-4">Cookie Policy</h1>
            <p class="text-muted mb-4">Last Updated: {{ date('F Y') }}</p>

            <div style="line-height: 1.8; color: #333; font-size: 1.1rem;" class="mt-4">
                <h4 class="text-dark fw-bold mt-5 mb-3">1. What Are Cookies</h4>
                <p>Cookies are small pieces of text sent by your web browser by a website you visit. A cookie file is stored in your web browser and allows the Service or a third-party to recognize you and make your next visit easier and the Service more useful to you.</p>

                <h4 class="text-dark fw-bold mt-5 mb-3">2. How Z-pos Uses Cookies</h4>
                <p>When you use and access the Service, we may place a number of cookies files in your web browser. We use cookies for the following purposes:</p>
                <ul>
                    <li><strong>Essential Cookies:</strong> To authenticate users and prevent fraudulent use of user accounts. These are necessary for the website to function properly.</li>
                    <li><strong>Preferences Cookies:</strong> To remember information that changes the way the Service behaves or looks, such as your "remember me" functionality on login.</li>
                    <li><strong>Analytics Cookies:</strong> To track information how the Service is used so that we can make improvements. We may also use analytics cookies to test new pages, features or new functionality of the Service to see how our users react to them.</li>
                </ul>

                <h4 class="text-dark fw-bold mt-5 mb-3">3. Third-party Cookies</h4>
                <p>In addition to our own cookies, we may also use various third-parties cookies to report usage statistics of the Service and deliver advertisements on and through the Service.</p>

                <h4 class="text-dark fw-bold mt-5 mb-3">4. What Are Your Choices Regarding Cookies</h4>
                <p>If you'd like to delete cookies or instruct your web browser to delete or refuse cookies, please visit the help pages of your web browser.</p>
                <p>Please note, however, that if you delete cookies or refuse to accept them, you might not be able to use all of the features we offer, you may not be able to store your preferences, and some of our pages might not display properly.</p>

                <h4 class="text-dark fw-bold mt-5 mb-3">5. More Information</h4>
                <p>If you are looking for more information, you can contact us through our email: info@z-pos.co.tz</p>
            </div>
        </div>
    </section>
</div>
@endsection
