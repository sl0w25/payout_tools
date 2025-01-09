<x-filament-widgets::widget>
    <x-filament::section>
        <!-- Payout Statistics Section -->
        <div class="bg-white p-4 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold">Payout Statistics</h2>
            <div wire:poll.3s class="mt-4">
                <p>Total Beneficiaries: {{ $data['fam_id'] }}</p>
                <p>Male: {{ $data['male'] }} &nbsp;|&nbsp; Female: {{ $data['female'] }}</p>
                <p>Total Unpaid: {{ $data['unpaid'] }}</p>
                <p>Total Paid: {{ $data['paid'] }}</p>
                <p>Total Amount Disbursed: {{ $data['amount'] }}</p>
            </div>
        </div>

        <!-- Table Section -->
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Province
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Total Unpaid
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Total Paid
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Total Beneficiaries
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($provinces as $index => $province)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $province['name'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $province['unpaid'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $province['paid'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $province['bene'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button 
                                class="text-blue-600 hover:text-blue-900" 
                                x-data="{ open: false }" 
                                @click="open = !open">
                                View
                            </button>
                        </td>
                    </tr>
                    <tr x-show="open" x-data="{ open: false }">
                        <td colspan="5" class="bg-gray-50 px-6 py-4">
                            <h4 class="font-semibold mb-2">Detailed Data for {{ $province['name'] }}</h4>
                            <table class="table-auto w-full text-left border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2">Detail Type</th>
                                        <th class="px-4 py-2">Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-4 py-2">Example Detail 1</td>
                                        <td class="px-4 py-2">Value 1</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2">Example Detail 2</td>
                                        <td class="px-4 py-2">Value 2</td>
                                    </tr>
                                    <!-- Add more rows as needed -->
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <script src="//unpkg.com/alpinejs" defer></script>
    </x-filament::section>
</x-filament-widgets::widget>
