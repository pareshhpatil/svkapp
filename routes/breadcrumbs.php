<?php

Breadcrumbs::for('home', function ($trail) {
    $trail->push('', '/merchant/dashboard');
});


Breadcrumbs::for('create.invoice', function ($trail, $type) {
    $trail->parent('home');
    $trail->push('Collect Payments');
    $trail->push('Create ' . $type);
});

Breadcrumbs::for('update.invoice', function ($trail, $type) {
    $trail->parent('home');
    $trail->push('Invoice list', '/merchant/paymentrequest/viewlist');
    if ($type == 1) {
        $trail->push('Update invoice');
    } elseif ($type == 2) {
        $trail->push('Update estimate');
    } else {
        $trail->push('Update subscription');
    }
    //$trail->push('Update ' . $type);
});

Breadcrumbs::for('create.expense', function ($trail) {
    $trail->parent('home');
    $trail->push('Expense');
    $trail->push('Create expense');
});

Breadcrumbs::for('list.expense', function ($trail, $type) {
    $trail->parent('home');
    $trail->push('Purchases');
    $trail->push($type);
    $trail->push('List');
});

Breadcrumbs::for('create.po', function ($trail) {
    $trail->parent('home');
    $trail->push('Purchases');
    $trail->push('Purchase order');
    $trail->push('Create purchase order');
});

Breadcrumbs::for('update.expense', function ($trail, $type, $exp_no) {
    $trail->parent('home');
    $trail->push('Purchases');
    if ($type == 1) {
        $trail->push('Expense list', '/merchant/expense/viewlist/expense');
        $trail->push('Update expense');
    } else {
        $trail->push('Purchase order list', '/merchant/expense/viewlist/po');
        $trail->push('Update purchase order');
    }
    $trail->push($exp_no);
});

Breadcrumbs::for('inventory', function ($trail) {
    //$old_links = Session::get('breadcrumbs');
    $trail->parent('home');
    $trail->push('Inventory');
});

Breadcrumbs::for('merchant.product.createnew', function ($trail) {
    $trail->parent('home');
    $trail->push('Inventory', route('inventory'));
    $trail->push('Create product/service');
});

Breadcrumbs::for('product.show', function ($trail, $name) {
    $trail->parent('home');
    $trail->push('Inventory', route('inventory'));
    $trail->push('View inventory details');
    $trail->push($name);
});

Breadcrumbs::for('product.update', function ($trail, $name) {
    $trail->parent('home');
    $trail->push('Inventory', route('inventory'));
    $trail->push('Update product/service');
    $trail->push($name);
});

Breadcrumbs::for('merchant.payout.create', function ($trail) {
    $trail->parent('home');
    $trail->push('Contacts');
    $trail->push('Beneficiaries');
    $trail->push('Create beneficiary');
});
Breadcrumbs::for('merchant.payout.beneficiarylist', function ($trail) {
    $trail->parent('home');
    $trail->push('Contacts');
    $trail->push('Beneficiaries');
    $trail->push('List beneficiary');
});

Breadcrumbs::for('merchant.payout.transfer', function ($trail) {
    $trail->parent('home');
    $trail->push('Payout');
    $trail->push('Initiate transfer');
});
Breadcrumbs::for('merchant.payout.transactions', function ($trail) {
    $trail->parent('home');
    $trail->push('Payout');
    $trail->push('Transfer transaction list');
});
Breadcrumbs::for('merchant.payout.nodal', function ($trail) {
    $trail->parent('home');
    $trail->push('Payout');
    $trail->push('Nodal account');
});

Breadcrumbs::for('merchant.payout.cashgram.create', function ($trail) {
    $trail->parent('home');
    $trail->push('Payout');
    $trail->push('Cashgram list', route('merchant.payout.cashgram.list'));
    $trail->push('Create cashgram');
});
Breadcrumbs::for('merchant.payout.cashgram.list', function ($trail) {
    $trail->parent('home');
    $trail->push('Payout');
    $trail->push('Cashgram list');
});

Breadcrumbs::for('list.category.expense', function ($trail) {
    $trail->parent('home');
    $trail->push('Purchases');
    $trail->push('Category list');
});

Breadcrumbs::for('list.department.expense', function ($trail) {
    $trail->parent('home');
    $trail->push('Purchases');
    $trail->push('Department list');
});
Breadcrumbs::for('merchant.unit-type.list', function ($trail) {
    $trail->parent('home');
    $trail->push('Settings', url('/merchant/profile/settings'));
    $trail->push('Data configuration');
    $trail->push('Unit type list');
});
Breadcrumbs::for('merchant.product-category.list', function ($trail) {
    $trail->parent('home');
    $trail->push('Settings', url('/merchant/profile/settings'));
    $trail->push('Data configuration');
    $trail->push('Product category list');
});

