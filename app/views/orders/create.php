<?php $title = 'Neuer Auftrag'; ?>
<div class="max-w-2xl mx-auto py-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Neuen Auftrag erstellen</h1>
        <form action="/orders" method="POST" class="space-y-4">
            <input type="text" name="order_number" placeholder="Auftragsnummer" required 
                   class="w-full border rounded px-3 py-2">
            <input type="date" name="order_date" required 
                   class="w-full border rounded px-3 py-2">
            <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded">
                Erstellen
            </button>
        </form>
    </div>
</div>
