@props(['dates', 'records', 'title'])

<div>
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        table{
            margin: 0px;
            background: #fff;
            width: 100%;
            border-collapse: collapse;
        }
        td,th{
            font-size:10px;
        }
        @media print{@page {size: landscape}}
    </style>
    <table border="1" id="tbl_exporttable_to_xls">
                <thead>
                    <tr>
                        <th
                        style="font-style:italic;
                        padding-left:10px;text-align:left;">
                            {{ $title }}
                        </th>
                    </tr>
                    <tr>
                        <th
style="text-align:center;border:1px solid #000;" rowspan="2">
                            DATE
                        </th>
                        @foreach ($dates as $date)
                            <th
style="text-align:center;border:1px solid #000;" colspan="2">
                                <div>
                                    {{ $date['date']}}
                                </div>
                            </th>
                            <th
style="text-align:center;border:1px solid #000;" rowspan="2">Remarks</th>
                        @endforeach
                        <th
style="text-align:center;border:1px solid #000;" rowspan="2">
                            Total Hours
                        </th>
                    </tr>
                    <tr>
                        @foreach ($dates as $date)
                            <th
style="text-align:center;border:1px solid #000;" colspan="2">
                                <div>
                                    {{ $date['day']}}
                                </div>
                            </th>
                        @endforeach
                    </tr>
                    <tr>
                        <td
style="text-align:center;border:1px solid #000;" ></td>
                        @foreach ($dates as $date)
                            <td
style="text-align:center;color: red;border:1px solid #000;"  >
                                TIME IN
                            </td>
                            <td
style="text-align:center;color: red;border:1px solid #000;"  class="makered">
                                TIME OUT
                            </td>
                            <td
style="text-align:center;border:1px solid #000;" ></td>
                        @endforeach
                        <td
style="text-align:center;border:1px solid #000;" ></td>
                    </tr>
                </thead>
                @php
                    $colLen = count($dates) * 3;
                @endphp
                <tbody>
                    @foreach ($records as $key=>$record)
                        <tr>
                            <th>
                                {{ $key }}
                            </th>
                            
                        </tr>
                        @foreach ($record as $person)
                            <tr>
                                <td
style="text-align:center;border:1px solid #000;" >
                                    {{ $person->name }}
                                </td>
                                @foreach ($person->records as $prec)
                                    @if ($prec['time_in'] == null &&
                                    $prec['time_out'] == null)
                                        <td
style="text-align:center; font-weight: 900;border:1px solid #000;"  colspan="2" >
                                            ABSENT
                                        </td>
                                    @else 
                                        <td
style="text-align:center;border:1px solid #000;" >
                                            {{ $prec['time_in'] }}
                                        </td>
                                        <td
style="text-align:center;border:1px solid #000;" >
                                            {{ $prec['time_out'] }}
                                        </td>
                                    @endif
                                    <td
style="text-align:center;border:1px solid #000;" >
                                        @php
                                            $first = explode(':', $prec['time_in'])[0];
                                            $second = explode(':', $prec['time_out'])[0]
                                        @endphp
                                    </td>
                                @endforeach
                               
                                <td
style="text-align:center;border:1px solid #000;" >
                                        {{ $person->total }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
    @if (isset(request()->is_print))
        <script>
                window.onload = function(){
                    window.print();
                }
            </script>
    @endif
            
</div>