Breadcrumbs::for('merchant.company-profile.home', function ($trail, $name) {
    $trail->parent('home');
    $trail->push('Settings', url('/merchant/profile/settings'));
    $trail->push('Company setting');
    $trail->push('Compay website');
});

Breadcrumbs::for('create.invoiceformat', function ($trail) {
    $trail->parent('home');
    $trail->push('Settings', url('/merchant/profile/settings'));
    $trail->push('Billing & Invoicing');
    $trail->push('Create invoice format');
});


Breadcrumbs::for('update.invoiceformat', function ($trail) {
    $trail->parent('home');
    $trail->push('Settings', url('/merchant/profile/settings'));
    $trail->push('Invoice format list', url('/merchant/template/viewlist'));
    $trail->push('Update invoice format');
});

Breadcrumbs::for('merchant.creditnote.create', function ($trail) {
    $trail->parent('home');
    $trail->push('Sales');
    $trail->push('Credit note');
    $trail->push('Create credit note');
});
Breadcrumbs::for('merchant.creditnote.credit-note.list', function ($trail) {
    $trail->parent('home');
    $trail->push('Sales');
    $trail->push('Credit note');
    $trail->push('Credit note list');
});
Breadcrumbs::for('merchant.debitnote.create', function ($trail) {
    $trail->parent('home');
    $trail->push('Purchases');
    $trail->push('Debit note');
    $trail->push('Create debit note');
});

Breadcrumbs::for('merchant.debitnote.debit-note.list', function ($trail) {
    $trail->parent('home');
    $trail->push('Purchases');
    $trail->push('Debit note');
    $trail->push('Debit note list');
});

Breadcrumbs::for('merchant.debitnote.update', function ($trail, $type, $credit_debit_note) {
    $trail->parent('home');
    if ($type == 1) {
        $trail->push('Sales');
        $trail->push('Credit note list', route('merchant.creditnote.credit-note.list'));
        $trail->push('Update credit note');
    } else {
        $trail->push('Purchases');
        $trail->push('Debit note list', route('merchant.debitnote.debit-note.list'));
        $trail->push('Update debit note');
    }
    $trail->push($credit_debit_note);
});
Breadcrumbs::for('merchant.debitnote.view', function ($trail, $type) {
    $trail->parent('home');
    if ($type == 1) {
        $trail->push('Sales');
        $trail->push('Credit note list', route('merchant.creditnote.credit-note.list'));
        $trail->push('View credit note');
    } else {
        $trail->push('Purchases');
        $trail->push('Debit note list', route('merchant.debitnote.debit-note.list'));
        $trail->push('View debit note');
    }
});

Breadcrumbs::for('merchant.expense.view', function ($trail, $type, $exp_no) {
    $trail->parent('home');
    $trail->push('Purchases');
    if ($type == 1) {
        $trail->push('Expense');
        $trail->push('Expense list', '/merchant/expense/viewlist/expense');
        $trail->push('Expense detail');
    } else {
        $trail->push('Purchase order');
        $trail->push('Purchase order list', '/merchant/expense/viewlist/po');
        $trail->push('Purchase order detail');
    }

    $trail->push($exp_no);
});

Breadcrumbs::for('merchant.expense.pending', function ($trail) {
    $trail->parent('home');
    $trail->push('Purchases');
    $trail->push('Incoming expenses');
});

Breadcrumbs::for('benefitslist', function ($trail) {
    $trail->parent('home');
    $trail->push('Benefits');
});

Breadcrumbs::for('merchant.autocollect.plans', function ($trail) {
    $trail->parent('home');
    $trail->push('Auto collect');
    $trail->push('Plan list');
});
Breadcrumbs::for('merchant.autocollect.create.plan', function ($trail) {
    $trail->parent('home');
    $trail->push('Auto collect');
    $trail->push('Plan list', '/merchant/autocollect/plans');
    $trail->push('Create plan');
});

Breadcrumbs::for('merchant.autocollect.create.subscription', function ($trail) {
    $trail->parent('home');
    $trail->push('Auto collect');
    $trail->push('Subscription list', '/merchant/autocollect/subscriptions');
    $trail->push('Create subscription');
});
Breadcrumbs::for('merchant.autocollect.subscriopnlist', function ($trail) {
    $trail->parent('home');
    $trail->push('Auto collect');
    $trail->push('Subscription list');
});
Breadcrumbs::for('merchant.autocollect.transactionlist', function ($trail) {
    $trail->parent('home');
    $trail->push('Auto collect');
    $trail->push('Transaction list');
});
Breadcrumbs::for('merchant.integrations', function ($trail) {
    $trail->parent('home');
    $trail->push('Integrations');
});

