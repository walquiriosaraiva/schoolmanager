<?php

namespace App\Repositories;

use App\Models\Payment;
use Carbon\Carbon;

class PaymentRepository
{
    public function store($request, $student_id)
    {
        try {

            for ($i = 1; $i <= $request['contract_duration']; $i++):
                $explodeDate = explode('-', $request['due_date']);
                $date = Carbon::create($explodeDate[0], $explodeDate[1], $explodeDate[2]);

                Payment::create([
                    'student_id' => $student_id,
                    'due_date' => $date->addMonths($i - 1),
                    'tuition' => $request['tuition'],
                    'sdf' => $request['sdf'],
                    'hot_lunch' => $request['hot_lunch'],
                    'enrollment' => $request['enrollment'],
                    'percentage_discount' => $request['percentage_discount']
                ]);
            endfor;


        } catch (\Exception $e) {
            throw new \Exception('Failed to create Payment information. ' . $e->getMessage());
        }
    }

    public function getPaymentInfo($student_id)
    {
        return Payment::where('student_id', $student_id)->get();
    }

    public function getPaymentCount($student_id)
    {
        return Payment::where('student_id', $student_id)->count();
    }

    public function update($request, $student_id)
    {
        try {
            Payment::where('student_id', $student_id)->update([
                'due_date' => $request['due_date'],
                'tuition' => $request['tuition'],
                'sdf' => $request['sdf'],
                'hot_lunch' => $request['hot_lunch'],
                'enrollment' => $request['enrollment'],
                'percentage_discount' => $request['percentage_discount']
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Failed to update Payment information. ' . $e->getMessage());
        }
    }
}
