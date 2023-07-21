<?php

namespace Aphly\LaravelStatistics\Models;

use Aphly\Laravel\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatisticsHost extends Model
{
    use HasFactory;
    protected $table = 'statistics_host';
    //public $timestamps = false;
    protected $fillable = [
        'appid','host','secret','status'
    ];

    function findOne(){

    }
}