Breadcrumbs::for('merchant.product-attribute.list', function ($trail) {
    $trail->parent('home');
    $trail->push('Settings', url('/merchant/profile/settings'));
    $trail->push('Data configuration');
    $trail->push('Product variations');
});
Breadcrumbs::for('merchant.product-attribute.create', function ($trail) {
    $trail->parent('home');
    $trail->push('Settings', url('/merchant/profile/settings'));
    $trail->push('Data configuration');
    $trail->push('Product variations', url('/merchant/product-attribute/index'));
    $trail->push('Create product variation');
});
Breadcrumbs::for('merchant.product-attribute.update', function ($trail) {
    $trail->parent('home');
    $trail->push('Settings', url('/merchant/profile/settings'));
    $trail->push('Data configuration');
    $trail->push('Product variations', url('/merchant/product-attribute/index'));
    $trail->push('Update product variation');
});

Breadcrumbs::for('home.gstr2a', function ($trail) {
    $trail->parent('home');
    $trail->push('GST');
    $trail->push('GSTR 2B Recon', '/merchant/gst/reconciliation');
});

Breadcrumbs::for('home.bookingcancellations', function ($trail) {
    $trail->parent('home');
    $trail->push('Booking');
    $trail->push('Booking Cancellations List', '/merchant/transaction/booking/cancellations');
});

Breadcrumbs::for('home.gstr2aSummary', function ($trail, $job_id) {
    $trail->parent('home');
    $trail->push('GST');
    $trail->push('GSTR 2B Recon', '/merchant/gst/reconciliation');
    $trail->push('Summary', '/merchant/gst/reconciliation/summary/' . $job_id);
});

Breadcrumbs::for('home.gstr2aDetails', function ($trail, $job_id, $supplier, $status) {
    $trail->parent('home');
    $trail->push('GST');
    $trail->push('GSTR 2B Recon', '/merchant/gst/reconciliation');
    $trail->push('Summary', '/merchant/gst/reconciliation/summary/' . $job_id);
    $trail->push('Detail', '/merchant/gst/reconciliation/detail/' . $job_id . '/' . $supplier . '/' . $status);
});
Breadcrumbs::for('collectlandingpage', function ($trail) {
    $trail->parent('home');
    $trail->push('Collect payments');
});
Breadcrumbs::for('merchant.imports', function ($trail) {
    $trail->parent('home');
    $trail->push('Imports');
});
Breadcrumbs::for('create.view', function ($trail) {
    $trail->parent('home');
    $trail->push('invoices');
    $trail->push('view');
});

Breadcrumbs::for('payyourbills', function ($trail) {
    $trail->parent('home');
    $trail->push('Pay your bills');
});
Breadcrumbs::for('einvoicelist', function ($trail) {
    $trail->parent('home');
    $trail->push('Sales');
    $trail->push('E Invoice list');
});

Breadcrumbs::for('merchant.product.dashboard', function ($trail) {
    $trail->parent('home');
    $trail->push('Inventory', route('inventory'));
    $trail->push('Dashboard');
});

Breadcrumbs::for('home.projectlist', function ($trail) {
    $trail->parent('home');
    $trail->push('Project');
    $trail->push('Project List', '/merchant/project/list');
});
Breadcrumbs::for('home.billcodelist', function ($trail) {
    $trail->parent('home');
    $trail->push('Project');
    $trail->push('Project List', '/merchant/project/list');
    $trail->push('Bill code list');
    
});
Breadcrumbs::for('home.billtransaction', function ($trail) {
    $trail->parent('home');
    $trail->push('Project');
    $trail->push('Project List', '/merchant/project/list');
    $trail->push('Bill transaction');
    
});

Breadcrumbs::for('home.projectcreate', function ($trail) {
    $trail->parent('home');
    $trail->push('Project');
    $trail->push('Project list', '/merchant/project/list');
    $trail->push('Project create', '/merchant/project/create');
});

Breadcrumbs::for('home.projectupdate', function ($trail, $id) {
    $trail->parent('home');
    $trail->push('Project');
    $trail->push('Project list', '/merchant/project/list');
    $trail->push('Project update', '/merchant/project/edit/' . $id);
});
Breadcrumbs::for('home.contractlist', function ($trail) {
    $trail->parent('home');
    $trail->push('Contract');
    $trail->push('Contract list', '/merchant/contract/list');
});

