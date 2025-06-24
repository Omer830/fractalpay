<?php

namespace Modules\UserKyc\Enums;

enum Gender: string
{
    case Male = "Male";
    case Female = "Female";
    case NON_BINARY = "Non-Binary";
    case GENDER_QUEER = "Gender Queer";
    case GENDER_FLUID = "Gender Fluid";
    case AGENDER = "Agender";
    case TRANSGENDER_MALE = "Transgender Male";
    case TRANSGENDER_FEMALE = "Transgender Female";
    case PREFER_NOT_TO_SAY = "Prefer not to say";
    case Other = "Other";
}