<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class deps extends Model
{
    use HasFactory;

    public function getSummaryByDate($startDateTime,$endDateTime){
        return $this->select("deps.title",
                      DB::raw('COUNT(DISTINCT salaries.staff_id) as staff_count'),
                      DB::raw('SUM(salaries.finalTotal) as total_salary'))
                    ->leftJoin('salaries','salaries.dep','=','deps.id')
                    ->whereBetween('salaries.created_at',[$startDateTime,$endDateTime])
                    ->groupBy('deps.title')
                    ->get();
    }
}
