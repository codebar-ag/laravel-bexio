<?php

namespace CodebarAg\Zendesk\Enums;

enum TicketPriority: string
{
    case URGENT = 'urgent';
    case HIGH = 'high';
    case NORMAL = 'normal';
    case LOW = 'low';
}
