<?php
if (!defined('ABSPATH')) exit;

function srspc_filter_content($content) {
    if (!is_main_query() || !is_single()) return $content;

    if (get_option('srspc_wordcount', '1') || get_option('srspc_charactercount', '1') || get_option('srspc_readtime', '1')) {
        return srspc_generate_html($content);
    }
    
    return $content;
}

function srspc_generate_html($content) {
    $html = '<h3>' . esc_html(__(get_option('srspc_headline', __('Post Statistics', 'srspcdomain')), 'srspcdomain')) . '</h3><p>';

    if (get_option('srspc_wordcount', '1') || get_option('srspc_readtime', '1')) {
        $word_count = str_word_count(strip_tags($content));
    }

    if (get_option('srspc_wordcount', '1')) {
        $html .= __('This post has', 'srspcdomain') . ' ' . $word_count . ' ' . __('words.', 'srspcdomain') . '<br>';
    }

    if (get_option('srspc_charactercount', '1')) {
        $html .= __('This post has', 'srspcdomain') . ' ' . strlen(strip_tags($content)) . ' ' . __('characters.', 'srspcdomain') . '<br>';
    }

    if (get_option('srspc_readtime', '1')) {
        $reading_time_seconds = round(($word_count / 225) * 60);
        $minutes = floor($reading_time_seconds / 60);
        $seconds = $reading_time_seconds % 60;

        $html .= __('This post will take about', 'srspcdomain') . ' ' . $minutes . 'm ' . $seconds . 's ' . __('to read.', 'srspcdomain') . '<br>';
    }

    $html .= '</p>';

    return (get_option('srspc_location', '0') === '0') ? $html . $content : $content . $html;
}
