@extends('layouts.app')

@section('title', 'Privacy Notice')

@section('content')
<div class="container mx-auto px-4 py-12 flex flex-col md:flex-row items-start gap-8">
    <!-- Left Logo -->
    <div class="flex-shrink-0">
        <img src="{{ asset('assets/logo-green-yellow.png') }}" alt="Afreximbank Logo" class="w-48 md:w-60">
    </div>

    <!-- Content -->
    <div class="flex-1">
        <h1 class="text-3xl md:text-4xl font-light text-[#004d47] mb-6">Privacy Notice</h1>
        <p class="text-gray-800 leading-relaxed mb-4">
            By using the Afreximbank African Trade Center Visitor Management System (“AATC VMS”), certain information
            about visitors and their activities will be collected and stored on Afreximbank’s secure servers. This may
            include Internet Protocol (IP) addresses, login history, navigation through the system, device and software
            details, and time and date of access. This technical data does not, by itself, identify an individual.
        </p>
        <p class="text-gray-800 leading-relaxed mb-4">
            When visitors or authorized users provide personal details, such as full name, company name, contact information,
            identification documents, and visit purpose, the data will be used solely for operational, security, and compliance
            purposes related to managing access to the African Trade Center premises.
        </p>
        <p class="text-gray-800 leading-relaxed">
            All collected information will be processed internally to facilitate visitor verification, access control, and
            compliance reporting. Afreximbank does not sell, rent, or share this information with unaffiliated third parties
            without consent, except as required by law or regulatory mandate. While robust measures are in place to protect data,
            Afreximbank assumes no liability for unauthorized access or misuse beyond its reasonable control.
        </p>
    </div>
</div>
@endsection
