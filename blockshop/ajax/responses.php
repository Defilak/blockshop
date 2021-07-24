<?php

namespace responses;


function alert_template($type, $message)
{
    return "<div class=\"mess {$type}\" id=\"shopmsg\">{$message}</div>";
}

function goodly($message)
{
    exit(alert_template('success', $message));
}

function badly($message)
{
    exit(alert_template('error', $message));
}

function infly($message)
{
    exit(alert_template('info', $message));
}
