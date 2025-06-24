<?php

namespace Modules\Invitation\Enums;

enum InvitationStatus : string
{
    case PENDING = 'pending';
    case OPENED = 'opened';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case EXPIRED = 'expired';
    case REMOVED = 'removed';
}