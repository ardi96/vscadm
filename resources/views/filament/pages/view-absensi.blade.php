<div>

    <h1>{{ $record->name }}</h1>

    <h2>Kelas: {{ $record->kelas->name }}</h2>
    <h2 class="mb-4">Coach: {{ $record->kelas->coach->name }}</h2>

    
    <div class="fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 
        ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
        <div class="fi-ta-content relative divide-y divide-gray-200 overflow-x-auto dark:divide-white/10 dark:border-t-white/10">
            <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                <thead class="divide-y divide-gray-200 dark:divide-white/5">
                    <tr class="bg-gray-50 dark:bg-white/5">
                        <th class="text-gray-700 font-bold uppercase py-2 text-start">Tanggal</th>
                        <th class="text-gray-700 font-bold uppercase py-2 text-start">Timestamp</th>
                        <th class="text-gray-700 font-bold uppercase py-2 text-start">Coach</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $absensi as $item)
                    <tr>
                        <td class="py-2 text-gray-700">{{ date_format(date_create($item->tanggal),'l d-M-Y') }}</td>
                        <td class="py-2 text-gray-700">{{ $item->created_at}}</td>
                        <td class="py-2 text-gray-700">{{ $item->coach->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
