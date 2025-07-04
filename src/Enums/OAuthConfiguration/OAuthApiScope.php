<?php

namespace CodebarAg\Bexio\Enums\OAuthConfiguration;

enum OAuthApiScope: string
{
    // API Scopes
    case ACCOUNTING = 'accounting';
    case ARTICLE_SHOW = 'article_show';
    case ARTICLE_EDIT = 'article_edit';
    case BANK_ACCOUNT_SHOW = 'bank_account_show';
    case BANK_PAYMENT_SHOW = 'bank_payment_show';
    case BANK_PAYMENT_EDIT = 'bank_payment_edit';
    case CONTACT_SHOW = 'contact_show';
    case CONTACT_EDIT = 'contact_edit';
    case FILE = 'file';
    case KB_INVOICE_SHOW = 'kb_invoice_show';
    case KB_INVOICE_EDIT = 'kb_invoice_edit';
    case KB_OFFER_SHOW = 'kb_offer_show';
    case KB_OFFER_EDIT = 'kb_offer_edit';
    case KB_ORDER_SHOW = 'kb_order_show';
    case KB_ORDER_EDIT = 'kb_order_edit';
    case KB_DELIVERY_SHOW = 'kb_delivery_show';
    case KB_DELIVERY_EDIT = 'kb_delivery_edit';
    case MONITORING_SHOW = 'monitoring_show';
    case MONITORING_EDIT = 'monitoring_edit';
    case NOTE_SHOW = 'note_show';
    case NOTE_EDIT = 'note_edit';
    case KB_ARTICLE_ORDER_SHOW = 'kb_article_order_show';
    case KB_ARTICLE_ORDER_EDIT = 'kb_article_order_edit';
    case PROJECT_SHOW = 'project_show';
    case PROJECT_EDIT = 'project_edit';
    case STOCK_EDIT = 'stock_edit';
    case TASK_SHOW = 'task_show';
    case TASK_EDIT = 'task_edit';
    case KB_BILL_SHOW = 'kb_bill_show';
    case KB_EXPENSE_SHOW = 'kb_expense_show';
    case PAYROLL_EMPLOYEE_SHOW = 'payroll_employee_show';
    case PAYROLL_EMPLOYEE_EDIT = 'payroll_employee_edit';
    case PAYROLL_ABSENCE_SHOW = 'payroll_absence_show';
    case PAYROLL_ABSENCE_EDIT = 'payroll_absence_edit';
    case PAYROLL_PAYSTUB_SHOW = 'payroll_paystub_show';
}
