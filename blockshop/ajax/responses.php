<?php

namespace responses;


function alert_template($type, $message)
{
    return "<div class=\"mess {$type}\" id=\"shopmsg\">{$message}</div>";
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

function bad($message)
{
    exit(alert_template('error', $message));
}

function good($message)
{
    exit(alert_template('success', $message));
}
