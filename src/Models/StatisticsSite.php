<?php

namespace Aphly\LaravelStatistics\Models;

use Aphly\Laravel\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatisticsSite extends Model
{
    use HasFactory;
    protected $table = 'statistics_site';
    //public $timestamps = false;
    protected $fillable = [
        'appid','host','secret','status'
    ];

    function findOne(){

    }
}
