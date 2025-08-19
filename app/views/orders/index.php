<?php $title = 'Aufträge'; ?>
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Aufträge</h1>
                <a href="/orders/create" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Neuer Auftrag
                </a>
            </div>
        </div>
        
        <div class="p-6">
            <div class="text-center py-12">
                <div class="mx-auto h-24 w-24 text-blue-500 mb-4">
                    <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h2M9 12h2m-6 7h2m11-2v6a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v3m-2 4v6m-4-6h6"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Noch keine Aufträge</h3>
                <p class="text-gray-500 mt-2">Erstellen Sie Ihren ersten Auftrag.</p>
                <div class="mt-6">
                    <a href="/orders/create" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Auftrag erstellen
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>