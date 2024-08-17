 <table>
     <tbody>
         <tr>
             <td bgcolor='#4285f4'>Tanggal</td>
             <td bgcolor='#4285f4'>Keterangan</td>
             <td bgcolor='#4285f4'>Service</td>
             <td bgcolor='#4285f4'>Biaya</td>
             <td bgcolor='#4285f4'>Modal</td>
             <td bgcolor='#4285f4'>Cuan</td>
         </tr>
         @php
             $tempDate = null;
             $tempTotal = 0;
         @endphp
         @foreach ($data as $index => $item)
             @php
                 $date = \Carbon\Carbon::parse($item['created_at'])->format('d/m/Y');
                 $tempTotal = $tempTotal + $item['untung'];
             @endphp

             <tr>
                 <td>
                     @if ($tempDate !== $date)
                         {{ $date }}
                     @endif
                 </td>
                 <td>{{ $item['kode'] }}</td>
                 <td>{{ $item['service'] }}</td>
                 <td>{{ $item['biaya'] }}</td>
                 <td>{{ $item['modal'] }}</td>
                 <td>{{ $item['untung'] }}</td>
             </tr>
             @php
                 $tempDate = $date;
             @endphp
         @endforeach


         <tr>
             <td colspan="6" align="right" bgcolor="#3FC300"
                 data-format={{ \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1 }}>
                 {{ $tempTotal }}
             </td>
         </tr>

     </tbody>
 </table>
