@extends('layouts.app')

@section('title', 'Terms of Use')

@section('content')
<div class="container mx-auto px-4 py-12 flex flex-col md:flex-row items-start gap-8">
    <!-- Left Logo -->
    <div class="flex-shrink-0">
        <img src="{{ asset('assets/logo-green-yellow.png') }}" alt="Afreximbank Logo" class="w-48 md:w-60">
    </div>

    <!-- Content -->
    <div class="flex-1">
        <h1 class="text-3xl md:text-4xl font-light text-[#004d47] mb-6">Terms of Use</h1>
        <h2 class="text-base uppercase tracking-widest font-medium mb-4">
            Terms and Conditions of Use of AATC Visitor Management System
        </h2>

        <h3 class="font-semibold text-lg mb-2">Disclaimers</h3>
        <p class="text-gray-800 leading-relaxed mb-4">
            The use of the Afreximbank African Trade Center Visitor Management System (“AATC VMS”) constitutes agreement
            with the following terms and conditions:
        </p>

        <ol class="list-[lower-alpha] pl-6 mb-6 space-y-2">
            <li>
                The AATC VMS is an operational tool for registering, managing, and monitoring visitors to the African Trade Center.
                Access is provided to authorized staff, tenants, and service providers solely for security and visitor management purposes.
            </li>
            <li>
                Afreximbank administers and maintains the AATC VMS. All data, configurations, and materials stored in the system
                remain the property of Afreximbank and are subject to these Terms and Conditions.
            </li>
            <li>
                Users are responsible for ensuring the accuracy and completeness of all visitor data entered into the system.
                Afreximbank shall not be held liable for any consequences arising from inaccurate or false information provided.
            </li>
        </ol>

        <h3 class="font-semibold text-lg mb-2">System & Liability Disclaimer</h3>
        <p class="text-gray-800 leading-relaxed">
            The AATC VMS is provided “as is” without warranties of any kind, whether express or implied, including but not limited to
            warranties of accuracy, availability, or fitness for a particular purpose. Afreximbank may update, modify, or suspend the system
            at any time without notice. Under no circumstances shall Afreximbank or its affiliates be liable for any direct, indirect,
            incidental, special, or consequential damages arising from the use or inability to use the system, even if Afreximbank has been
            advised of the possibility of such damages. Use of the system is at the user’s sole risk.
        </p>
    </div>
</div>
@endsection
