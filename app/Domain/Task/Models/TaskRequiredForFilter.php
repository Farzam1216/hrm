<?php

namespace App\Domain\Task\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskRequiredForFilter extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'filter_type', 'filter_id'];
    /**
     * Get the parent filter model (department or division or location or designation (job title) or employment status).
     */
    public function filter()
    {
        return $this->morphTo();
    }
}
