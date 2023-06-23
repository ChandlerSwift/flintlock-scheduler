<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultSession extends Model
{
    use HasFactory;

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public static function format_time(int $seconds, bool $withDay)
    {
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $index = floor((int) $seconds / 86400);
        $day = $days[$index];
        $hour = (int) (($seconds % 86400) / 3600);
        $am_pm = 'AM';
        if ($hour > 12) {
            $hour -= 12;
            $am_pm = 'PM';
        }
        $minute = str_pad(floor(($seconds % 3600) / 60), 2, '0', STR_PAD_LEFT);
        if ($withDay) {
            return "$day, $hour:$minute $am_pm";
        } else {
            return "$hour:$minute $am_pm";
        }
    }

    public function formatted_start_time(bool $withDay = true)
    {
        return $this->format_time((int) $this->start_seconds, $withDay);
    }

    public function formatted_end_time(bool $withDay = true)
    {
        return $this->format_time((int) $this->end_seconds, $withDay);
    }
}
