<?php
/**
 * ChunkShield Notifications Helper
 * 
 * This file provides functions for creating and displaying user-friendly
 * notifications including both PHP session-based messages and JavaScript toast
 * notifications.
 */

/**
 * Add a success notification
 * 
 * @param string $message The notification message
 * @return void
 */
function addSuccessNotification($message) {
    $_SESSION['success'] = $message;
}

/**
 * Add an error notification
 * 
 * @param string $message The notification message
 * @return void
 */
function addErrorNotification($message) {
    $_SESSION['error'] = $message;
}

/**
 * Add a warning notification
 * 
 * @param string $message The notification message
 * @return void
 */
function addWarningNotification($message) {
    $_SESSION['warning'] = $message;
}

/**
 * Add an info notification
 * 
 * @param string $message The notification message
 * @return void
 */
function addInfoNotification($message) {
    $_SESSION['info'] = $message;
}

/**
 * Directly output a toast notification with JavaScript
 * Useful for AJAX responses or dynamic notifications
 * 
 * @param string $message The notification message
 * @param string $type The notification type (success, error, warning, info)
 * @param string|null $title Optional title for the notification
 * @param array $options Additional options for the toast
 * @return string JavaScript code to display the toast notification
 */
function getToastScript($message, $type = 'info', $title = null, $options = []) {
    $escapedMessage = htmlspecialchars(str_replace('"', '\"', $message), ENT_QUOTES);
    $titleScript = $title ? '"' . htmlspecialchars(str_replace('"', '\"', $title), ENT_QUOTES) . '"' : 'null';
    
    $optionsJson = '{}';
    if (!empty($options)) {
        $optionsJson = json_encode($options);
    }
    
    return '<script>
        document.addEventListener("DOMContentLoaded", function() {
            if (window.toast) {
                window.toast.' . $type . '("' . $escapedMessage . '", ' . $titleScript . ', ' . $optionsJson . ');
            }
        });
    </script>';
}

/**
 * Display a direct toast notification without using session
 * 
 * @param string $message The notification message
 * @param string $type The notification type (success, error, warning, info)
 * @param string|null $title Optional title for the notification
 * @param array $options Additional options for the toast
 * @return void Outputs the script directly
 */
function showDirectToast($message, $type = 'info', $title = null, $options = []) {
    echo getToastScript($message, $type, $title, $options);
}

/**
 * Add multiple notifications at once
 * 
 * @param array $notifications Array of notifications in format [type => message]
 * @return void
 */
function addMultipleNotifications($notifications) {
    foreach ($notifications as $type => $message) {
        switch ($type) {
            case 'success':
                addSuccessNotification($message);
                break;
            case 'error':
                addErrorNotification($message);
                break;
            case 'warning':
                addWarningNotification($message);
                break;
            case 'info':
                addInfoNotification($message);
                break;
        }
    }
}

/**
 * Format validation errors into a readable HTML list
 * 
 * @param array $errors Array of validation error messages
 * @return string Formatted HTML for displaying errors
 */
function formatValidationErrors($errors) {
    if (empty($errors)) {
        return '';
    }
    
    $output = '<ul class="validation-errors">';
    foreach ($errors as $error) {
        $output .= '<li>' . htmlspecialchars($error) . '</li>';
    }
    $output .= '</ul>';
    
    return $output;
}

/**
 * Add validation errors as an error notification
 * 
 * @param array $errors Array of validation error messages
 * @return void
 */
function addValidationErrors($errors) {
    if (empty($errors)) {
        return;
    }
    
    addErrorNotification(formatValidationErrors($errors));
}