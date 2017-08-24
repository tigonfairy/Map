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

@php $datas = [];
$index = 0;

@endphp

<table class="ht-table-data" border="1">
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
            @php $datas[] = [
                'group' => $group->id,
                'product' => $array
            ]; @endphp
        @endforeach

    </tr>
    <tr>
        {{--<th></th>--}}
        {{--<th></th>--}}
        {{--<th></th>--}}
        {{--<th></th>--}}
        {{--<th></th>--}}
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
    {{--GDv--}}
    @php  $gdvs = \App\Models\User::where('position',\App\Models\User::GĐV)->get();@endphp
    @if($gdvs->count())
           @foreach($gdvs as $key => $gdv)
                @php
                    $agents = \App\Models\Agent::where('gdv',$gdv->id)->count();
                @endphp
                @if($agents)
                        {{--in ra gdv--}}
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{$gdv->name}}</td>
                            <td>{{$gdv->code}}</td>
                            {{--dung luong vung--}}

                            @php $dlv = \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                ->groupBy('agent_id','month')->join('agents','agents.id', '=' ,'sale_agents.agent_id')->where('agents.gdv',$gdv->id)
                                ->get()->sum('capacity');@endphp
                            <td>{{$dlv}}</td>

                            @php $slkh = \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                ->groupBy('agent_id','month')->join('agents','agents.id', '=' ,'sale_agents.agent_id')->where('agents.gdv',$gdv->id)
                                ->get()->sum('sales_plan');@endphp
                            <td>{{$slkh}}</td>
                            @php $sltt =  \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                            ->join('agents','agents.id', '=' ,'sale_agents.agent_id')->where('agents.gdv',$gdv->id)
                                ->get()->sum('sales_real');@endphp
                            <td>{{$sltt}}</td>


                            {{--nhom san pham--}}
                            @foreach($datas as $data)
                                @php $group = $data['group'];
                                    $products = $data['product'];
                                $val = 0;
                                $string = '';
                                @endphp

                                @foreach($products as $product)
                                    @php $sltt =  \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                         ->join('agents','agents.id', '=' ,'sale_agents.agent_id')->where('agents.gdv',$gdv->id)->where('product_id',$product)
                                ->get()->sum('sales_real');
                                    $val += $sltt;
                                    $string .= '<td>'.$sltt.'</td>';
                                    @endphp
                                @endforeach
                                <td>{{$val}}</td>
                                {!! $string  !!}
                            @endforeach
                        </tr>

                    {{--in ra dai ly--}}
                        @php $agents = \App\Models\Agent::where('gdv',$gdv->id)->where("tv",0)->where('gsv',0)->whereHas('user',function($query) {
                            $query->whereIn('position',[\App\Models\User::NVKD,\App\Models\User::GĐV]);
                        })->get();

                        @endphp
                        @foreach($agents as $agent)
                            <tr>
                                <td>{{++$index}}</td>
                                <td>{{$agent->code}}</td>
                                <td>{{$agent->name}}</td>
                                <td>{{($agent->user) ? $agent->user->name : ''}}</td>
                                <td>{{($agent->user) ? $agent->user->code : ''}}</td>
                                @php $dlv = \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                ->groupBy('agent_id','month')->where('agent_id',$agent->id)
                                ->get()->sum('capacity'); @endphp
                                <td>{{$dlv}}</td>
                                @php $slkh = \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                ->groupBy('agent_id','month')->where('agent_id',$agent->id)
                                ->get()->sum('sales_plan');@endphp
                                <td>{{$slkh}}</td>
                                @php $sltt =  \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                    ->where('agent_id',$agent->id)
                                ->get()->sum('sales_real');@endphp
                                <td>{{$sltt}}</td>


                                {{--nhom san pham--}}
                                @foreach($datas as $data)
                                    @php $group = $data['group'];
                                    $products = $data['product'];
                                $val = 0;
                                $string = '';
                                    @endphp

                                    @foreach($products as $product)
                                        @php $sltt =  \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                         ->where('agent_id',$agent->id)->where('product_id',$product)
                                ->get()->sum('sales_real');
                                    $val += $sltt;
                                    $string .= '<td>'.$sltt.'</td>';
                                        @endphp
                                    @endforeach
                                    <td>{{$val}}</td>
                                    {!! $string  !!}
                                @endforeach

                            </tr>
                    @endforeach




                @endif

           @endforeach
    @endif


    <tr>


    </tr>
    </tbody>
</table>
</body>
</html>