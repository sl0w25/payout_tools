<x-filament-widgets::widget>
    <x-filament::section>
    <div class="bg-white p-4 rounded-lg shadow-md">
    <h2 class="text-xl font-semibold">Payout Statistics</h2>
    <div wire:poll.3s class="mt-4">
        <p>Total Beneficiaries: {{ $data['fam_id'] }}</p>
        <p>Total Unpaid: {{ $data['unpaid'] }}</p>
        <p>Total Paid: {{ $data['paid'] }}</p>
        <p>Total Amount Disburse: {{ $data['amount'] }}</p>
    </div>
    </div>
    </x-filament::section>
</x-filament-widgets::widget>
