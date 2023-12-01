<?php

namespace CodebarAg\Zendesk\Enums;

enum TicketType: string
{
    case INCIDENT = 'incident';
    case PROBLEM = 'problem';
    case QUESTION = 'question';
    case TASK = 'task';
}
