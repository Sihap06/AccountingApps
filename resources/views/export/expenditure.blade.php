 <table>
     <tbody>
         <tr>
             <td bgcolor='#C32200'>Tanggal</td>
             <td bgcolor='#C32200'>Jenis</td>
             <td bgcolor='#C32200'>Total</td>
         </tr>
         @php
             $total = 0;
         @endphp
         @foreach ($data as $item)
             @php
                 $total = $total + $item->total;
             @endphp
             <tr>
                 <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                 <td>{{ $item->jenis }}</td>
                 <td>{{ $item->total }}</td>
             </tr>
         @endforeach

         <tr>
             <td bgcolor="#3FC300" colspan="3" align="right"
                 data-format={{ \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1 }}>
                 {{ $total }}
             </td>
         </tr>


     </tbody>
 </table>
