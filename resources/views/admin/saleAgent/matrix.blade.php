<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>
    <style>
        body {
            font-family: sans-serif;

        }

        tr > th, tr > td {
            wrap-text: true;
            text-align: center;
        }

        .group_product {

            font-weight: bold;
            /*border: 1px solid #D9D9D9;*/
        }

        .group_product td {
            background-color: #FFC000;
        }

        .tieude {
            /*background-color: #404040;*/
            /*color: #FFC000;*/

        }

        .title {
            /*background-color: #D9D9D9;*/
            text-align: left;

        }

        .thang {
            /*background-color: #404040;*/
            font-weight: bold;
            color: #ffffff;
        }

        .thang td {
            background-color: #404040;
        }

        .name_product {
            background-color: #44546A;
            color: #92D040;
        }
        .total {
            background-color: #404040;
            color :#ED693B;
            font-weight: bold;
        }

        .total_group {
            background-color: #ACB9CA;
        }
    </style>
</head>
<body>
<table border="1">
    @php
        $total = 0;
        $total_cbd = 0;
        $total_maxgreen = 0;
        $total_maxgro = 0;



        $total_view  = '';
                $name_cbd = '';
                $value_cbd = '';
                $name_maxgreen = '';
                $value_maxgreen = '';
                $name_maxgro = '';
                $value_maxgro = '';
    @endphp
    <tr class="first">
        <td></td>
        <td class="total">Tất cả sản phẩm</td>
        @php  $groupProduct = \App\Models\GroupProduct::orderBy('created_at','desc')->get();@endphp
        @if($groupProduct->count())

            @foreach($groupProduct as $group)

                @php   $products = $group->product()->where('level',0)->orderBy('created_at','desc')->get();@endphp
                @if($products->count())
                    <td class="name_product" style="font-weight: bold">{{$group->name}}</td>
                    @php
                        $name_cbd .= '<td class="name_product"></td>';
                        $name_maxgreen .= '<td class="name_product"></td>';
                        $name_maxgro .= '<td class="name_product"></td>';

                        $group_cbd = 0;
                        $group_maxgreen = 0;
                        $group_maxgro = 0;

                        $group_name_cbd = '';
                        $group_name_maxgreen = '';
                        $group_name_maxgro = '';


                    $total_group = '';
                    @endphp
                    @foreach($products as $product)
                        <td class="name_product">{{$product->name}}</td>
                        @php
                            $cbd = 0;
                            $maxgreen = 0;
                            $maxgro = 0;
                                  if($product->cbd()) {
                                    $cbd = \App\Garena\Functions::calculateSaleReal($type,$manager_id,$product->cbd()->id,$startMonth,$endMonth);
                                      $name_cbd .= '<td class="name_product">'.$product->cbd()->code.'</td>';
                                  } else {
                                    $name_cbd .= '<td class="name_product"></td>';
                                  }
                                  if($product->maxgreen()) {
                                    $maxgreen = \App\Garena\Functions::calculateSaleReal($type,$manager_id,$product->maxgreen()->id,$startMonth,$endMonth);
                                     $name_maxgreen .= '<td class="name_product">'.$product->maxgreen()->code.'</td>';
                                  }else {
                                    $name_maxgreen .= '<td class="name_product"></td>';
                                  }

                                  if($product->maxgro()) {
                                    $maxgro = \App\Garena\Functions::calculateSaleReal($type,$manager_id,$product->maxgro()->id,$startMonth,$endMonth);
                                     $name_maxgro .= '<td class="name_product">'.$product->maxgro()->code.'</td>';
                                  }else {
                                    $name_maxgro .= '<td class="name_product"></td>';
                                  }

                             $group_cbd += $cbd;
                            $group_maxgreen += $maxgreen;
                            $group_maxgro += $maxgro;




                            $group_name_cbd .= '<td >'.$cbd.'</td>';
                            $group_name_maxgreen .= '<td >'.$maxgreen.'</td>';
                            $group_name_maxgro .= '<td >'.$maxgro.'</td>';



                            $total_product = $cbd+$maxgreen+$maxgro;
                            $total_group .= '<td class="total_group">'.$total_product.'</td>';

                        @endphp

                    @endforeach
                    @php
                        $value_cbd .= '<td class="name_product">'.$group_cbd.'</td>'.$group_name_cbd;
                        $value_maxgreen .= '<td class="name_product">'.$group_maxgreen.'</td>'.$group_name_maxgreen;
                        $value_maxgro .= '<td class="name_product">'.$group_maxgro.'</td>'.$group_name_maxgro;


                         $total_cbd += $group_cbd;
                        $total_maxgreen += $group_maxgreen;
                        $total_maxgro += $group_maxgro;

                        $total_product_group = $group_cbd + $group_maxgreen + $group_maxgro;
                         $total_view .= '<td class="name_product">'.$total_product_group.'</td>'.$total_group;

                    @endphp

                @endif


            @endforeach
        @endif

    </tr>

    <tr>
        <td class="total">Tổng</td>
        <td class="total">{{$total_cbd + $total_maxgreen + $total_maxgro}}</td>
        {!! $total_view  !!}
    </tr>


      <tr>
        <td></td>
        <td></td>
        {!! $name_cbd !!}
    </tr>

    <tr>
        <td class="total">CBD</td>
        <td class="total">{{$total_cbd}}</td>
        {!! $value_cbd !!}
    </tr>
    <tr>
        <td></td>
        <td></td>
        {!! $name_maxgreen !!}
    </tr>

    <tr>
        <td class="total">Maxgreen</td>
        <td class="total">{{$total_maxgreen}}</td>
        {!! $value_maxgreen !!}
    </tr>
    <tr>
        <td></td>
        <td></td>
        {!! $name_maxgro !!}
    </tr>

    <tr>
        <td class="total">Maxgr0</td>
        <td class="total">{{$total_maxgro}}</td>
        {!! $value_maxgro !!}
    </tr>

</table>
</body>
</html>