<?php $title = 'Neues Produkt'; ?>
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Neues Produkt anlegen</h1>
        
        <form action="/products" method="POST" class="space-y-6">
            <!-- Produktname -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Produktname *</label>
                <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300">
            </div>
            
            <!-- SKU / Artikelnummer -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Artikelnummer (intern)</label>
                    <input type="text" name="sku" class="mt-1 block w-full rounded-md border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Hersteller Art.-Nr.</label>
                    <input type="text" name="manufacturer_sku" class="mt-1 block w-full rounded-md border-gray-300">
                </div>
            </div>
            
            <!-- Preise -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Preis (Brutto) *</label>
                    <input type="number" step="0.01" name="base_price" required class="mt-1 block w-full rounded-md border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kostenpreis</label>
                    <input type="number" step="0.01" name="cost_price" class="mt-1 block w-full rounded-md border-gray-300">
                </div>
            </div>
            
            <!-- Steuer -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Steuersatz (%)</label>
                <select name="tax_rate" class="mt-1 block w-full rounded-md border-gray-300">
                    <option value="20">20% Standard</option>
                    <option value="10">10% Reduziert</option>
                    <option value="0">0% Befreit</option>
                </select>
            </div>
            
            <!-- Einheit -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Einheit</label>
                <select name="unit_id" class="mt-1 block w-full rounded-md border-gray-300">
                    <option value="1">Stück (Stk)</option>
                    <option value="2">Kilogramm (kg)</option>
                    <option value="3">Liter (l)</option>
                    <option value="4">Meter (m)</option>
                    <option value="5">Stunde (h)</option>
                </select>
            </div>
            
            <!-- Sub-Produkte -->
            <div class="border-t pt-4">
                <h3 class="text-lg font-medium mb-4">Sub-Produkte (optional)</h3>
                <div id="sub-products" class="space-y-2">
                    <div class="flex items-center space-x-2">
                        <input type="text" placeholder="Sub-Produkt Name" class="flex-1 rounded-md border-gray-300">
                        <input type="number" placeholder="Menge" step="0.01" class="w-24 rounded-md border-gray-300">
                        <input type="number" placeholder="Preis" step="0.01" class="w-24 rounded-md border-gray-300">
                        <button type="button" class="text-red-600">×</button>
                    </div>
                </div>
                <button type="button" onclick="addSubProduct()" class="text-sm text-blue-600">+ Sub-Produkt hinzufügen</button>
            </div>
            
            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                Produkt anlegen
            </button>
        </form>
    </div>
</div>

<script>
function addSubProduct() {
    const container = document.getElementById('sub-products');
    const div = document.createElement('div');
    div.className = 'flex items-center space-x-2';
    div.innerHTML = `
        <input type="text" name="sub_products[name][]" placeholder="Sub-Produkt Name" class="flex-1 rounded-md border-gray-300">
        <input type="number" name="sub_products[quantity][]" placeholder="Menge" step="0.01" class="w-24 rounded-md border-gray-300">
        <input type="number" name="sub_products[price][]" placeholder="Preis" step="0.01" class="w-24 rounded-md border-gray-300">
        <button type="button" onclick="this.parentElement.remove()" class="text-red-600">×</button>
    `;
    container.appendChild(div);
}
</script>