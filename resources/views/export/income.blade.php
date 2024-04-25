 <table>
     <tbody>
         <tr>
             <td bgcolor='#3FC300'>Tanggal</td>
             <td bgcolor='#3FC300'>Pemasukkan</td>
         </tr>
         @php
             $total = 0;
         @endphp
         @foreach ($data as $item)
             @php
                 $total = $total + $item->total;
             @endphp
             <tr>
                 <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                 <td>{{ $item->total }}</td>
             </tr>
         @endforeach
         <tr>
             <td bgcolor="#FFC300" colspan="2" align="right"
                 data-format={{ \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1 }}>
                 {{ $total }}
             </td>
         </tr>

     </tbody>
 </table>
