<?php

namespace responses;


function alert_template($type, $message)
{
    return <<<EOT
        <div class="alert alert-{$type} alert-dismissible fade show" id="shopmsg">
            {$message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    EOT;
}

function infly($message)
{
    return (alert_template('info', $message));
}

function goodly($message)
{
    exit(alert_template('success', $message));
}

function badly($message)
{
    exit(alert_template('error', $message));
}


function text($message)
{
    return (alert_template('info', $message));
}

function warning($message)
{
    exit(alert_template('warning', $message));
}

function success($message)
{
    exit(alert_template('success', $message));
}
