<?php $title = 'Neuer Kunde'; ?>
<div class="max-w-2xl mx-auto py-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Neuen Kunden anlegen</h1>
        <form action="/customers" method="POST" class="space-y-4">
            <input type="text" name="customer_number" placeholder="Kundennummer" required 
                   class="w-full border rounded px-3 py-2">
            <input type="text" name="company_name" placeholder="Firmenname" 
                   class="w-full border rounded px-3 py-2">
            <input type="email" name="email" placeholder="E-Mail" required 
                   class="w-full border rounded px-3 py-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                Speichern
            </button>
        </form>
    </div>
</div>
