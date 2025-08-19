<?php $title = 'Einstellungen'; ?>
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Einstellungen</h1>
        
        <form class="space-y-6">
            <!-- Firmendaten -->
            <div>
                <h2 class="text-lg font-medium mb-4">Firmendaten</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Firmenname</label>
                        <input type="text" value="Österreichische Firma GmbH" class="mt-1 block w-full rounded-md border-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Adresse</label>
                        <input type="text" value="Musterstraße 1, 1234 Wien" class="mt-1 block w-full rounded-md border-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">E-Mail</label>
                        <input type="email" value="office@firma.at" class="mt-1 block w-full rounded-md border-gray-300">
                    </div>
                </div>
            </div>
            
            <!-- Theme -->
            <div>
                <h2 class="text-lg font-medium mb-4">Design</h2>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Theme</label>
                    <select class="mt-1 block w-full rounded-md border-gray-300">
                        <option value="tremor">Tremor (Modern)</option>
                        <option value="flakes">Flakes (Klassisch)</option>
                    </select>
                </div>
            </div>
            
            <!-- Backup -->
            <div>
                <h2 class="text-lg font-medium mb-4">Backup</h2>
                <div class="space-y-2">
                    <button type="button" class="btn btn-secondary">
                        Backup jetzt erstellen
                    </button>
                    <button type="button" class="btn btn-secondary">
                        Backup wiederherstellen
                    </button>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">
                Einstellungen speichern
            </button>
        </form>
    </div>
</div>