@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Online Training',
    'subtitle' => 'Virtual training programmes and webinars for ASEANAPOL member agencies.',
    'breadcrumbs' => [
        ['label' => 'Home',            'url' => route('landing',        ['locale' => app()->getLocale()])],
        ['label' => 'Training',        'url' => route('training.index', ['locale' => app()->getLocale()])],
        ['label' => 'Online Training', 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="overflow-x-auto rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-primary text-white">
                        <th class="px-6 py-4 font-semibold w-12 text-center">No</th>
                        <th class="px-6 py-4 font-semibold">Title</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <tr class="bg-white dark:bg-dark-card hover:bg-blue-50 dark:hover:bg-primary/10 transition-colors">
                        <td class="px-6 py-5 text-center font-semibold text-primary dark:text-accent">1</td>
                        <td class="px-6 py-5 text-gray-800 dark:text-gray-200 font-medium">Countering Embezzlement by Means of IT Technologies</td>
                        <td class="px-6 py-5 text-gray-600 dark:text-gray-400 whitespace-nowrap">30 March &ndash; 17 April 2026</td>
                    </tr>
                    <tr class="bg-gray-50 dark:bg-dark-surface hover:bg-blue-50 dark:hover:bg-primary/10 transition-colors">
                        <td class="px-6 py-5 text-center font-semibold text-primary dark:text-accent">2</td>
                        <td class="px-6 py-5 text-gray-800 dark:text-gray-200 font-medium">Countering the Activities of Extremist and Terrorist Structures on the Internet</td>
                        <td class="px-6 py-5 text-gray-600 dark:text-gray-400 whitespace-nowrap">6 &ndash; 10 April 2026</td>
                    </tr>
                    <tr class="bg-white dark:bg-dark-card hover:bg-blue-50 dark:hover:bg-primary/10 transition-colors">
                        <td class="px-6 py-5 text-center font-semibold text-primary dark:text-accent">3</td>
                        <td class="px-6 py-5 text-gray-800 dark:text-gray-200 font-medium">Counteraction to Illicit Drug Trafficking</td>
                        <td class="px-6 py-5 text-gray-600 dark:text-gray-400 whitespace-nowrap">7 &ndash; 10 April 2026</td>
                    </tr>
                    <tr class="bg-gray-50 dark:bg-dark-surface hover:bg-blue-50 dark:hover:bg-primary/10 transition-colors">
                        <td class="px-6 py-5 text-center font-semibold text-primary dark:text-accent">4</td>
                        <td class="px-6 py-5 text-gray-800 dark:text-gray-200 font-medium">Cryptocurrencies &mdash; Types of Cryptocurrencies, Bitcoins and Electronic Wallets, the Block Chain Method. The Use of Cryptocurrencies in the Financing of Terrorism, in the &ldquo;Shadow Web&rdquo;, Transaction Tracking, Methods of Confiscation and Possibilities of Investigation</td>
                        <td class="px-6 py-5 text-gray-600 dark:text-gray-400 whitespace-nowrap">18 &ndash; 22 May 2026</td>
                    </tr>
                    <tr class="bg-white dark:bg-dark-card hover:bg-blue-50 dark:hover:bg-primary/10 transition-colors">
                        <td class="px-6 py-5 text-center font-semibold text-primary dark:text-accent">5</td>
                        <td class="px-6 py-5 text-gray-800 dark:text-gray-200 font-medium">Information Protection, Solving of ICT-Crimes, Counteraction to Illicit Transactions with the Use of Digital Assets. Peculiarities of Investigation of Crimes in the Sphere of Information and Telecommunication Technologies</td>
                        <td class="px-6 py-5 text-gray-600 dark:text-gray-400 whitespace-nowrap">25 &ndash; 29 May 2026</td>
                    </tr>
                    <tr class="bg-gray-50 dark:bg-dark-surface hover:bg-blue-50 dark:hover:bg-primary/10 transition-colors">
                        <td class="px-6 py-5 text-center font-semibold text-primary dark:text-accent">6</td>
                        <td class="px-6 py-5 text-gray-800 dark:text-gray-200 font-medium">Techniques for Investigating Cyberspace Crimes</td>
                        <td class="px-6 py-5 text-gray-600 dark:text-gray-400 whitespace-nowrap">15 &ndash; 26 June 2026</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</section>
@endsection