Breadcrumbs::for('home.contractcreate', function ($trail) {
    $trail->parent('home');
    $trail->push('Contract');
    $trail->push('Contract list', '/merchant/contract/list');
    $trail->push('Contract create', '/merchant/contract/create');
});

Breadcrumbs::for('home.contractupdate', function ($trail) {
    $trail->parent('home');
    $trail->push('Contract');
    $trail->push('Contract list', '/merchant/contract/list');
    $trail->push('Contract create', '/merchant/contract/create');
});
Breadcrumbs::for('home.invoice.view', function ($trail) {
    $trail->parent('home');
    $trail->push('Sales');
    $trail->push('Invoice / Estimate list', '/merchant/paymentrequest/viewlist');
    $trail->push('Invoice');
   
});

Breadcrumbs::for('home.orderlist', function ($trail) {
    $trail->parent('home');
    $trail->push('Change order');
    $trail->push('Change order List', '/merchant/order/list');
});

Breadcrumbs::for('home.ordercreate', function ($trail) {
    $trail->parent('home');
    $trail->push('Change order');
    $trail->push('Change order list', '/merchant/order/list');
    $trail->push('Change order create', '/merchant/order/create');
});

Breadcrumbs::for('home.orderupdate', function ($trail) {
    $trail->parent('home');
    $trail->push('Change Order');
    $trail->push('Change order list', '/merchant/order/list');
});
Breadcrumbs::for('merchant.region-setting.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Settings', url('/merchant/profile/settings'));
    $trail->push('Personal preferences');
    $trail->push('Region setting');

});

Breadcrumbs::for('merchant.cost-types.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Settings', url('/merchant/profile/settings'));
    $trail->push('Cost Types');
});

Breadcrumbs::for('merchant.cost-types.create', function ($trail) {
    $trail->parent('home');
    $trail->push('Settings', url('/merchant/profile/settings'));
    $trail->push('Cost Types', url('/merchant/cost-types/index'));
});

Breadcrumbs::for('merchant.cost-types.edit', function ($trail) {
    $trail->parent('home');
    $trail->push('Settings', url('/merchant/profile/settings'));
    $trail->push('Cost Types', url('/merchant/cost-types/index'));
});

Breadcrumbs::for('merchant.import.billCode', function ($trail) {
    $trail->parent('home');
    $trail->push('Imports', url('/merchant/imports'));
    $trail->push('Bill codes');
});
Breadcrumbs::for('merchant.import.contract', function ($trail) {
    $trail->parent('home');
    $trail->push('Imports', url('/merchant/imports'));
    $trail->push('Contract');
});

Breadcrumbs::for('merchant.subusers.create', function ($trail) {
    $trail->parent('home');
    $trail->push('Settings', url('/merchant/profile/settings'));
    $trail->push('Manage Users');
    $trail->push('Submerchant list', url('/merchant/subusers'));
    $trail->push('Create sub-merchant');
});

Breadcrumbs::for('merchant.subusers.edit', function ($trail) {
    $trail->parent('home');
    $trail->push('Settings', url('/merchant/profile/settings'));
    $trail->push('Manage Users');
    $trail->push('Submerchant list', url('/merchant/subusers'));
    $trail->push('Edit sub-merchant');
});

Breadcrumbs::for('merchant.roles.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Settings', url('/merchant/profile/settings'));
    $trail->push('Manage Users');
    $trail->push('Roles');
});

Breadcrumbs::for('merchant.roles.create', function ($trail) {
    $trail->parent('home');
    $trail->push('Settings', url('/merchant/profile/settings'));
    $trail->push('Manage Users');
    $trail->push('Roles list', url('/merchant/roles'));
    $trail->push('Create Role');
});

Breadcrumbs::for('merchant.roles.edit', function ($trail) {
    $trail->parent('home');
    $trail->push('Settings', url('/merchant/profile/settings'));
    $trail->push('Manage Users');
    $trail->push('Roles list', url('/merchant/roles'));
    $trail->push('Edit Role');
});

Breadcrumbs::for('invoicelist', function ($trail) {
    $trail->parent('home');
    $trail->push('Sales');
    $trail->push('Invoice / Estimate list', '/merchant/invoice/list');
});

Breadcrumbs::for('notifications', function ($trail) {
    $trail->parent('home');
    $trail->push('Notifications');
});

Breadcrumbs::for('merchant.user.create-token', function ($trail) {
    $trail->parent('home');
    $trail->push('Settings', url('/merchant/profile/settings'));
    $trail->push('Create Token');
});

Breadcrumbs::for('home.invoice-status', function ($trail) {
    $trail->parent('home');
    $trail->push('Settings', url('/merchant/profile/settings'));
    $trail->push('Invoice Status');
});