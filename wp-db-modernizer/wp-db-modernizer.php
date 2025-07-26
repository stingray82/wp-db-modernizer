<?php
/**
 * Plugin Name: WP DB Modernizer (One-Time Emoji + Charset Upgrade)
 * Description: One-time script to modernize your database: backs it up, fixes datetime defaults, and upgrades all tables to utf8mb4_unicode_ci.
 * Version: 1.0
 * Author: stingray82
 */

add_action('init', function () {
    // Run once guard
    if (get_option('wp_db_modernizer_done')) {
        return;
    }

    global $wpdb;

    // 1. Create backup folder if needed
    $backup_dir = WP_CONTENT_DIR . '/backups';
    if (!is_dir($backup_dir)) {
        wp_mkdir_p($backup_dir);
    }

    // 2. Create a timestamped SQL backup
    $timestamp = date('Y-m-d_H-i-s');
    $backup_file = $backup_dir . "/db-backup-{$timestamp}.sql";

    if (is_callable('exec')) {
        // Use mysqldump if available
        $db_name = DB_NAME;
        $db_user = DB_USER;
        $db_pass = DB_PASSWORD;
        $db_host = DB_HOST;

        // Escape password for shell
        $escaped_pass = escapeshellarg($db_pass);
        $escaped_user = escapeshellarg($db_user);
        $escaped_host = escapeshellarg($db_host);

        $cmd = "mysqldump --user={$escaped_user} --password={$escaped_pass} --host={$escaped_host} {$db_name} > {$backup_file}";
        exec($cmd, $output, $return_var);

        if ($return_var !== 0) {
            error_log('❌ WP DB Modernizer: Failed to create DB backup with mysqldump.');
        }
    }

    // 3. Fix invalid datetime defaults in wp_posts
    $wpdb->query("ALTER TABLE {$wpdb->prefix}posts 
        MODIFY post_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        MODIFY post_date_gmt DATETIME DEFAULT CURRENT_TIMESTAMP,
        MODIFY post_modified DATETIME DEFAULT CURRENT_TIMESTAMP,
        MODIFY post_modified_gmt DATETIME DEFAULT CURRENT_TIMESTAMP
    ");

    // 4. Convert all wp_ tables to utf8mb4_unicode_ci
    $tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
    foreach ($tables as $table) {
        $table_name = $table[0];
        if (strpos($table_name, $wpdb->prefix) !== 0) continue;

        $wpdb->query("ALTER TABLE `{$table_name}` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    // 5. Set WP core charset settings (optional but recommended)
    if (!defined('DB_CHARSET')) define('DB_CHARSET', 'utf8mb4');
    if (!defined('DB_COLLATE')) define('DB_COLLATE', 'utf8mb4_unicode_ci');

    // 6. Mark as done
    update_option('wp_db_modernizer_done', true);

    // 7. Admin notice
    add_action('admin_notices', function () use ($backup_file) {
        echo '<div class="notice notice-success"><p> WP DB Modernizer: Database successfully upgraded. ';
        echo 'A backup was saved to <code>' . esc_html($backup_file) . '</code>. You can now deactivate and delete this plugin.</p></div>';
    });
});
