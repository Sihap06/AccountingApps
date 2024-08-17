 <table>
     <tbody>
         <tr>
             <td>Income</td>
             <td align="right"
                 data-format={{ \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1 }}>
                 {{ $income }}</td>
         </tr>
         <tr>
             <td>Expenditure</td>
             <td align="right"
                 data-format={{ \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1 }}>
                 {{ $expend }}</td>
         </tr>
         <tr>
             <td>Netto</td>
             <td align="right"
                 data-format={{ \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1 }}>
                 {{ $netto }}</td>
         </tr>
     </tbody>
 </table>
