 <table>
     <tbody>
         <tr>
             <td bgcolor="#FFC300">Tanggal</td>
             <td bgcolor="#FFC300">Teknisi</td>
             <td bgcolor="#FFC300">Service</td>
             <td bgcolor="#FFC300">Total</td>
         </tr>
         @php
             $total = 0;
         @endphp
         @foreach ($data as $item)
             @php
                 $total = $total + $item['fee_teknisi'];
             @endphp
             <tr>
                 <td>{{ \Carbon\Carbon::parse($item['created_at'])->format('d/m/Y') }}</td>
                 <td>{{ $item['name'] }}</td>
                 <td>{{ $item['service'] }}</td>
                 <td>{{ $item['fee_teknisi'] }}</td>
             </tr>
         @endforeach

         <tr>
             <td bgcolor="#4285f4" colspan="4" align="right"
                 data-format={{ \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1 }}>
                 {{ $total }}
             </td>
         </tr>

     </tbody>
 </table>
