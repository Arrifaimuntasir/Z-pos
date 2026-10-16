<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory, HasTenant;

    protected $fillable = ['description', 'amount', 'expense_date', 'category', 'branch_id'];
}
