<?php

namespace App\Traits;

use App\Models\Technician;

trait CalculatesTransactionProfit
{
    /**
     * Resolve fee_teknisi / modal / untung for one transaction line.
     *
     * When a sparepart is used ($modal > 0) the technician fee is taken from
     * the margin (biaya - modal) using percent_with_sparepart, and the
     * sparepart price is part of the modal. Without a sparepart the fee is a
     * flat percent_fee of biaya.
     */
    protected function getPerhitungan($technical_id, $biaya, $modal)
    {
        if ($technical_id != null && $technical_id != '') {
            $tecnician = Technician::findOrFail($technical_id);

            if ($modal > 0) {
                $percentModal = $tecnician->percent_with_sparepart;
                $countModal = ($biaya - $modal) * $percentModal / 100;
            } else {
                $percentModal = $tecnician->percent_fee;
                $countModal = $biaya * $percentModal / 100;
            }

            $percentUntung = 100 - $percentModal;
            $countUntung = $biaya * $percentUntung / 100;

            return [
                'fee_teknisi' => $countModal,
                'modal' => $modal > 0 ? $modal + $countModal : $countModal,
                'untung' => $modal > 0 ? $biaya - $modal - $countModal : $countUntung,
            ];
        }

        return [
            'fee_teknisi' => 0,
            'modal' => ($modal != null) ? $modal : 0,
            'untung' => $biaya - $modal,
        ];
    }
}
