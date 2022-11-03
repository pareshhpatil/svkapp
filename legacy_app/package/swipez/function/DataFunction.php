<?php

/**
 * Data function class to handle column functions
 */
abstract class DataFunction
{

    public $req_type = NULL;
    public $function_id = NULL;
    public $user_id = NULL;
    public $value = NULL;
    public $script = NULL;
    public $param_name = NULL;
    public $param_value = NULL;

    abstract function set($value);

    abstract function get($type);
}

class ExpiryDate extends DataFunction
{

    function set($value = '')
    {
        if ($value != '') {
            if ($value != 'NA') {
                $expiry_date = new DateTime($value);
                $_POST['expiry_date'] = $expiry_date->format('Y-m-d');
                $this->value = $expiry_date->format('Y-m-d');
            } else {
                $_POST['expiry_date'] = 'NA';
                $this->value = 'NA';
            }
        }
    }

    function get($type)
    {
        return $this->$type;
    }
}

class LateFeePercent extends DataFunction
{

    function set($value = 0)
    {
        if ($value > 0) {
            $late_fee = $_POST['grand_total'] * $value / 100;
            $_POST['late_fee'] = round($late_fee, 2);
            $this->value = $value;
        }
    }

    function get($type)
    {
        return $this->$type;
    }
}

class LateFeeFixed extends DataFunction
{

    function set($value = 0)
    {
        if ($value > 0) {
            $_POST['late_fee'] = $value;
            $this->value = $value;
        }
    }

    function get($type)
    {
        return $this->$type;
    }
}

class BillingPeriod extends DataFunction
{

    function set($value = '')
    {
        $this->value = $value;
    }

    function get($type)
    {
        return $this->$type;
    }
}

class PreviousDue extends DataFunction
{

    function set($value = 0)
    {
        $script = '$("#previous_due").change(function() {
        calculategrandtotal();
        });';

        $this->script = $script;
        $_POST['previous_dues'] = $value;
        $this->value = $value;
    }

    function get($type)
    {
        return $this->$type;
    }
}

class EInvoiceType extends DataFunction
{

    function set($value = 0)
    {
        $_POST['einvoice_type'] = $value;
        $this->value = $value;
    }

    function get($type)
    {
        return $this->$type;
    }
}

class BaseAmount extends DataFunction
{

    function set($value = 0)
    {
        $script = '$("#particulartotal").change(function() {
        calculategrandtotal();
        });';

        $this->script = $script;
        $_POST['totalcost'] = $value;
        $this->value = $value;
    }

    function get($type)
    {
        return $this->$type;
    }
}

class InvoiceNumber extends DataFunction
{

    function set($value = '')
    {
        if ($this->req_type == 'Invoice_update' && $this->param_name == 'system_generated') {
            $script = '$("#invoice_number").attr' . "('readonly','true');";
            $this->script = $script;
        }
        if ($value == '' && $this->param_name == 'system_generated' && $this->req_type != 'Invoice_update') {
            $common = new CommonModel();
            if ($this->req_type == 'Invoice_create') {
                $row = $common->getSingleValue('merchant_auto_invoice_number', 'auto_invoice_id', $this->param_value);
                $this->value = '" placeholder="' . $row['prefix'] . '-System generated" readonly="';
            } elseif ($this->req_type == 'Staging invoice') {
                $this->value = 'System generated' . $this->param_value;
            } else {
                if ($_POST['request_type'] == 2) {
                    $this->value = '';
                } else {
                    $this->value = 'System generated' . $this->param_value;
                }

                // $this->value = $common->getInvoiceNumber($this->user_id, $this->param_value);
            }
        } else {
            $this->value = $value;
        }
        $_POST['invoice_number'] = $this->value;
    }

    function get($type)
    {
        return $this->$type;
    }
}

class CurrentDate extends DataFunction
{

    public function set($value = '')
    {
        if ($value == '') {
            $this->value = date('d M Y');
        } else {
            $this->value = $value;
        }
    }

    public function get($type)
    {
        return $this->$type;
    }
}

class FirstDate extends DataFunction
{

    public function set($value = '')
    {
        if ($value == '') {
            $this->value = date('01 M Y');
        } else {
            $this->value = $value;
        }
    }

    public function get($type)
    {
        return $this->$type;
    }
}

class AddDays extends DataFunction
{

    function set($value = '')
    {
        if ($this->param_name != '') {
            $script = '
                $("#' . $this->param_name . '").change(function() {
                var req_date = document.getElementById(' . "'" . $this->param_name . "'" . ').value;
                     if(req_date=="")
                        {
                        fulldate="";
                        }else{
                var d = new Date(req_date);
                var datenew = new Date(d.getTime() + ' . $this->param_value . ' * 24 * 60 * 60 * 1000);
                var day = datenew.getDate();
                var month = datenew.getMonth() + 1;
                var year = datenew.getFullYear();
                ' . '
                var fulldate = ("0" + day).slice(-2) + " " + monthNames[month] + " " + year;
                }
                try{
                document.getElementById("add_days").value = fulldate;
                } catch(o) {
                    document.getElementById("due_date").value = fulldate;
                }
                });
                ';

            $this->script = $script;
        }

        if ($value == '') {
            $date = strtotime($_POST[$this->param_name] . $this->param_value . ' days');
            $this->value = date('d M Y', $date);
        } else {
            $this->value = $value;
        }
    }

    function get($type)
    {
        return $this->$type;
    }
}

class EndDate extends DataFunction
{

    function set($value = '')
    {
        if ($value == '') {
            $this->value = date('t M Y

                ');
        } else {
            $this->value = $value;
        }
    }

    function get($type)
    {
        return $this->$type;
    }
}

class LastPayment extends DataFunction
{

    function set($value = 0)
    {
        $script = '$("#last_payment").change(function() {
        calculategrandtotal();
        });';

        $this->script = $script;
        $_POST['last_payment'] = $value;
        $this->value = $value;
    }

    function get($type)
    {
        return $this->$type;
    }
}

class Adjustment extends DataFunction
{

    function set($value = 0)
    {
        $script = '$("#adjustment").change(function() {
        calculategrandtotal();
        });';

        $this->script = $script;
        $_POST['adjustment'] = $value;
        $this->value = $value;
    }

    function get($type)
    {
        return $this->$type;
    }
}
