<html lang="en">
<head>
    <title>Invoice</title>

    <!-- <link rel="stylesheet" href="https://cdn.tailwindcss.com"> -->

    <!-- <script src="https://cdn.tailwindcss.com"></script> -->

    <!-- <link rel="stylesheet" href="{{asset('css/tailwind.min.css')}}"> -->

    <!-- <link rel="stylesheet" href="{{ public_path('tailwind.min.css') }}" type="text/css"> -->

    <link rel="stylesheet" href="{{ public_path('invoice.css') }}" type="text/css">

</head>
<body>

<table class="w-full">
    <tr>
        <td class="w-half">
            <img src="{{ public_path('/storage/images/logo-raport.png') }}" alt="logo" class="logo" height="92">
        </td>
        <td class="w-half">
            <h4>Nomor Invoice: {{ $record->invoice_no }}</h4>
            <div>Tanggal Invoice: {{ $record->created_at->format('d M Y') }}</div>
        </td>
    </tr>
</table>

<div class="margin-top">
    <table class="w-full">
        <tr>
            <td class="w-half">
                <div><h4>Kepada:</h4></div>
                <div>Bpk/Ibu {{ $record->member->parent_name }}</div>
                <br>
                <div>Nama Siswa: {{ $record->member->name }}</div>
                <div>Grade: {{ $record->member->grade?->name  }}</div>
                <div>Kelas: {{ $record->member->kelas?->name }}</div>
            </td>
        </tr>
    </table>
</div>

<div class="margin-top">
    <table class="products">
        <tr>
            <th>Keterangan</th>
            <th>Nominal</th>
        </tr>
        @foreach($record->items as $item)
        <tr class="items">
            <td>
                {{ $item->description }}
            </td>
            <td class="price">
                Rp {{ number_format($item->amount,0,",",".") }}
            </td>
        </tr>
        @endforeach
    </table>
</div>

<div class="total">
    Total: Rp {{ number_format($record->amount,0,",",".") }} <span class="float-right">     </span>
</div>

<div class="footer margin-top">
    <div>Thank you</div>
    <div>&copy; Veins Skating Club</div>
</div>


</body>
</html>