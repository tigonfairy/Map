<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Document</title>
    <style>
        body{
            font-family: sans-serif;

        }
        tr > th ,tr > td{
            wrap-text: true
        }
        table {
            font-size: 14px;
            /*border: 1px solid #808080;*/
        }
        table.ht-table-data thead tr th{
            /*color: #FFD966;*/
            /*background-color: #404040;*/
        }
        table.ht-table-data th{

            white-space: normal;
        }
        table.ht-table-data td{
            white-space: normal;
        }
        .text-uppercase{
            text-transform: uppercase;
        }
        table.ht-table-data .ht-highlight{
            /*color: #333;*/
        }
    </style>
</head>
<body>

@php $data = [];
$index = 0;

@endphp

<table class="ht-table-data">
    <thead>
    <tr>
        <th rowspan="2" align="center" valign="middle" width="10">STT</th>
        <th rowspan="2"  align="center"valign="middle" width="20">Mã đại lý</th>
        <th rowspan="2" align="center" valign="middle" width="30">Tên đại lý</th>
        <th rowspan="2" align="center" valign="middle" width="25">NVKD</th>
        <th rowspan="2" align="center" valign="middle" width="10">Mã NVKD</th>
        <th align="center" valign="middle" width="10">DLV</th>
        <th align="center" valign="middle" width="10">TTKH</th>
        <th align="center" valign="middle" width="10">TT</th>
        @foreach($groupProduct as $group)
            <th align="center" valign="middle" width="10">{{$group->code}}</th>

            @php
                $array = [];
                $product = $group->product()->where('level',1)->get();
            @endphp

            @if($product->count())
                @foreach($product as $p)
                    @php $array[] = $p->id @endphp
                    <th align="center" valign="middle" width="10">{{$p->code}}</th>
                @endforeach
            @endif
            @php $data[] = [
                'group' => $group->id,
                'product' => $array
            ]; @endphp
        @endforeach

    </tr>
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th align="center" valign="middle" width="10">Dung lượng vùng</th>
        <th align="center" valign="middle" width="10">Tổng sản lượng kế hoạch</th>
        <th align="center" valign="middle" width="10">Tổng sản lượng thực tế</th>
        @foreach($groupProduct as $group)
            <th align="center" valign="middle" width="10">{{$group->name_vn}}</th>
            @php $product = $group->product()->where('level',1)->get();@endphp
            @if($product->count())
                @foreach($product as $p)
                    <th align="center" valign="middle" width="10">{{$p->name_vn}}</th>
                @endforeach
            @endif
        @endforeach
    </tr>
    </thead>
    <tbody>
    {{--<tr class="ht-highlight">--}}

    {{--</tr>--}}
<<<<<<< HEAD
    <tr>
        @php dd($data); @endphp

    </tr>
=======
    @php
            @endphp

>>>>>>> b541010a5a16f02fed013ad1480e09fe1574a75c
    </tbody>
</table>
</body>
</html>