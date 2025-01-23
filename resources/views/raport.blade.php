<html lang="en">
<head>
    <title>Invoice</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<div class="px-2 py-8 max-w-xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center">
            <img src={{ config('app.url') . '/storage/images/logo-light.png'}} alt="" width="64" height="64">
            <div class="mx-2 text-gray-700 font-bold text-xl">VEINS SKATING CLUB</div>
        </div>
        <div class="text-gray-700">
            <div class="font-semibold text-lg mb-2 uppercase">Student Report</div>
            <div class="text-sm">Periode {{ date("F", strtotime(date("Y") ."-". $record->month ."-01"))  .' '. $record->year }}</div>
            <div class="text-sm">Tanggal Ujian : {{ date('d-M-Y', strtotime($record->created_at)) }}</div>
        </div>
    </div>
    
    <div class="border-b-2 border-gray-300 pb-8 mb-8">
        <h2 class="text-2xl font-bold mb-4">Data Siswa</h2>
        <div class="text-gray-700 mb-2">Nama  : {{ $record->member->name }}</div>
        <div class="text-gray-700 mb-2">Orang Tua  : {{ $record->member->parent_name }}</div>
        <div class="text-gray-700 mb-2">Grade : {{ $record->grade->name }}</div>
        <div class="text-gray-700 mb-2">Kelas : {{ $record->member->kelas->name }}</div>
        <div class="text-gray-700 mb-2">Wali Kelas : {{ $record->member->kelas->coach->name }}</div>
    </div>
    
    <table class="w-full text-left mb-8">
        <thead>
        <tr>
            <th class="text-gray-700 font-bold uppercase py-2">Aspek Penilaian</th>
            <th class="text-gray-700 font-bold uppercase py-2">Nilai</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $record->gradingItems as $item)
        <tr>
            <td class="py-2 text-gray-700">{{ $item->aspect }}</td>
            <td class="py-2 text-gray-700">{{ $item->mark }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    
    <div class="border-t-2 border-gray-300 pt-8 mb-8">
        <div class="text-gray-700 mb-2">Catatan pelatih: {!! $record->notes !!}</div>
        <div class="text-gray-700 mb-2">Keputusan:</div>
        <div class="text-gray-700">
            @if( $record->decision == 1)
                Naik Kelas
            @else
                Tidak Naik Kelas
            @endif 
        </div>
    </div>

    <table class="w-full text-center mb-8">
        <thead>
        <tr>
            <th class="text-gray-700 font-bold uppercase py-2">Wali Kelas</th>
            <th class="text-gray-700 font-bold uppercase py-2">Head Coach</th>
            <th class="text-gray-700 font-bold uppercase py-2">Ketua VSC</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="py-2 text-gray-700 border border-gray-300 dark:border-gray-700"><br/><br/><br/></br>{{ $record->member->kelas->coach->name }}</td>
            <td class="py-2 text-gray-700 border border-gray-300 dark:border-gray-700"><br/><br/><br/></br>{{ $record->approver->name }}</td>
            <td class="py-2 text-gray-700 border border-gray-300 dark:border-gray-700"><br/><br/><br/></br>Rizal Prasetyo</td>
        </tr>
        </tbody>
    </table>

</div>

</body>
</html>