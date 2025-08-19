<?php $title = 'Dashboard - Austrian ERP'; ?>
<div class="max-w-7xl mx-auto">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">Aufträge heute</h3>
            <p class="text-3xl font-bold text-blue-600 mt-2">0</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">Neue Kunden</h3>
            <p class="text-3xl font-bold text-green-600 mt-2">0</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">Umsatz Monat</h3>
            <p class="text-3xl font-bold text-purple-600 mt-2">€0,00</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">Offene Rechnungen</h3>
            <p class="text-3xl font-bold text-red-600 mt-2">0</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="/customers/create" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">Neuer Kunde</h3>
                <p class="mt-1 text-sm text-gray-500">Kundenkonto anlegen</p>
            </div>
        </a>
        
        <a href="/products/create" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">Neues Produkt</h3>
                <p class="mt-1 text-sm text-gray-500">Produkt anlegen</p>
            </div>
        </a>
        
        <a href="/orders/create" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">Neuer Auftrag</h3>
                <p class="mt-1 text-sm text-gray-500">Auftrag erstellen</p>
            </div>
        </a>
    </div>
</div>