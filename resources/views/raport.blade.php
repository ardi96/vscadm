<html lang="en">
<head>
    <title>Raport</title>

    <link rel="stylesheet" href="{{ public_path('invoice.css') }}" type="text/css">

    <style>

    table.border, td.border, th.border {
        border: 1px solid black;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    </style>

</head>
<body>

<table class="w-full">
    <tr>
        <td class="w-half">
            <img src="{{ public_path('/storage/images/logo-raport.png') }}" alt="logo" class="logo" height="92">
        </td>
        <td class="w-half">
            <h4>Student Report</h4>
            <div>Periode {{ date("F", strtotime(date("Y") ."-". $record->month ."-01"))  .' '. $record->year }}</div>
            <div>Tanggal Ujian : {{ date('d-M-Y', strtotime($record->created_at)) }}</div>
        </td>
    </tr>
</table>

<div class="px-2 py-8 max-w-xl mx-auto">
    
    <div class="border-b-2 border-gray-300 pb-4 mb-4">
        <h2 class="text-2xl font-bold mb-4">Data Siswa</h2>
        <div class="text-gray-700 mb-2">Nama  : {{ $record->member->name }}</div>
        <div class="text-gray-700 mb-2">Orang Tua  : {{ $record->member->parent_name }}</div>
        <div class="text-gray-700 mb-2">Grade : {{ $record->grade->name }}</div>
        <div class="text-gray-700 mb-2">Kelas : {{ $record->member->kelas->name }}</div>
        <div class="text-gray-700 mb-2">Wali Kelas : {{ $record->member->kelas->coach->name }}</div>
    </div>
    <br/>
    <br/>
    <br/>
    <table class="w-full text-left mb-4 border">
        <thead>
        <tr>
            <th style="height: 40px" class="text-gray-700 font-bold uppercase py-2 border">Aspek Penilaian</th>
            <th style="height: 40px" class="text-gray-700 font-bold uppercase py-2 border">Nilai</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $record->gradingItems as $item)
        <tr>
            <td style="height: 30px; padding: 5px" class="py-2 text-gray-700 border">{{ $item->aspect }}</td>
            <td style="text-align:center; height: 30px" class="py-2 text-gray-700 border">{{ $item->mark }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    
    <div class="border-t-2 border-gray-300 pt-4 mb-4">
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

    <br/>
    <br/>
    <br/>
    <br/>

    <table class="w-full text-center mb-8 border">
        <thead>
        <tr>
            <th style="padding: 5px" class="text-gray-700 font-bold uppercase py-2 border">Wali Kelas</th>
            <th style="padding: 5px" class="text-gray-700 font-bold uppercase py-2 border">Head Coach</th>
            <th style="padding: 5px" class="text-gray-700 font-bold uppercase py-2 border">Ketua VSC</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="text-align:center" class="py-2 text-gray-700 border border-gray-300 dark:border-gray-700 border"><br/><br/><br/><br/><br/></br>{{ $record->member->kelas->coach->name }}</td>
            <td style="text-align:center" class="py-2 text-gray-700 border border-gray-300 dark:border-gray-700 border"><br/><br/><br/><br/><br/></br>{{ $record->approver?  $record->approver->name  : ''}}</td>
            <td style="text-align:center" class="py-2 text-gray-700 border border-gray-300 dark:border-gray-700 border"><br/><br/><br/><br/><br/></br>Rizal Prasetyo</td>
        </tr>
        </tbody>
    </table>

</div>

</body>
</html>