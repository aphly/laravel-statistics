<?php

namespace Aphly\LaravelStatistics\Models;

use Aphly\Laravel\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatisticsIpv4 extends Model
{
    use HasFactory;
    protected $table = 'statistics_ipv4';
    //public $timestamps = false;
    protected $fillable = [
        'country_iso','ip_start','ip_end','ip_start_int','ip_end_int'
    ];

}
