<div>
    <h3 class="text-lg font-semibold mb-4">Details</h3>
    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr>
                <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Birth Date</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Age</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Birth Place</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Gender</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Civil Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($familyHead as $info)
                <tr>
                    <td class="border border-gray-300 px-4 py-2"> {{ $info->first_name }}  @if($info->middle_name)
                                                        {{ Str::substr($info->middle_name, 0, 1,) }}.
                                                        @endif{{ $info->last_name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $info->birthday }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $info->age }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $info->birthplace }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $info->gender }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $info->civil_status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
