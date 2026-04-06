<?php

namespace App\Enums;

enum UserRole: string
{
    case AdminSchool = 'admin_school';
    case Teacher     = 'teacher';
    case Student     = 'student';
}
